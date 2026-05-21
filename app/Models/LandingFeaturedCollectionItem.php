<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingFeaturedCollectionItem extends Model
{
    protected $fillable = [
        'sort_order',
        'image_path',
        'label',
        'filter_param',
    ];
}
