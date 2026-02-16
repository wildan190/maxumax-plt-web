<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Refund;
use Illuminate\Support\Facades\Log;

class StripeService
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createSession(array $lineItems, array $metadata, string $successUrl, string $cancelUrl, ?string $customerEmail = null)
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'customer_email' => $customerEmail,
            'metadata' => $metadata,
        ]);
    }

    public function retrieveSession(string $sessionId)
    {
        return Session::retrieve($sessionId);
    }

    public function refundPayment(?string $paymentIntentId)
    {
        if (!$paymentIntentId) {
            return false;
        }

        try {
            Refund::create(['payment_intent' => $paymentIntentId]);
            return true;
        } catch (\Exception $e) {
            Log::error('Stripe Refund Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Build Stripe line items from cart.
     */
    public function prepareCartLineItems(array $cart, string $currency, array $config): array
    {
        $lineItems = [];
        $orderItems = [];
        $totalAmount = 0;

        foreach ($cart as $it) {
            $product = \App\Models\Product::find($it['product_id']);
            if (!$product || (!$product->is_active && !$product->available_for_preorder)) {
                continue;
            }

            $unit = (float) $product->price * $config['rate'];
            if (!empty($it['long_sleeve'])) {
                $unit += $config['longSleeve'];
            }
            $quantity = (int) $it['quantity'];
            $lineTotal = round($unit * $quantity, 2);
            $totalAmount += $lineTotal;

            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => $product->name,
                        'description' => $this->buildProductDescription($product, $it),
                    ],
                    'unit_amount' => $this->currencyService->convertToCents($unit, $currency),
                ],
                'quantity' => $quantity,
            ];

            $orderItems[] = [
                'product_id' => $product->id,
                'item' => $it,
                'unit' => $unit,
                'line_total' => $lineTotal,
            ];
        }

        return [$lineItems, $orderItems, $totalAmount];
    }

    /**
     * Build Stripe line items for a preorder.
     */
    public function preparePreorderLineItems(\App\Models\Product $product, array $itemsData, string $currency, array $config): array
    {
        $lineItems = [];
        $orderItems = [];
        $totalAmount = 0;
        $allCustomFields = [];
        $namesetCountTotal = 0;

        foreach ($itemsData as $variantId => $itemData) {
            $variant = \App\Models\ProductVariant::find($variantId);
            if (!$variant || $variant->product_id != $product->id)
                continue;

            $types = [
                'ss' => ['qty' => (int) ($itemData['quantity_ss'] ?? 0), 'ls' => false, 'namesets' => $itemData['namesets_ss'] ?? []],
                'ls' => ['qty' => (int) ($itemData['quantity_ls'] ?? 0), 'ls' => true, 'namesets' => $itemData['namesets_ls'] ?? []]
            ];

            foreach ($types as $typeKey => $typeData) {
                $qty = $typeData['qty'];
                if ($qty <= 0)
                    continue;

                $isLongSleeve = $typeData['ls'];
                $unit = (float) $product->price * $config['rate'] + ($isLongSleeve ? $config['longSleeve'] : 0);
                $lineTotal = round($unit * $qty, 2);
                $totalAmount += $lineTotal;

                foreach ($typeData['namesets'] as $ns) {
                    if (!empty($ns['key']) || !empty($ns['value'])) {
                        $allCustomFields[] = $ns;
                        $namesetCountTotal++;
                    }
                }

                $lineItems[] = [
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => [
                            'name' => $product->name . ' (' . $variant->name . ')',
                            'description' => $isLongSleeve ? 'Type: Long Sleeve' : 'Type: Short Sleeve',
                        ],
                        'unit_amount' => $this->currencyService->convertToCents($unit, $currency),
                    ],
                    'quantity' => $qty,
                ];

                $orderItems[] = [
                    'variant_id' => $variantId,
                    'variant_name' => $variant->name,
                    'quantity' => $qty,
                    'long_sleeve' => $isLongSleeve,
                    'unit_price' => $unit,
                    'line_total' => $lineTotal
                ];
            }
        }

        // Add nameset price
        if ($namesetCountTotal > 0) {
            $namesetPrice = $config['nameset'];
            $totalAmount += round($namesetPrice * $namesetCountTotal, 2);
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => ['name' => 'Jersey Customization (Nameset)'],
                    'unit_amount' => $this->currencyService->convertToCents($namesetPrice, $currency),
                ],
                'quantity' => $namesetCountTotal,
            ];
        }

        return [$lineItems, $orderItems, $totalAmount, $allCustomFields];
    }

    private function buildProductDescription(\App\Models\Product $product, array $item): string
    {
        $desc = $product->name;
        if (!empty($item['size'])) {
            $desc .= ' (Size: ' . $item['size'] . ')';
        }
        if (!empty($item['long_sleeve'])) {
            $desc .= ' - Long Sleeve';
        }
        return $desc;
    }
}
