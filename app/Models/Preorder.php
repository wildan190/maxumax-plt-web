<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Preorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
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
        'notes',
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

    protected static function booted()
    {
        static::creating(function (Preorder $pre) {
            if (empty($pre->order_number)) {
                $prefix = 'MM-PO-';
                if ($pre->product_id) {
                    $product = Product::find($pre->product_id);
                    if ($product) {
                        $prefix = $product->available_for_preorder ? 'MM-PO-' : 'MM-OR-';
                    }
                }
                do {
                    $candidate = $prefix.strtoupper(Str::random(8));
                } while (Preorder::where('order_number', $candidate)->exists());
                $pre->order_number = $candidate;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'order_number';
    }
}
