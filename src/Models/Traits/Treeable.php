<?php

namespace Soda\Navigation\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Soda\Navigation\Support\NavigationItemCollection;

trait Treeable
{
    /**
     * Cached "previous" (i.e. before the model is moved) direct ancestor id of this model.
     *
     * @var int
     */
    protected $old_parent_id;

    /**
     * Cached "previous" (i.e. before the model is moved) model position.
     *
     * @var int
     */
    protected $old_position;

    /**
     * Indicates if the model is being moved to another ancestor.
     *
     * @var bool
     */
    protected $isMoved = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function bootTreeable()
    {
        // If model's parent identifier was changed,
        // the closure table rows will update automatically.
        static::saving(function (Model $model) {
            $model->clampPosition();
            $model->moveNode();
        });

        // When entity is created, the appropriate
        // data will be put into the closure table.
        static::created(function (Model $model) {
            $model->old_parent_id = false;
            $model->old_position = $model->position;
        });

        // Everytime the model's position or parent
        // is changed, its siblings reordering will happen,
        // so they will always keep the proper order.
        static::saved(function (Model $model) {
            $model->reorderSiblings();
        });
    }

    public function newFromBuilder($attributes = [], $connection = null)
    {
        $instance = parent::newFromBuilder($attributes);
        $instance->old_parent_id = $instance->parent_id;
        $instance->old_position = $instance->position;

        return $instance;
    }

    /**
     * Gets the "children" relation index.
     *
     * @return string
     */
    public function getChildrenRelationIndex()
    {
        return 'children';
    }

    /**
     * Gets value of the "parent id" attribute.
     *
     * @return int
     */
    public function getParentIdAttribute()
    {
        return $this->getAttributeFromArray($this->getParentIdColumn());
    }

    /**
     * Sets new parent id and caches the old one.
     *
     * @param int $value
     */
    public function setParentIdAttribute($value)
    {
        if ($this->parent_id === $value) {
            return;
        }
        $this->old_parent_id = $this->parent_id;
        $this->attributes[$this->getParentIdColumn()] = $value;
    }

    /**
     * Gets the fully qualified "parent id" column.
     *
     * @return string
     */
    public function getQualifiedParentIdColumn()
    {
        return $this->getTable().'.'.$this->getParentIdColumn();
    }

    /**
     * Gets the short name of the "parent id" column.
     *
     * @return string
     */
    public function getParentIdColumn()
    {
        return 'parent_id';
    }

    /**
     * Gets value of the "position" attribute.
     *
     * @return int
     */
    public function getPositionAttribute()
    {
        return $this->getAttributeFromArray($this->getPositionColumn());
    }

    /**
     * Sets new position and caches the old one.
     *
     * @param int $value
     */
    public function setPositionAttribute($value)
    {
        if ($this->position === $value) {
            return;
        }
        $this->old_position = $this->position;
        $this->attributes[$this->getPositionColumn()] = intval($value);
    }

    /**
     * Gets the fully qualified "position" column.
     *
     * @return string
     */
    public function getQualifiedPositionColumn()
    {
        return $this->getTable().'.'.$this->getPositionColumn();
    }

    /**
     * Gets the short name of the "position" column.
     *
     * @return string
     */
    public function getPositionColumn()
    {
        return 'position';
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array $models
     *
     * @return NavigationItemCollection
     */
    public function newCollection(array $models = [])
    {
        return new NavigationItemCollection($models);
    }

    /**
     * Builds a part of the siblings query.
     *
     * @param int|bool         $parentId
     *
     * @return QueryBuilder
     */
    protected function siblings($parentId = false)
    {
        $parentId = ($parentId === false ? $this->parent_id : $parentId);

        /**
         * @var QueryBuilder
         */
        $query = $this->where($this->getParentIdColumn(), '=', $parentId);

        return $query;
    }

    /**
     * Shorthand of the children query part.
     *
     * @param mixed $id
     *
     * @return QueryBuilder
     */
    protected function children($id = null)
    {
        $id = ($id ?: $this->getKey());

        return $this->where($this->getParentIdColumn(), '=', $id);
    }

    /**
     * Retrieves all children of a model.
     *
     * @param array $columns
     *
     * @return \Franzose\ClosureTable\Extensions\Collection
     */
    public function getChildren(array $columns = ['*'])
    {
        if ($this->hasChildrenRelation()) {
            $result = $this->getRelation($this->getChildrenRelationIndex());
        } else {
            $result = $this->children()->get($columns);
        }

        return $result;
    }

    /**
     * Returns a number of model's children.
     *
     * @return int
     */
    public function countChildren()
    {
        if ($this->hasChildrenRelation()) {
            $result = $this->getRelation($this->getChildrenRelationIndex())->count();
        } else {
            $result = $this->children()->count();
        }

        return $result;
    }

    /**
     *  Indicates whether a model has children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return ! ! $this->countChildren();
    }

    /**
     * Indicates whether a model has children as a relation.
     *
     * @return bool
     */
    public function hasChildrenRelation()
    {
        return array_key_exists($this->getChildrenRelationIndex(), $this->getRelations());
    }

    /**
     * Pushes a new item to a relation.
     *
     * @param $relation
     * @param $value
     *
     * @return $this
     */
    public function appendRelation($relation, $value)
    {
        if (! array_key_exists($relation, $this->getRelations())) {
            $this->setRelation($relation, new NavigationItemCollection([$value]));
        } else {
            $this->getRelation($relation)->add($value);
        }

        return $this;
    }

    /**
     * Makes the model a child or a root with given position. Do not use moveTo to move a node within the same ancestor (call position = value and save instead).
     *
     * @param int $position
     * @param     $parentId
     *
     * @return Entity
     * @throws \InvalidArgumentException
     */
    public function moveTo($position, $parentId = null)
    {
        $parentId = $parentId instanceof Model ? $parentId->getKey() : $parentId;

        if ($this->parent_id == $parentId && $this->position == $position) {
            return $this;
        }

        if ($this->getKey() == $parentId) {
            throw new \InvalidArgumentException('Target entity is equal to the sender.');
        }

        $this->parent_id = $parentId;
        $this->position = $position;

        $this->isMoved = true;

        $this->save();

        $this->isMoved = false;

        return $this;
    }

    /**
     * Perform a model insert operation.
     *
     * @param  Builder $query
     * @param  array   $options
     *
     * @return bool
     */
    protected function performInsert(Builder $query, array $options = [])
    {
        if ($this->isMoved === false) {
            $this->position = $this->position !== null ? $this->position : $this->getNextAfterLastPosition();
        }

        return parent::performInsert($query, $options);
    }

    /**
     * Gets the next sibling position after the last one at the given ancestor.
     *
     * @param int|bool $parentId
     *
     * @return int
     */
    public function getNextAfterLastPosition($parentId = false)
    {
        $position = $this->getLastPosition($parentId);

        return $position === null ? 0 : $position + 1;
    }

    public function getLastPosition($parentId = false)
    {
        $positionColumn = $this->getPositionColumn();
        $parentIdColumn = $this->getParentIdColumn();

        $parentId = ($parentId === false ? $this->parent_id : $parentId);

        $entity = $this->select($positionColumn)
            ->where($parentIdColumn, '=', $parentId)
            ->orderBy($positionColumn, 'desc')
            ->first();

        return ! is_null($entity) ? (int) $entity->position : null;
    }

    /**
     * Clamp the position between 0 and the last position of the current parent.
     */
    protected function clampPosition()
    {
        if (! $this->isDirty($this->getPositionColumn())) {
            return;
        }
        $newPosition = max(0, min($this->position, $this->getNextAfterLastPosition()));
        $this->attributes[$this->getPositionColumn()] = $newPosition;
    }

    /**
     * Moves node to another ancestor.
     *
     * @return void
     */
    protected function moveNode()
    {
        if ($this->exists && $this->isDirty($this->getParentIdColumn())) {
            $this->reorderSiblings(true);
        }
    }

    /**
     * Reorders model's siblings when one is moved to another position or ancestor.
     *
     * @param bool $parentIdChanged
     *
     * @return void
     */
    protected function reorderSiblings($parentIdChanged = false)
    {
        list($range, $action) = $this->setupReordering($parentIdChanged);

        $positionColumn = $this->getPositionColumn();

        // As the method called twice (before moving and after moving),
        // first we gather "old" siblings by the old parent id value of the model.
        if ($parentIdChanged === true) {
            $query = $this->siblings($this->old_parent_id);
        } else {
            $query = $this->siblings();
        }

        if ($action) {
            $this->buildWherePosition($query, $positionColumn, $range)
                ->where($this->getKeyName(), '<>', $this->getKey())
                ->$action($positionColumn);
        }
    }

    /**
     * Setups model's siblings reordering.
     *
     * Actually, the method determines siblings that will be reordered
     * by creating range of theirs positions and determining the action
     * that will be used in reordering ('increment' or 'decrement').
     *
     * @param bool $parentIdChanged
     *
     * @return array
     */
    protected function setupReordering($parentIdChanged)
    {
        $range = $action = null;
        // If the model's parent was changed, firstly we decrement
        // positions of the 'old' next siblings of the model.
        if ($parentIdChanged === true) {
            $range = $this->old_position;
            $action = 'decrement';
        } else {
            // TODO: There's probably a bug here where if you just created an entity and you set it to be
            // a root (parent_id = null) then it comes in here (while it should have gone in the else)
            // Reordering within the same ancestor
            if ($this->old_parent_id !== false && $this->old_parent_id == $this->parent_id) {
                if ($this->position > $this->old_position) {
                    $range = [$this->old_position, $this->position];
                    $action = 'decrement';
                } elseif ($this->position < $this->old_position) {
                    $range = [$this->position, $this->old_position];
                    $action = 'increment';
                }
            } // Ancestor has changed
            else {
                $range = $this->position;
                $action = 'increment';
            }
        }

        if (! is_array($range)) {
            $range = [$range, null];
        }

        return [$range, $action];
    }

    /**
     * Builds "where position" query part based on given values.
     *
     * @param Builder $query
     * @param string  $column
     * @param array   $values
     *
     * @return $this
     */
    public function buildWherePosition(Builder $query, $column, array $values)
    {
        if (count($values) == 1 || is_null($values[1])) {
            $query->where($column, '>=', $values[0]);
        } else {
            $query->whereIn($column, range($values[0], $values[1]));
        }

        return $query;
    }
}
