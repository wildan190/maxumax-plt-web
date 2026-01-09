<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\PreorderHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create Stripe checkout session
     */
    public function createCheckoutSession(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
            'currency' => 'nullable|string|in:MYR,BND,IDR',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Cart kosong']);
        }

        $currency = $data['currency'] ?? session()->get('currency', 'MYR');
        $config = $this->getCurrencyConfig($currency);
        
        // Calculate total and create line items
        $lineItems = [];
        $totalAmount = 0;
        $orderItems = [];

        foreach ($cart as $it) {
            $product = Product::where('id', $it['product_id'])->first();
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

            // Convert to cents for Stripe (Stripe uses smallest currency unit)
            $amountInCents = $this->convertToCents($lineTotal, $currency);

            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => $product->name,
                        'description' => $this->buildProductDescription($product, $it),
                    ],
                    'unit_amount' => $amountInCents,
                ],
                'quantity' => $quantity,
            ];

            $orderItems[] = [
                'product_id' => $product->id,
                'product' => $product,
                'item' => $it,
                'unit' => $unit,
                'line_total' => $lineTotal,
            ];
        }

        if (empty($lineItems)) {
            return back()->withErrors(['cart' => 'Tidak ada produk valid di cart']);
        }

        try {
            // Create Stripe checkout session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
                'customer_email' => $data['email'] ?? null,
                'metadata' => [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'currency' => $currency,
                    'notes' => $data['notes'] ?? '',
                ],
            ]);

            // Store checkout session data in session for later use
            session()->put('stripe_checkout', [
                'session_id' => $checkoutSession->id,
                'order_data' => $data,
                'order_items' => $orderItems,
                'currency' => $currency,
                'total_amount' => $totalAmount,
            ]);

            return redirect($checkoutSession->url);
        } catch (ApiErrorException $e) {
            return back()->withErrors(['stripe' => 'Error creating payment session: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            return redirect()->route('cart.show')->withErrors(['payment' => 'Invalid payment session']);
        }

        try {
            $checkoutSession = Session::retrieve($sessionId);
            
            if ($checkoutSession->payment_status !== 'paid') {
                return redirect()->route('cart.show')->withErrors(['payment' => 'Payment not completed']);
            }

            $checkoutData = session()->get('stripe_checkout');
            if (!$checkoutData || $checkoutData['session_id'] !== $sessionId) {
                return redirect()->route('cart.show')->withErrors(['payment' => 'Invalid checkout session']);
            }

            $currency = $checkoutData['currency'];
            $config = $this->getCurrencyConfig($currency);
            $orders = [];

            // Create orders for each item
            foreach ($checkoutData['order_items'] as $orderItem) {
                $product = $orderItem['product'];
                $it = $orderItem['item'];
                $unit = $orderItem['unit'];
                $lineTotal = $orderItem['line_total'];

                $pre = DB::transaction(function () use ($product, $it, $checkoutData, $unit, $lineTotal, $currency, $sessionId) {
                    $product = Product::where('id', $product->id)->lockForUpdate()->first();
                    if (!$product || (!$product->is_active && !$product->available_for_preorder)) {
                        return null;
                    }

                    if (!$product->available_for_preorder) {
                        $reserved = $this->getReservedQty($product);
                        $free = (int) $product->stock - $reserved;
                        if ($free < (int) $it['quantity']) {
                            return null;
                        }
                    }

                    $orderNumber = $this->generateOrderNumberForProduct($product);
                    $pre = Preorder::create([
                        'order_number' => $orderNumber,
                        'product_id' => $product->id,
                        'name' => $checkoutData['order_data']['name'],
                        'email' => $checkoutData['order_data']['email'] ?? null,
                        'phone' => $checkoutData['order_data']['phone'] ?? null,
                        'address' => $checkoutData['order_data']['address'] ?? null,
                        'jersey_type' => $product->jersey_type ?? null,
                        'size' => $it['size'] ?? null,
                        'long_sleeve' => !empty($it['long_sleeve']),
                        'custom_fields' => null,
                        'quantity' => (int) $it['quantity'],
                        'unit_price' => $unit,
                        'total_amount' => $lineTotal,
                        'currency' => $currency,
                        'status' => 'paid', // Mark as paid since Stripe payment succeeded
                        'notes' => $checkoutData['order_data']['notes'] ?? null,
                    ]);

                    PreorderHistory::create([
                        'preorder_id' => $pre->id,
                        'old_status' => null,
                        'new_status' => 'paid',
                        'note' => 'Order via Stripe payment (Session: ' . ($checkoutData['session_id'] ?? 'unknown') . ')',
                    ]);

                    // Decrement stock if product exists
                    if ($pre->product && !$product->available_for_preorder) {
                        $product = $pre->product;
                        if ($product->stock >= $pre->quantity && $product->stock > 0) {
                            $product->stock = max(0, $product->stock - $pre->quantity);
                            $product->save();
                        }
                    }

                    return $pre;
                });

                if ($pre) {
                    $orders[] = $pre;
                }
            }

            // Clear cart and checkout session
            session()->forget('cart');
            session()->forget('stripe_checkout');

            return view('cart.thankyou', ['orders' => $orders, 'currency' => $currency]);
        } catch (ApiErrorException $e) {
            return redirect()->route('cart.show')->withErrors(['payment' => 'Error verifying payment: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle cancelled payment
     */
    public function cancel()
    {
        session()->forget('stripe_checkout');
        return redirect()->route('cart.show')->with('error', 'Payment was cancelled');
    }

    /**
     * Get currency configuration
     */
    private function getCurrencyConfig(string $currency): array
    {
        $currencies = [
            'MYR' => ['rate' => 1, 'longSleeve' => 3, 'nameset' => 13],
            'BND' => ['rate' => 1.05, 'longSleeve' => 3, 'nameset' => 13],
            'IDR' => ['rate' => 5200, 'longSleeve' => 15600, 'nameset' => 67600],
        ];

        return $currencies[$currency] ?? $currencies['MYR'];
    }

    /**
     * Get reserved quantity for a product
     */
    private function getReservedQty(Product $product): int
    {
        return (int) Preorder::where('product_id', $product->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('quantity');
    }

    /**
     * Generate order number for product
     */
    private function generateOrderNumberForProduct(Product $product): string
    {
        $prefix = $product->available_for_preorder ? 'MM-PO-' : 'MM-OR-';
        do {
            $candidate = $prefix . strtoupper(\Illuminate\Support\Str::random(8));
        } while (Preorder::where('order_number', $candidate)->exists());
        return $candidate;
    }

    /**
     * Convert amount to cents (Stripe uses smallest currency unit)
     */
    private function convertToCents(float $amount, string $currency): int
    {
        // Stripe supports MYR, BND, and IDR
        // MYR and BND use 2 decimal places (multiply by 100)
        // IDR doesn't use decimal places (already in smallest unit)
        if ($currency === 'IDR') {
            return (int) round($amount);
        }
        return (int) round($amount * 100);
    }

    /**
     * Build product description for Stripe
     */
    private function buildProductDescription(Product $product, array $item): string
    {
        $desc = $product->name;
        if ($product->jersey_type) {
            $desc .= ' - ' . $product->jersey_type;
        }
        if (!empty($item['size'])) {
            $desc .= ', Size: ' . $item['size'];
        }
        if (!empty($item['long_sleeve'])) {
            $desc .= ', Long Sleeve';
        }
        return $desc;
    }
}
