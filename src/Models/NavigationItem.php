<?php

namespace Soda\Navigation\Models;

use Soda\Cms\Database\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Soda\Cms\Database\Models\Traits\Draftable;
use Soda\Navigation\Models\Traits\Treeable;
use Soda\Cms\Database\Models\Traits\Positionable;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class NavigationItem extends Model
{
    use OptionallyBoundToApplication, Positionable, Draftable, Treeable;

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
            default:
                return $this->slug_value;
                break;
        }
    }

    public function deepMatchesUrl()
    {
        if ($this->matchesUrl()) {
            return true;
        }

        foreach ($this->children as $childNavigationItem) {
            if ($childNavigationItem->deepMatchesUrl()) {
                return true;
            }
        }

        return false;
    }

    public function matchesUrl()
    {
        return Request::is(ltrim($this->getUrl(), '/'));
    }
}
