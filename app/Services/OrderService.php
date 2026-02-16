<?php

namespace App\Services;

use App\Models\Preorder;
use App\Models\PreorderHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Generate a unique order number.
     */
    public function generateOrderNumber(Product $product): string
    {
        $prefix = $product->available_for_preorder ? 'MM-PO-' : 'MM-OR-';
        do {
            $code = $prefix . strtoupper(Str::random(8));
        } while (Preorder::where('order_number', $code)->exists());

        return $code;
    }

    /**
     * Get reserved quantity for a product.
     */
    public function getReservedQty(Product $product): int
    {
        return (int) Preorder::where('product_id', $product->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('quantity');
    }

    /**
     * Process order creation in a transaction.
     */
    public function createOrder(array $data, Product $product, array $itemsData, string $currency, array $config)
    {
        return DB::transaction(function () use ($data, $itemsData, $product, $config, $currency) {
            $uuid = (string) Str::uuid();
            $fullAddress = $data['address'] ?? $this->formatAddress($data);

            $totalQty = 0;
            $totalAmount = 0;
            $orderItems = [];
            $firstVariantId = null;
            $firstVariantName = null;
            $hasLongSleeveGlobal = false;
            $allCustomFields = [];

            foreach ($itemsData as $variantId => $itemData) {
                $types = [
                    'ss' => ['qty' => (int) ($itemData['quantity_ss'] ?? 0), 'ls' => false, 'namesets' => $itemData['namesets_ss'] ?? []],
                    'ls' => ['qty' => (int) ($itemData['quantity_ls'] ?? 0), 'ls' => true, 'namesets' => $itemData['namesets_ls'] ?? []]
                ];

                $variantTotalQty = $types['ss']['qty'] + $types['ls']['qty'];

                if (!$product->available_for_preorder) {
                    $variant = ProductVariant::lockForUpdate()->find($variantId);
                    if ($variant && $variant->product_id == $product->id) {
                        if ($variant->stock < $variantTotalQty) {
                            throw new \RuntimeException("Not enough stock for variant {$variant->name}");
                        }
                        $variant->stock -= $variantTotalQty;
                        $variant->save();
                    }
                } else {
                    $variant = ProductVariant::find($variantId);
                }

                if (!$firstVariantId) {
                    $firstVariantId = $variantId;
                    $firstVariantName = $variant ? $variant->name : null;
                }

                foreach ($types as $typeKey => $typeData) {
                    $qty = $typeData['qty'];
                    if ($qty <= 0)
                        continue;

                    if ($typeData['ls'])
                        $hasLongSleeveGlobal = true;

                    $unitBase = (float) $product->price * $config['rate'];
                    $unitSurcharge = $typeData['ls'] ? $config['longSleeve'] : 0;

                    $validNamesets = array_filter($typeData['namesets'], fn($ns) => !empty($ns['key']) || !empty($ns['value']));
                    foreach ($validNamesets as $ns)
                        $allCustomFields[] = $ns;

                    $namesetCount = count($validNamesets);
                    $variantTotal = ($unitBase + $unitSurcharge) * $qty + ($namesetCount * $config['nameset']);

                    $totalQty += $qty;
                    $totalAmount += $variantTotal;

                    $orderItems[] = [
                        'variant_id' => $variantId,
                        'variant_name' => ($variant ? $variant->name : 'Unknown') . ($typeData['ls'] ? ' (Long Sleeve)' : ' (Short Sleeve)'),
                        'quantity' => $qty,
                        'long_sleeve' => $typeData['ls'],
                        'custom_fields' => array_values($validNamesets),
                        'unit_price' => $unitBase,
                        'surcharges' => [
                            'long_sleeve' => $typeData['ls'] ? ($config['longSleeve'] * $qty) : 0,
                            'nameset' => $namesetCount * $config['nameset']
                        ],
                        'total_price' => round($variantTotal, 2)
                    ];
                }
            }

            $orderNumber = $this->generateOrderNumber($product);
            $convertedShippingCost = (float) ($data['shipping_cost'] ?? 0) * $config['rate'];
            $totalAmount += $convertedShippingCost;
            $unitPriceAvg = $totalQty > 0 ? (($totalAmount - $convertedShippingCost) / $totalQty) : 0;

            $pre = Preorder::create([
                'uuid' => $uuid,
                'order_number' => $orderNumber,
                'product_id' => $product->id,
                'product_variant_id' => $firstVariantId,
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $fullAddress,
                'jersey_type' => $product->jersey_type ?? null,
                'size' => $firstVariantName,
                'long_sleeve' => $hasLongSleeveGlobal,
                'custom_fields' => !empty($allCustomFields) ? $allCustomFields : ($data['custom_fields'] ?? null),
                'quantity' => $totalQty,
                'unit_price' => $unitPriceAvg,
                'total_amount' => $totalAmount,
                'currency' => $currency,
                'status' => $data['status'] ?? 'pending',
                'notes' => $data['notes'] ?? null,
                'items' => $orderItems,
                'shipping_courier_name' => $data['shipping_courier_name'] ?? null,
                'shipping_courier_logo' => $data['shipping_courier_logo'] ?? null,
                'shipping_service_name' => $data['shipping_service_name'] ?? null,
                'shipping_service_id' => $data['shipping_service_id'] ?? null,
                'shipping_cost' => $convertedShippingCost,
                'stripe_payment_intent_id' => $data['stripe_payment_intent_id'] ?? null,
                'stripe_session_id' => $data['stripe_session_id'] ?? null,
            ]);

            PreorderHistory::create([
                'preorder_id' => $pre->id,
                'old_status' => null,
                'new_status' => $pre->status,
                'note' => $data['history_note'] ?? 'Order created',
            ]);

            return $pre;
        });
    }

    public function formatAddress(array $data): string
    {
        return trim(implode(', ', array_filter([
            $data['address_detail'] ?? null,
            $data['city'] ?? null,
            $data['province'] ?? null,
            'Postal ' . ($data['postal_code'] ?? ''),
            $data['region'] ?? null,
        ])));
    }

    /**
     * Dispatch notifications for a successful order.
     */
    public function notifySuccess(Preorder $order): void
    {
        // Send Email to buyer
        if ($order->email) {
            \App\Jobs\SendEmailJob::dispatch($order->email, new \App\Mail\OrderCreated($order), 2);
            \App\Jobs\SendEmailJob::dispatch($order->email, new \App\Mail\PaymentSuccess($order), 5);
        }

        // Database notifications for admins
        $admins = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
        if ($admins->isNotEmpty()) {
            $notification = str_starts_with($order->order_number, 'MM-PO-') ? new \App\Notifications\NewPreorderNotification($order) : new \App\Notifications\NewOrderNotification($order);
            \Illuminate\Support\Facades\Notification::send($admins, $notification);

            // Database notifications for buyer (if registered)
            if ($order->email) {
                $buyer = \App\Models\User::where('email', $order->email)->first();
                if ($buyer) {
                    $buyer->notify($notification);
                }
            }
        }
    }

    /**
     * Create multiple orders from Stripe checkout data.
     */
    public function createOrdersFromStripe(array $checkoutData, string $sessionId, ?string $paymentIntentId): array
    {
        $currency = $checkoutData['currency'];
        $config = $this->currencyService->getCurrencyConfig($currency);
        $orders = [];

        return \Illuminate\Support\Facades\DB::transaction(function () use ($checkoutData, $sessionId, $paymentIntentId, $currency, $config) {
            foreach ($checkoutData['order_items'] as $orderItem) {
                // Determine itemsData format based on whether it's regular cart or preorder session
                if (isset($orderItem['item'])) {
                    // Regular Cart
                    $it = $orderItem['item'];
                    $product = \App\Models\Product::findOrFail($orderItem['product_id']);
                    $itemsData = [
                        $it['product_variant_id'] ?? 'legacy' => [
                            'quantity_ss' => $it['long_sleeve'] ? 0 : $it['quantity'],
                            'quantity_ls' => $it['long_sleeve'] ? $it['quantity'] : 0,
                            'namesets_ss' => [],
                            'namesets_ls' => []
                        ]
                    ];
                } else {
                    // Preorder Session
                    $product = \App\Models\Product::findOrFail($checkoutData['product_id']);
                    $variantId = $orderItem['variant_id'];
                    $itemsData = [
                        $variantId => [
                            'quantity_ss' => $orderItem['long_sleeve'] ? 0 : $orderItem['quantity'],
                            'quantity_ls' => $orderItem['long_sleeve'] ? $orderItem['quantity'] : 0,
                            'namesets_ss' => [],
                            'namesets_ls' => []
                        ]
                    ];
                }

                $order = $this->createOrder(
                    array_merge($checkoutData['order_data'], [
                        'status' => 'paid',
                        'stripe_payment_intent_id' => $paymentIntentId,
                        'stripe_session_id' => $sessionId,
                        'history_note' => 'Order via Stripe payment - automatically paid',
                        'shipping_cost' => $checkoutData['shipping_data']['shipping_cost'] ?? 0,
                        'shipping_courier_name' => $checkoutData['shipping_data']['shipping_courier_name'] ?? null,
                        'shipping_service_id' => $checkoutData['shipping_data']['shipping_service_id'] ?? null,
                    ]),
                    $product,
                    $itemsData,
                    $currency,
                    $config
                );

                $this->notifySuccess($order);
                $orders[] = $order;
            }

            return $orders;
        });
    }
}
