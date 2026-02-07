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
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Notifications\NewPreorderNotification;
use App\Services\EasyParcelService;
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
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'address_detail' => 'required|string',
            'currency' => 'nullable|string|in:MYR,BND,IDR',
            'notes' => 'nullable|string',
        ]);
        $fullAddress = trim(implode(', ', array_filter([
            $data['address_detail'] ?? null,
            $data['city'] ?? null,
            $data['province'] ?? null,
            'Postal ' . ($data['postal_code'] ?? ''),
            $data['region'] ?? null,
        ])));

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
                    'address' => $fullAddress,
                    'currency' => $currency,
                    'notes' => $data['notes'] ?? '',
                    'region' => $data['region'],
                    'province' => $data['province'],
                    'city' => $data['city'],
                    'postal_code' => $data['postal_code'],
                ],
            ]);

            // Store checkout session data in session for later use
            session()->put('stripe_checkout', [
                'session_id' => $checkoutSession->id,
                'order_data' => array_merge($data, ['address' => $fullAddress]),
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
                    $it = $orderItem['item'];
                    $product = Product::where('id', $orderItem['product']->id)->lockForUpdate()->first();
                    $unit = $orderItem['unit'];
                    $lineTotal = $orderItem['line_total'];

                    if (!$product || (!$product->is_active && !$product->available_for_preorder)) {
                        throw new \Exception("Product " . ($product->name ?? 'Unknown') . " not available");
                    }

                    $variant = null;
                    if (!empty($it['product_variant_id'])) {
                        $variant = \App\Models\ProductVariant::lockForUpdate()->find($it['product_variant_id']);
                    }

                    if (!$product->available_for_preorder) {
                        if ($variant) {
                            if ($variant->stock < (int) $it['quantity']) {
                                throw new \Exception("Insufficient stock for variant " . $variant->name);
                            }
                        } else {
                            $reserved = $this->getReservedQty($product);
                            $free = (int) $product->stock - $reserved;
                            if ($free < (int) $it['quantity']) {
                                throw new \Exception("Insufficient stock for " . $product->name);
                            }
                        }
                    }

                    $orderNumber = $this->generateOrderNumberForProduct($product);
                    $pre = Preorder::create([
                        'order_number' => $orderNumber,
                        'product_id' => $product->id,
                        'product_variant_id' => $variant ? $variant->id : null,
                        'name' => $checkoutData['order_data']['name'],
                        'email' => $checkoutData['order_data']['email'] ?? null,
                        'phone' => $checkoutData['order_data']['phone'] ?? null,
                        'address' => $checkoutData['order_data']['address'] ?? null,
                        'jersey_type' => $product->jersey_type ?? null,
                        'size' => $it['size'] ?? ($variant ? $variant->name : null),
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
                        if ($variant) {
                            $variant->stock = max(0, $variant->stock - $pre->quantity);
                            $variant->save();
                        } else {
                            $product->stock = max(0, $product->stock - $pre->quantity);
                            $product->save();
                        }
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

                // Notify admins (DB notifications only, no email)
                $admins = User::whereIn('role', ['admin', 'staff'])->get();
                if ($admins->isNotEmpty()) {
                    if (str_starts_with($pre->order_number, 'MM-PO-')) {
                        Notification::send($admins, new NewPreorderNotification($pre));
                    } else {
                        Notification::send($admins, new NewOrderNotification($pre));
                    }

                    // Store DB notification for buyer if registered
                    if (!empty($pre->email)) {
                        $buyer = User::where('email', $pre->email)->first();
                        if ($buyer) {
                            if (str_starts_with($pre->order_number, 'MM-PO-')) {
                                $buyer->notify(new NewPreorderNotification($pre));
                            } else {
                                $buyer->notify(new NewOrderNotification($pre));
                            }
                        }
                    }
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
    /**
     * Create Stripe checkout session for single preorder item (from preorder form)
     */
    public function createPreorderCheckoutSession(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'items' => 'required|array',
            'items.*.quantity_ss' => 'nullable|integer|min:0',
            'items.*.quantity_ls' => 'nullable|integer|min:0',
            'items.*.namesets_ss' => 'nullable|array',
            'items.*.namesets_ss.*.key' => 'required_with:items.*.namesets_ss|string',
            'items.*.namesets_ss.*.value' => 'required_with:items.*.namesets_ss|string',
            'items.*.namesets_ls' => 'nullable|array',
            'items.*.namesets_ls.*.key' => 'required_with:items.*.namesets_ls|string',
            'items.*.namesets_ls.*.value' => 'required_with:items.*.namesets_ls|string',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'address_detail' => 'required|string',
            'currency' => 'nullable|string|in:MYR,BND,IDR,SGD',
            'notes' => 'nullable|string',
            'shipping_courier_name' => 'required|string|max:255',
            'shipping_courier_logo' => 'nullable|string|max:255',
            'shipping_service_name' => 'required|string|max:255',
            'shipping_service_id' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
        ]);
        $fullAddress = trim(implode(', ', array_filter([
            $data['address_detail'] ?? null,
            $data['city'] ?? null,
            $data['province'] ?? null,
            'Postal ' . ($data['postal_code'] ?? ''),
            $data['region'] ?? null,
        ])));
        $data['address'] = $fullAddress;

        $product = Product::findOrFail($data['product_id']);
        if (!$product->is_active && !$product->available_for_preorder) {
            abort(404);
        }

        // Filter valid items
        $itemsData = array_filter($data['items'] ?? [], fn($item) => ($item['quantity_ss'] ?? 0) > 0 || ($item['quantity_ls'] ?? 0) > 0);

        if (empty($itemsData)) {
            return back()->withErrors(['items' => 'Please select at least one item quantity.'])->withInput();
        }

        $currency = $data['currency'] ?? 'MYR';
        $config = $this->getCurrencyConfig($currency);

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

            // Pre-check stock availability for all types in this variant
            $variantCurrentUsage = $types['ss']['qty'] + $types['ls']['qty'];
            if (!$product->available_for_preorder && $variantCurrentUsage > 0) {
                if ($variant->stock < $variantCurrentUsage) {
                    return back()->withErrors(['items' => "Not enough stock for {$variant->name}."])->withInput();
                }
            }

            foreach ($types as $typeKey => $typeData) {
                $qty = $typeData['qty'];
                if ($qty <= 0)
                    continue;

                $isLongSleeve = $typeData['ls'];

                // Base + Surcharge
                $unit = (float) $product->price * $config['rate'];
                if ($isLongSleeve) {
                    $unit += $config['longSleeve'];
                }

                $lineTotal = round($unit * $qty, 2);
                $totalAmount += $lineTotal;

                // Process Namesets
                if (!empty($typeData['namesets'])) {
                    foreach ($typeData['namesets'] as $ns) {
                        if (!empty($ns['key']) || !empty($ns['value'])) {
                            $allCustomFields[] = $ns;
                            $namesetCountTotal++;
                        }
                    }
                }

                // Stripe Line Item for Product
                $amountInCents = $this->convertToCents($unit, $currency);

                $desc = $product->name . ' (' . $variant->name . ')';
                $desc .= $isLongSleeve ? ' - Long Sleeve' : ' - Short Sleeve';

                $lineItems[] = [
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => [
                            'name' => $product->name . ' (' . $variant->name . ')',
                            'description' => $isLongSleeve ? 'Type: Long Sleeve' : 'Type: Short Sleeve',
                        ],
                        'unit_amount' => $amountInCents,
                    ],
                    'quantity' => $qty,
                ];

                // Item for Session Storage
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

        // Add Nameset Line Item
        if ($namesetCountTotal > 0) {
            $namesetPrice = $config['nameset'];
            $namesetTotal = round($namesetPrice * $namesetCountTotal, 2);
            $totalAmount += $namesetTotal;

            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => 'Jersey Customization (Nameset)',
                        'description' => 'Custom Name & Number x ' . $namesetCountTotal,
                    ],
                    'unit_amount' => $this->convertToCents($namesetPrice, $currency),
                ],
                'quantity' => $namesetCountTotal,
            ];
        }

        // Add Shipping Cost
        $shippingCost = (float) ($data['shipping_cost'] ?? 0);
        $convertedShippingCost = $shippingCost * $config['rate'];
        $totalAmount += $convertedShippingCost;

        if ($convertedShippingCost > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => 'Shipping Fee',
                        'description' => ($data['shipping_courier_name'] ?? 'Courier') . ' - ' . ($data['shipping_service_name'] ?? 'Service'),
                    ],
                    'unit_amount' => $this->convertToCents($convertedShippingCost, $currency),
                ],
                'quantity' => 1,
            ];
        }

        // Update data with flattened custom_fields for session storage compatibility
        $data['custom_fields'] = $allCustomFields;

        try {
            // Create Stripe checkout session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.preorder.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.preorder.cancel'),
                'customer_email' => $data['email'] ?? null,
                'metadata' => [
                    'name' => $data['name'],
                    'phone' => $data['phone'] ?? '',
                    'currency' => $currency,
                    'product_id' => $product->id,
                    'item_count' => count($orderItems),
                ],
            ]);

            // Store checkout session data
            session()->put('stripe_preorder_checkout', [
                'session_id' => $checkoutSession->id,
                'order_data' => $data, // Contains full data including custom_fields
                'order_items' => $orderItems, // Processed items
                'product_id' => $product->id,
                'currency' => $currency,
                'total_amount' => $totalAmount,
                'shipping_data' => [
                    'shipping_courier_name' => $data['shipping_courier_name'],
                    'shipping_courier_logo' => $data['shipping_courier_logo'],
                    'shipping_service_name' => $data['shipping_service_name'],
                    'shipping_service_id' => $data['shipping_service_id'],
                    'shipping_cost' => $convertedShippingCost,
                ],
            ]);

            return redirect($checkoutSession->url);
        } catch (ApiErrorException $e) {
            return back()->withErrors(['stripe' => 'Error creating payment session: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Handle successful payment for single preorder
     */
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

            // Generate UUID
            $uuid = (string) \Illuminate\Support\Str::uuid();
            $order = null;

            DB::beginTransaction();

            try {
                $product = Product::where('id', $product->id)->lockForUpdate()->first();
                if (!$product || (!$product->is_active && !$product->available_for_preorder)) {
                    throw new \Exception('Product not available');
                }

                // Prepare Items & Stock Deduction
                $totalQty = 0;
                $finalItems = [];
                // order_items from session is: [['variant_id', 'variant_name', 'quantity', 'unit_price', 'line_total'], ...]

                foreach ($checkoutData['order_items'] as $item) {
                    $variantId = $item['variant_id'];
                    $qty = (int) $item['quantity'];
                    $totalQty += $qty;

                    $variant = \App\Models\ProductVariant::lockForUpdate()->find($variantId);

                    // Stock Check
                    if (!$product->available_for_preorder) {
                        if ($variant && $variant->stock < $qty) {
                            throw new \Exception("Stock no longer available for {$variant->name}");
                        }
                    }

                    // Deduct Stock
                    if (!$product->available_for_preorder && $variant) {
                        $variant->stock = max(0, $variant->stock - $qty);
                        $variant->save();
                    }

                    $finalItems[] = $item;
                }

                // Identify primary variant for legacy fields
                $firstItem = $checkoutData['order_items'][0] ?? null;
                $firstVariantId = $firstItem['variant_id'] ?? null;
                $firstVariantName = $firstItem['variant_name'] ?? null;

                $orderNumber = $this->generateOrderNumberForProduct($product);
                $totalAmount = $checkoutData['total_amount']; // Includes nameset and shipping

                // Retrieve shipping data
                $shippingData = $checkoutData['shipping_data'] ?? [];
                $shippingCost = $shippingData['shipping_cost'] ?? 0;
                
                // Calculate unit price average before adding shipping
                $amountExcludingShipping = $totalAmount - $shippingCost;
                $unitPriceAvg = $totalQty > 0 ? ($amountExcludingShipping / $totalQty) : 0;

                $order = Preorder::create([
                    'uuid' => $uuid,
                    'order_number' => $orderNumber,
                    'product_id' => $product->id,
                    'product_variant_id' => $firstVariantId,
                    'name' => $orderData['name'],
                    'email' => $orderData['email'] ?? null,
                    'phone' => $orderData['phone'] ?? null,
                    'address' => $orderData['address'] ?? null,
                    'jersey_type' => $product->jersey_type ?? null,
                    'size' => $firstVariantName,
                    'long_sleeve' => !empty($orderData['long_sleeve']),
                    'custom_fields' => $orderData['custom_fields'] ?? null,
                    'quantity' => $totalQty,
                    'unit_price' => $unitPriceAvg,
                    'total_amount' => $totalAmount,
                    'currency' => $currency,
                    'status' => 'paid',
                    'notes' => $orderData['notes'] ?? null,
                    'items' => $finalItems, // JSON
                    'stripe_payment_intent_id' => $paymentIntentId,
                    'stripe_session_id' => $sessionId,
                    'shipping_courier_name' => $shippingData['shipping_courier_name'] ?? null,
                    'shipping_courier_logo' => $shippingData['shipping_courier_logo'] ?? null,
                    'shipping_service_name' => $shippingData['shipping_service_name'] ?? null,
                    'shipping_service_id' => $shippingData['shipping_service_id'] ?? null,
                    'shipping_cost' => $shippingCost,
                ]);

                PreorderHistory::create([
                    'preorder_id' => $order->id,
                    'old_status' => null,
                    'new_status' => 'paid',
                    'note' => 'Order via Stripe payment - automatically paid (Session: ' . $sessionId . ')',
                ]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->refundStripePayment($paymentIntentId);
                Log::error('Preorder creation failed after payment: ' . $e->getMessage());
                return redirect()->route('preorder.landing')->withErrors(['payment' => 'Order creation failed. Payment refunded. Error: ' . $e->getMessage()]);
            }

            try {
                if ($order && !empty($order->shipping_service_id)) {
                    $weight = max(1, $order->quantity * 0.5);
                    $sendCode = $orderData['postal_code'] ?? null;
                    $sendState = $orderData['province'] ?? null;
                    $sendCity = $orderData['city'] ?? null;
                    $sendCountry = 'MY';
                    $isDelyva = !empty($order->shipping_courier_name) && stripos($order->shipping_courier_name, 'delyva') !== false;
                    if ($isDelyva) {
                        $delyva = new \App\Services\DelyvaService();
                        $origin = [
                            'name' => config('app.name'),
                            'address1' => 'Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens',
                            'postcode' => '88000',
                            'state' => 'Sabah',
                            'city' => 'Kota Kinabalu',
                            'country' => 'MY',
                            'phone' => $orderData['phone'] ?? '',
                            'email' => null,
                        ];
                        $destination = [
                            'name' => $order->name,
                            'address1' => $orderData['address_detail'] ?? ($order->address ?? ''),
                            'postcode' => $sendCode,
                            'state' => $sendState,
                            'city' => $sendCity,
                            'country' => $sendCountry,
                            'phone' => $order->phone ?? '',
                            'email' => $order->email ?? null,
                        ];
                        $items = [
                            [
                                'name' => 'Jersey',
                                'quantity' => $order->quantity,
                                'weight' => ['unit' => 'kg', 'value' => $weight],
                            ]
                        ];
                        $meta = [
                            'reference' => $order->order_number,
                            'cod' => ['amount' => 0, 'currency' => $order->currency],
                            'price' => ['amount' => $order->shipping_cost ?? 0, 'currency' => $order->currency],
                        ];
                        $created = $delyva->createOrder($origin, $destination, $items, $meta);
                        $orderId = $created['data']['id'] ?? null;
                        if ($orderId) {
                            $serviceCode = $order->shipping_service_id;
                            $delyva->processOrder($orderId, $serviceCode);
                            $details = $delyva->getOrder($orderId);
                            $consignmentNo = $details['data']['consignmentNo'] ?? null;
                            if ($consignmentNo) {
                                $order->tracking_number = $consignmentNo;
                                $order->shipping_status = 'shipped';
                                $order->save();
                                PreorderHistory::create([
                                    'preorder_id' => $order->id,
                                    'old_status' => $order->status,
                                    'new_status' => $order->status,
                                    'note' => 'Booked via Delyva. Consignment: ' . $consignmentNo,
                                ]);
                            }
                        }
                    } else {
                        $easyParcel = new EasyParcelService();
                        $orderPayload = [
                            'weight' => $weight,
                            'content' => 'Jersey',
                            'value' => $order->total_amount,
                            'service_id' => $order->shipping_service_id,
                            'order_number' => $order->order_number,
                            'pick_name' => config('app.name'),
                            'pick_contact' => $orderData['phone'] ?? '',
                            'pick_mobile' => $orderData['phone'] ?? '',
                            'pick_addr1' => 'Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens',
                            'pick_code' => '88000',
                            'pick_state' => 'Sabah',
                            'pick_province' => 'Sabah',
                            'pick_country' => 'MY',
                            'send_name' => $order->name,
                            'send_contact' => $order->phone ?? '',
                            'send_mobile' => $order->phone ?? '',
                            'send_addr1' => $orderData['address_detail'] ?? ($order->address ?? ''),
                            'send_code' => $sendCode,
                            'send_state' => $sendState,
                            'send_province' => $sendState,
                            'send_country' => $sendCountry,
                            'send_email' => $order->email,
                        ];
                        if (($order->shipping_service_id ?? '') !== 'ep_flat') {
                            $shipping = new \App\Services\ShippingService();
                            $res = $shipping->bookShipment($order, $orderPayload);
                            if ($res['success'] && !empty($res['awb'])) {
                                $order->tracking_number = $res['awb'];
                                $order->shipping_status = 'shipped';
                                $order->save();
                                PreorderHistory::create([
                                    'preorder_id' => $order->id,
                                    'old_status' => $order->status,
                                    'new_status' => $order->status,
                                    'note' => 'Booked via EasyParcel. AWB: ' . $res['awb'],
                                ]);
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Auto-booking error: ' . $e->getMessage());
            }

            // Send Email
            if ($order && $order->email) {
                SendEmailJob::dispatch($order->email, new OrderCreated($order), 2);
                SendEmailJob::dispatch($order->email, new PaymentSuccess($order), 5);
            }

            session()->forget('stripe_preorder_checkout');

            $redirect = $product->available_for_preorder ? 'preorder.thankyou' : 'order.thankyou';
            return redirect()->route($redirect, ['uuid' => $uuid]);

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
