<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\PreorderHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;
use App\Mail\PaymentSuccess;
use App\Jobs\SendEmailJob;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Refund;
use Illuminate\Support\Facades\Log;

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

        // Prevent double processing (Idempotency)
        $existingOrder = Preorder::where('stripe_session_id', $sessionId)->first();
        if ($existingOrder) {
            session()->forget('cart');
            session()->forget('stripe_checkout');
            $orders = Preorder::where('stripe_session_id', $sessionId)->get();
            return view('cart.thankyou', ['orders' => $orders, 'currency' => $existingOrder->currency]);
        }

        try {
            $checkoutSession = Session::retrieve($sessionId);
            
            if ($checkoutSession->payment_status !== 'paid') {
                return redirect()->route('cart.show')->withErrors(['payment' => 'Payment not completed']);
            }

            $checkoutData = session()->get('stripe_checkout');
            if (!$checkoutData || $checkoutData['session_id'] !== $sessionId) {
                // If session lost but payment successful, auto-refund
                if (isset($checkoutSession->payment_intent)) {
                    $this->refundStripePayment($checkoutSession->payment_intent);
                }
                return redirect()->route('cart.show')->withErrors(['payment' => 'Session expired or invalid. Payment refunded automatically.']);
            }

            $currency = $checkoutData['currency'];
            $orders = [];
            $paymentIntentId = $checkoutSession->payment_intent ?? null;
            
            DB::beginTransaction();
            
            try {
                foreach ($checkoutData['order_items'] as $orderItem) {
                    $product = Product::where('id', $orderItem['product']->id)->lockForUpdate()->first();
                    $it = $orderItem['item'];
                    $unit = $orderItem['unit'];
                    $lineTotal = $orderItem['line_total'];

                    if (!$product || (!$product->is_active && !$product->available_for_preorder)) {
                        throw new \Exception("Product " . ($product->name ?? 'Unknown') . " not available");
                    }

                    if (!$product->available_for_preorder) {
                        $reserved = $this->getReservedQty($product);
                        $free = (int) $product->stock - $reserved;
                        if ($free < (int) $it['quantity']) {
                            throw new \Exception("Insufficient stock for " . $product->name);
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
                        'status' => 'paid',
                        'notes' => $checkoutData['order_data']['notes'] ?? null,
                        'stripe_payment_intent_id' => $paymentIntentId,
                        'stripe_session_id' => $sessionId,
                    ]);

                    PreorderHistory::create([
                        'preorder_id' => $pre->id,
                        'old_status' => null,
                        'new_status' => 'paid',
                        'note' => 'Order via Stripe payment - automatically paid (Session: ' . $sessionId . ')',
                    ]);

                    if ($pre->product && !$product->available_for_preorder) {
                        $product->stock = max(0, $product->stock - $pre->quantity);
                        $product->save();
                    }
                    
                    $orders[] = $pre;
                }
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->refundStripePayment($paymentIntentId);
                Log::error('Order creation failed after payment: ' . $e->getMessage());
                return redirect()->route('cart.show')->withErrors(['payment' => 'Order creation failed. Payment refunded automatically. Error: ' . $e->getMessage()]);
            }

            // Success flow
            foreach ($orders as $pre) {
                if ($pre->email) {
                    SendEmailJob::dispatch($pre->email, new OrderCreated($pre), 2);
                    SendEmailJob::dispatch($pre->email, new PaymentSuccess($pre), 5);
                }
            }

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
     * Create Stripe checkout session for single preorder item (from preorder form)
     */
    public function createPreorderCheckoutSession(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'size' => 'nullable|string|max:10',
            'long_sleeve' => 'sometimes|boolean',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.key' => 'required_with:custom_fields|string',
            'custom_fields.*.value' => 'required_with:custom_fields|string',
            'quantity' => 'required|integer|min:1',
            'currency' => 'nullable|string|in:MYR,BND,IDR',
            'notes' => 'nullable|string',
        ]);

        $product = DB::transaction(function () use ($data) {
            $p = Product::where('id', $data['product_id'])->lockForUpdate()->first();
            if (!$p || (!$p->is_active && !$p->available_for_preorder)) {
                throw new \RuntimeException('Product not available');
            }
            if (!$p->available_for_preorder) {
                $reserved = $this->getReservedQty($p);
                $free = (int) $p->stock - $reserved;
                if ($free < (int) $data['quantity']) {
                    throw new \RuntimeException('Not enough stock available.');
                }
            }
            return $p;
        });

        $currency = $data['currency'] ?? 'MYR';
        $config = $this->getCurrencyConfig($currency);

        $unit = (float) $product->price * $config['rate'];
        $longSleeve = $request->boolean('long_sleeve');

        if ($longSleeve) {
            $unit += $config['longSleeve'];
        }

        // Add nameset cost if custom fields are provided
        $hasCustomization = !empty($data['custom_fields']);
        if ($hasCustomization) {
            $unit += $config['nameset'];
        }

        $quantity = (int) ($data['quantity'] ?? 1);
        $total = round($unit * $quantity, 2);

        // Convert to cents for Stripe
        $amountInCents = $this->convertToCents($total, $currency);

        // Build product description
        $description = $product->name;
        if ($product->jersey_type) {
            $description .= ' - ' . $product->jersey_type;
        }
        if (!empty($data['size'])) {
            $description .= ', Size: ' . $data['size'];
        }
        if ($request->boolean('long_sleeve')) {
            $description .= ', Long Sleeve';
        }
        if ($hasCustomization) {
            $customText = [];
            foreach ($data['custom_fields'] as $field) {
                $customText[] = $field['key'] . ': ' . $field['value'];
            }
            $description .= ', ' . implode(', ', $customText);
        }

        try {
            // Create Stripe checkout session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => [
                            'name' => $product->name,
                            'description' => $description,
                        ],
                        'unit_amount' => $amountInCents,
                    ],
                    'quantity' => $quantity,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.preorder.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.preorder.cancel'),
                'customer_email' => $data['email'] ?? null,
                'metadata' => [
                    'name' => $data['name'],
                    'phone' => $data['phone'] ?? '',
                    'address' => $data['address'] ?? '',
                    'currency' => $currency,
                    'notes' => $data['notes'] ?? '',
                    'product_id' => $product->id,
                    'size' => $data['size'] ?? '',
                    'long_sleeve' => $request->boolean('long_sleeve') ? '1' : '0',
                    'quantity' => $quantity,
                ],
            ]);

            // Store checkout session data in session for later use
            // Ensure long_sleeve is properly stored as boolean
            $orderData = $data;
            $orderData['long_sleeve'] = $longSleeve;
            
            session()->put('stripe_preorder_checkout', [
                'session_id' => $checkoutSession->id,
                'order_data' => $orderData,
                'product_id' => $product->id,
                'unit_price' => $unit,
                'total_amount' => $total,
                'currency' => $currency,
            ]);

            return redirect($checkoutSession->url);
        } catch (ApiErrorException $e) {
            return back()->withErrors(['stripe' => 'Error creating payment session: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Handle successful payment for single preorder
     */
    public function preorderSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            return redirect()->route('preorder.landing')->withErrors(['payment' => 'Invalid payment session']);
        }

        // Idempotency Check
        $existingOrder = Preorder::where('stripe_session_id', $sessionId)->first();
        if ($existingOrder) {
            session()->forget('stripe_preorder_checkout');
            $redirect = $existingOrder->product->available_for_preorder ? 'preorder.thankyou' : 'order.thankyou';
            return redirect()->route($redirect, ['uuid' => $existingOrder->uuid]);
        }

        try {
            $checkoutSession = Session::retrieve($sessionId);
            
            if ($checkoutSession->payment_status !== 'paid') {
                return redirect()->route('preorder.landing')->withErrors(['payment' => 'Payment not completed']);
            }

            $checkoutData = session()->get('stripe_preorder_checkout');
            if (!$checkoutData || $checkoutData['session_id'] !== $sessionId) {
                // Auto refund if session lost
                if (isset($checkoutSession->payment_intent)) {
                    $this->refundStripePayment($checkoutSession->payment_intent);
                }
                return redirect()->route('preorder.landing')->withErrors(['payment' => 'Session expired or invalid. Payment refunded automatically.']);
            }

            $product = Product::find($checkoutData['product_id']);
            if (!$product) {
                // Refund
                if (isset($checkoutSession->payment_intent)) {
                    $this->refundStripePayment($checkoutSession->payment_intent);
                }
                return redirect()->route('preorder.landing')->withErrors(['payment' => 'Product not found. Payment refunded.']);
            }

            $paymentIntentId = $checkoutSession->payment_intent ?? null;
            $orderData = $checkoutData['order_data'];
            $currency = $checkoutData['currency'];
            $unit = $checkoutData['unit_price'];
            $total = $checkoutData['total_amount'];
            $pre = null;

            DB::beginTransaction();

            try {
                $product = Product::where('id', $product->id)->lockForUpdate()->first();
                if (!$product || (!$product->is_active && !$product->available_for_preorder)) {
                    throw new \Exception('Product not available');
                }

                if (!$product->available_for_preorder) {
                    $reserved = $this->getReservedQty($product);
                    $free = (int) $product->stock - $reserved;
                    if ($free < (int) $orderData['quantity']) {
                        throw new \Exception('Not enough stock available');
                    }
                }

                $orderNumber = $this->generateOrderNumberForProduct($product);
                $pre = Preorder::create([
                    'order_number' => $orderNumber,
                    'product_id' => $product->id,
                    'name' => $orderData['name'],
                    'email' => $orderData['email'] ?? null,
                    'phone' => $orderData['phone'] ?? null,
                    'address' => $orderData['address'] ?? null,
                    'jersey_type' => $product->jersey_type ?? null,
                    'size' => $orderData['size'] ?? null,
                    'long_sleeve' => !empty($orderData['long_sleeve']),
                    'custom_fields' => $orderData['custom_fields'] ?? null,
                    'quantity' => (int) $orderData['quantity'],
                    'unit_price' => $unit,
                    'total_amount' => $total,
                    'currency' => $currency,
                    'status' => 'paid',
                    'notes' => $orderData['notes'] ?? null,
                    'stripe_payment_intent_id' => $paymentIntentId,
                    'stripe_session_id' => $sessionId,
                ]);

                PreorderHistory::create([
                    'preorder_id' => $pre->id,
                    'old_status' => null,
                    'new_status' => 'paid',
                    'note' => 'Order via Stripe payment - automatically paid (Session: ' . $sessionId . ')',
                ]);

                if ($pre->product && !$product->available_for_preorder) {
                    $product->stock = max(0, $product->stock - $pre->quantity);
                    $product->save();
                }
                
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->refundStripePayment($paymentIntentId);
                Log::error('Preorder creation failed after payment: ' . $e->getMessage());
                return redirect()->route('preorder.landing')->withErrors(['payment' => 'Order creation failed. Payment refunded. Error: ' . $e->getMessage()]);
            }

            if ($pre && $pre->email) {
                SendEmailJob::dispatch($pre->email, new OrderCreated($pre), 2);
                SendEmailJob::dispatch($pre->email, new PaymentSuccess($pre), 5);
            }

            session()->forget('stripe_preorder_checkout');

            $redirect = $product->available_for_preorder ? 'preorder.thankyou' : 'order.thankyou';
            return redirect()->route($redirect, ['uuid' => $pre->uuid]);

        } catch (ApiErrorException $e) {
            return redirect()->route('preorder.landing')->withErrors(['payment' => 'Error verifying payment: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle cancelled payment for single preorder
     */
    public function preorderCancel()
    {
        session()->forget('stripe_preorder_checkout');
        return redirect()->route('preorder.landing')->with('error', 'Payment was cancelled');
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

    /**
     * Refund Stripe payment
     */
    private function refundStripePayment(?string $paymentIntentId)
    {
        if ($paymentIntentId) {
            try {
                Refund::create(['payment_intent' => $paymentIntentId]);
                Log::info("Auto-refunded payment $paymentIntentId due to order creation failure.");
            } catch (\Exception $e) {
                Log::error("Failed to auto-refund payment $paymentIntentId: " . $e->getMessage());
            }
        }
    }
}
