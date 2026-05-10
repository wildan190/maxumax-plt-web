<?php

namespace App\Services\Admin;

use App\Models\Preorder;
use App\Models\ProductVariant;

class PreorderStockLedgerService
{
    /**
     * Decrement stock when admin marks order paid. Returns note suffix to append to history.
     */
    public function decrementOnMarkPaid(Preorder $preorder): string
    {
        $note = '';
        if (!$preorder->product) {
            return $note;
        }

        if (!empty($preorder->items) && is_array($preorder->items)) {
            $note .= '. Stock decremented for items:';
            foreach ($preorder->items as $item) {
                $vid = $item['variant_id'] ?? null;
                $qty = $item['quantity'] ?? 0;
                if ($vid && $qty > 0) {
                    $variant = ProductVariant::lockForUpdate()->find($vid);
                    if ($variant) {
                        $variant->stock = max(0, $variant->stock - $qty);
                        $variant->save();
                        $note .= " [{$variant->name}: -{$qty}]";
                    }
                }
            }

            return $note;
        }

        if ($preorder->product_variant_id) {
            $variant = ProductVariant::find($preorder->product_variant_id);
            if ($variant && $variant->stock >= $preorder->quantity && $variant->stock > 0) {
                $variant->stock = max(0, $variant->stock - $preorder->quantity);
                $variant->save();
                $note .= '. Variant stock decremented by ' . $preorder->quantity . ' (variant: ' . $variant->name . ', remaining: ' . $variant->stock . ')';
            } else {
                $note .= '. Variant stock insufficient or zero; no decrement performed.';
            }

            return $note;
        }

        $product = $preorder->product;
        if ($product->stock >= $preorder->quantity && $product->stock > 0) {
            $product->stock = max(0, $product->stock - $preorder->quantity);
            $product->save();
            $note .= '. Stock decremented by ' . $preorder->quantity . ' (remaining: ' . $product->stock . ')';
        } else {
            $note .= '. Product stock insufficient or zero; no decrement performed.';
        }

        return $note;
    }

    /**
     * Restore stock after approved refund (retail products only in original logic).
     */
    public function restoreAfterRefund(Preorder $order): void
    {
        if (!$order->product || $order->product->available_for_preorder) {
            return;
        }

        if (!empty($order->items) && is_array($order->items)) {
            foreach ($order->items as $item) {
                $vid = $item['variant_id'] ?? null;
                $qty = $item['quantity'] ?? 0;
                if ($vid && $qty > 0) {
                    $variant = ProductVariant::lockForUpdate()->find($vid);
                    if ($variant) {
                        $variant->stock += $qty;
                        $variant->save();
                    }
                }
            }

            return;
        }

        if ($order->product_variant_id) {
            $variant = ProductVariant::find($order->product_variant_id);
            if ($variant) {
                $variant->stock = $variant->stock + $order->quantity;
                $variant->save();
            }

            return;
        }

        $product = $order->product;
        $product->stock = $product->stock + $order->quantity;
        $product->save();
    }
}
