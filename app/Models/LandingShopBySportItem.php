<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingShopBySportItem extends Model
{
    protected $fillable = [
        'sort_order',
        'image_path',
        'label',
        'sport_param',
    ];
}
