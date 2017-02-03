<?php

namespace Soda\Navigation\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class NavigationItem extends Model
{
    use OptionallyInApplicationTrait;

    protected $table = 'navigation_items';

    protected $fillable = [
        'name',
    ];
}
