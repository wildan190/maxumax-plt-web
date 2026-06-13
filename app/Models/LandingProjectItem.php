<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingProjectItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort_order',
        'image_path',
        'category',
        'title',
        'headline',
        'subhead',
        'description',
        'gallery',
    ];

    protected $casts = [
        'gallery' => 'array',
    ];
}
