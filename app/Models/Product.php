<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'uuid',
        'slug',
        'sku',
        'category',
        'collection',
        'description',
        'jersey_type',
        'price',
        'image_path',
        'is_active',
        'available_for_preorder',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'available_for_preorder' => 'boolean',
        'stock' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('name');
    }

    /**
     * Get total stock across all variants.
     */
    public function getTotalVariantStockAttribute()
    {
        return $this->variants()->sum('stock');
    }

    /**
     * Check if product has variants.
     */
    public function hasVariants()
    {
        return $this->variants()->count() > 0;
    }
}
