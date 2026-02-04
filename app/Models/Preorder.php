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
        'product_variant_id',
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
        'stripe_payment_intent_id',
        'stripe_session_id',
        'refund_status',
        'refund_amount',
        'stripe_refund_id',
        'refund_reason',
        'tracking_number',
        'shipping_status',
        'shipping_courier_name',
        'shipping_courier_logo',
        'shipping_service_name',
        'shipping_service_id',
        'shipping_cost',
        'items', // JSON column for multi-item orders
        'uuid',  // Ensure UUID is fillable if manually setting it (though booted logic handles it)
    ];

    protected $casts = [
        'long_sleeve' => 'boolean',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'custom_fields' => 'array',
        'items' => 'array',
        'shipping_cost' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function histories()
    {
        return $this->hasMany(PreorderHistory::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Get the timestamp when the order was delivered/received.
     */
    public function getDeliveryTimestamp()
    {
        // Try to find in history notes first (most accurate for timestamp)
        $deliveryHistory = $this->histories->filter(function ($h) {
            return stripos($h->note, 'diterima') !== false ||
                stripos($h->note, 'delivered') !== false ||
                stripos($h->note, 'received') !== false ||
                $h->new_status === 'delivered';
        })->first();

        if ($deliveryHistory) {
            return $deliveryHistory->created_at;
        }

        // Fallback to updated_at if shipping_status is delivered
        if ($this->shipping_status === 'delivered') {
            return $this->updated_at;
        }

        return null;
    }

    protected static function booted()
    {
        static::creating(function (Preorder $pre) {
            if (empty($pre->uuid)) {
                $pre->uuid = (string) Str::uuid();
            }
            if (empty($pre->order_number)) {
                $prefix = 'MM-PO-';
                if ($pre->product_id) {
                    $product = Product::find($pre->product_id);
                    if ($product) {
                        $prefix = $product->available_for_preorder ? 'MM-PO-' : 'MM-OR-';
                    }
                }
                do {
                    $candidate = $prefix . strtoupper(Str::random(8));
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
