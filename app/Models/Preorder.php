<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'email',
        'phone',
        'address',
        'jersey_type',
        'size',
        'long_sleeve',
        'custom_fields',
        'quantity',
        'unit_price',
        'total_amount',
        'currency',
        'status',
        'notes'
    ];

    protected $casts = [
        'long_sleeve' => 'boolean',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'custom_fields' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function histories()
    {
        return $this->hasMany(PreorderHistory::class);
    }
}
