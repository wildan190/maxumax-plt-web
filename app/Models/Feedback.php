<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'email',
        'rating',
        'comment',
    ];
}
