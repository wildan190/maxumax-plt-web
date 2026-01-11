<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'is_highlight',
        'description',
    ];

    protected $casts = [
        'is_highlight' => 'boolean',
    ];
}
