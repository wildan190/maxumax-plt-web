<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'stock',
        'is_available',
    ];

    protected $casts = [
        'stock' => 'integer',
        'is_available' => 'boolean',
    ];

    /**
     * Get the product that owns this variant.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if variant has stock available.
     */
    public function hasStock($quantity = 1)
    {
        return $this->is_available && $this->stock >= $quantity;
    }

    /**
     * Decrease stock by given quantity.
     */
    public function decreaseStock($quantity = 1)
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Increase stock by given quantity.
     */
    public function increaseStock($quantity = 1)
    {
        $this->stock += $quantity;
        $this->save();
    }
}
