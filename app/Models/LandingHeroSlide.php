<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingHeroSlide extends Model
{
    protected $fillable = [
        'sort_order',
        'image_path',
        'title',
        'body',
        'buttons',
    ];

    protected $casts = [
        'buttons' => 'array',
    ];
}
