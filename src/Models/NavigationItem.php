<?php

namespace Soda\Navigation\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Models\Page;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Soda\Cms\Models\Traits\PositionableTrait;
use Soda\Navigation\Models\Traits\Treeable;

class NavigationItem extends Model
{
    use OptionallyInApplicationTrait, PositionableTrait, DraftableTrait, Treeable;

    protected $table = 'navigation_items';

    public $fillable = [
        'name',
        'slug_type',
        'slug_value',
        'application_id',
        'status',
        'position',
        'parent_id',
    ];

    const SLUG_TYPE_PAGE = 'page';

    const SLUG_TYPE_URL = 'url';

    public function getUrl()
    {
        switch ($this->slug_type) {
            case static::SLUG_TYPE_PAGE:
                return Page::find($this->slug_value)->slug;
                break;
        }
    }
}
