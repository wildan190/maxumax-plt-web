<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Preorder;
use App\Models\PreorderHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;
use App\Mail\RefundRequested;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Notifications\NewPreorderNotification;

class PreorderController extends Controller
{
    /**
     * Get currency configuration for pricing.
     */
    private function getCurrencyConfig(string $currency): array
    {
        $currencies = [
            'MYR' => ['rate' => 1, 'longSleeve' => 10, 'nameset' => 35],
            'BND' => ['rate' => 1.05, 'longSleeve' => 3, 'nameset' => 13],
            'SGD' => ['rate' => 1.05, 'longSleeve' => 3, 'nameset' => 13],
            'IDR' => ['rate' => 5200, 'longSleeve' => 15600, 'nameset' => 67600],
        ];

        return $currencies[$currency] ?? $currencies['MYR'];
    }

    private function resolveCurrency(Request $request): string
    {
        if (session('currency_manual', false)) {
            return session('currency', 'MYR');
        }

        if (session()->has('currency')) {
            return session('currency');
        }

        try {
            $ip = $request->ip();
            $ctx = stream_context_create(['http' => ['timeout' => 2]]);
            $json = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode", false, $ctx);

            if ($json) {
                $data = json_decode($json, true);
                $country = $data['countryCode'] ?? null;
                $currency = match ($country) {
                    'ID' => 'IDR',
                    'BN' => 'BND',
                    'SG' => 'SGD',
                    default => 'MYR',
                };
                session(['currency' => $currency]);
                return $currency;
            }
        } catch (\Throwable $e) {
            // Fallback
        }

        session(['currency' => 'MYR']);
        return 'MYR';
    }

    private function getReservedQty(Product $product): int
    {
        return (int) Preorder::where('product_id', $product->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('quantity');
    }

    /**
     * Show the preorder landing page with preorder products only.
     */
    public function showLanding(Request $request)
    {
        $products = Product::where('available_for_preorder', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $highlightedGallery = \App\Models\Gallery::where('is_highlight', true)->latest()->take(6)->get();

        $currency = $this->resolveCurrency($request);
        $currencyConfig = $this->getCurrencyConfig($currency);

        return view('preorder.landing', compact('products', 'highlightedGallery', 'currency', 'currencyConfig'));
    }

    /**
     * Show the preorder creation form for a specific product.
     */
    public function create(Request $request, Product $product)
    {
        if (!($product->is_active || $product->available_for_preorder)) {
            abort(404);
        }

        $product->load('variants');
        $currency = $this->resolveCurrency($request);
        $currencyConfig = $this->getCurrencyConfig($currency);

        return view('preorder.create', ['product' => $product, 'currency' => $currency, 'currencyConfig' => $currencyConfig]);
    }

    /**
     * Store a new preorder.
     */
    public function store(Request $request)
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
            'currency' => 'nullable|string|in:MYR,SGD,IDR,BND',
            'notes' => 'nullable|string',
        ]);
        $fullAddress = trim(implode(', ', array_filter([
            $data['address_detail'] ?? null,
            $data['city'] ?? null,
            $data['province'] ?? null,
            'Postal ' . ($data['postal_code'] ?? ''),
            $data['region'] ?? null,
        ])));

        $product = Product::findOrFail($data['product_id']);
        if (!$product->is_active && !$product->available_for_preorder) {
            abort(404);
        }

        // Filter items with at least one qty > 0
        $itemsData = array_filter($data['items'] ?? [], fn($item) => ($item['quantity_ss'] ?? 0) > 0 || ($item['quantity_ls'] ?? 0) > 0);

        if (empty($itemsData)) {
            return back()->withErrors(['items' => 'Please select at least one item quantity.'])->withInput();
        }

        $currency = $data['currency'] ?? $this->resolveCurrency($request);
        $config = $this->getCurrencyConfig($currency);
        $uuid = (string) \Illuminate\Support\Str::uuid();

        // Transaction for Stock Check & Order Creation
        $orders = DB::transaction(function () use ($data, $itemsData, $product, $config, $currency, $uuid, $fullAddress) {
            $totalQty = 0;
            $totalAmount = 0;
            $orderItems = [];
            $firstVariantId = null;
            $firstVariantName = null;
            $hasLongSleeveGlobal = false;
            $allCustomFields = [];

            foreach ($itemsData as $variantId => $itemData) {
                // Handle SS and LS separately
                $types = [
                    'ss' => ['qty' => (int) ($itemData['quantity_ss'] ?? 0), 'ls' => false, 'namesets' => $itemData['namesets_ss'] ?? []],
                    'ls' => ['qty' => (int) ($itemData['quantity_ls'] ?? 0), 'ls' => true, 'namesets' => $itemData['namesets_ls'] ?? []]
                ];

                $variantTotalQty = $types['ss']['qty'] + $types['ls']['qty'];

                // Stock Check
                if (!$product->available_for_preorder) {
                    $variant = \App\Models\ProductVariant::lockForUpdate()->find($variantId);
                    if ($variant && $variant->product_id == $product->id) {
                        if ($variant->stock < $variantTotalQty) {
                            throw new \RuntimeException("Not enough stock for variant {$variant->name}");
                        }
                        $variant->stock -= $variantTotalQty;
                        $variant->save();
                    }
                } else {
                    $variant = \App\Models\ProductVariant::find($variantId);
                }

                if (!$firstVariantId) {
                    $firstVariantId = $variantId;
                    $firstVariantName = $variant ? $variant->name : null;
                }

                foreach ($types as $typeKey => $typeData) {
                    $qty = $typeData['qty'];
                    if ($qty <= 0)
                        continue;

                    $isLongSleeve = $typeData['ls'];
                    if ($isLongSleeve)
                        $hasLongSleeveGlobal = true;

                    // Base Price
                    $unitBase = (float) $product->price * $config['rate'];

                    // Surcharge
                    $unitSurcharge = 0;
                    if ($isLongSleeve) {
                        $unitSurcharge += $config['longSleeve'];
                    }

                    // Namesets
                    // Filter empty namesets
                    $validNamesets = [];
                    if (!empty($typeData['namesets'])) {
                        foreach ($typeData['namesets'] as $ns) {
                            if (!empty($ns['key']) || !empty($ns['value'])) {
                                $validNamesets[] = $ns;
                                $allCustomFields[] = $ns; // Legacy flat tracking
                            }
                        }
                    }
                    $namesetCount = count($validNamesets);

                    // Logic: We charge per nameset entry found.
                    // If user enters 1 nameset for 2 jerseys, we charge 1 nameset.
                    // This is "per entered nameset" pricing.

                    $variantTotal = ($unitBase + $unitSurcharge) * $qty;
                    $variantTotal += ($namesetCount * $config['nameset']);

                    $totalQty += $qty;
                    $totalAmount += $variantTotal;

                    // Add suffix to separate SS/LS in UI later if needed?
                    // We store them as separate line items in JSON
                    $orderItems[] = [
                        'variant_id' => $variantId,
                        'variant_name' => ($variant ? $variant->name : 'Unknown') . ($isLongSleeve ? ' (Long Sleeve)' : ' (Short Sleeve)'),
                        'quantity' => $qty,
                        'long_sleeve' => $isLongSleeve,
                        'custom_fields' => $validNamesets,
                        'unit_price' => $unitBase,
                        'surcharges' => [
                            'long_sleeve' => $isLongSleeve ? ($config['longSleeve'] * $qty) : 0,
                            'nameset' => $namesetCount * $config['nameset']
                        ],
                        'total_price' => round($variantTotal, 2)
                    ];
                }
            }

            $orderNumber = $this->generateOrderNumberForProduct($product);

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
                'custom_fields' => !empty($allCustomFields) ? $allCustomFields : null,
                'quantity' => $totalQty,
                'unit_price' => $totalQty > 0 ? ($totalAmount / $totalQty) : 0,
                'total_amount' => $totalAmount,
                'currency' => $currency,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'items' => $orderItems,
            ]);

            PreorderHistory::create([
                'preorder_id' => $pre->id,
                'old_status' => null,
                'new_status' => 'pending',
                'note' => 'Order created via ' . ($product->available_for_preorder ? 'Preorder' : 'Ready Stock'),
            ]);

            return [$pre];
        });

        // Send Email to buyer
        if (!empty($orders) && $orders[0]->email) {
            SendEmailJob::dispatch($orders[0]->email, new OrderCreated($orders[0]), 2);
        }

        // Database notifications for admins
        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        if ($admins->isNotEmpty()) {
            // Database notifications for admins only (no email)
            if (str_starts_with($orders[0]->order_number, 'MM-PO-')) {
                Notification::send($admins, new NewPreorderNotification($orders[0]));
            } else {
                Notification::send($admins, new NewOrderNotification($orders[0]));
            }

            // Database notifications for buyer (if registered)
            if (!empty($orders[0]->email)) {
                $buyer = User::where('email', $orders[0]->email)->first();
                if ($buyer) {
                    if (str_starts_with($orders[0]->order_number, 'MM-PO-')) {
                        $buyer->notify(new NewPreorderNotification($orders[0]));
                    } else {
                        $buyer->notify(new NewOrderNotification($orders[0]));
                    }
                }
            }
        }

        $redirect = $product->available_for_preorder ? 'preorder.thankyou' : 'order.thankyou';
        return redirect()->route($redirect, ['uuid' => $orders[0]->uuid]);
    }

    /**
     * Show the thank you page after placing a preorder.
     */
    public function thankyou(Request $request, $uuid)
    {
        // Fetch all orders with this UUID
        $preorders = Preorder::with('product')->where('uuid', $uuid)->get();

        if ($preorders->isEmpty()) {
            // Fallback for old single-ID links if strictly UUID? 
            // If $uuid is integer, try find by ID?
            if (is_numeric($uuid)) {
                $pre = Preorder::find($uuid);
                if ($pre)
                    $preorders = collect([$pre]);
                else
                    abort(404);
            } else {
                abort(404);
            }
        }

        return view('preorder.thankyou', ['preorders' => $preorders]);
    }

    /**
     * Generate a unique, non-ID-based order number.
     */
    private function generateOrderNumberForProduct(Product $product): string
    {
        $prefix = $product->available_for_preorder ? 'MM-PO-' : 'MM-OR-';
        do {
            $code = $prefix . strtoupper(str()->random(8));
        } while (Preorder::where('order_number', $code)->exists());

        return $code;
    }

    /**
     * Track order by order number (no login required).
     */
    public function track(Request $request)
    {
        $order = $request->query('order');
        $pre = null;
        $error = null;
        if ($order) {
            $pre = Preorder::with(['product', 'histories', 'complaints'])->where('order_number', $order)->first();
            if (!$pre) {
                $error = 'Order tidak ditemukan';
            }
        }

        return view('order.track', [
            'orderInput' => $order,
            'preorder' => $pre,
            'error' => $error,
        ]);
    }

    /**
     * Show products listing page (only available products, not preorder).
     */
    public function showProducts(Request $request)
    {
        $products = Product::where('is_active', true)
            ->where('available_for_preorder', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $currency = $this->resolveCurrency($request);
        $currencyConfig = $this->getCurrencyConfig($currency);

        return view('products.index', compact('products', 'currency', 'currencyConfig'));
    }

    /**
     * Show public product detail with feedback and rating.
     * Only shows products that are available (not preorder).
     */
    public function showProduct(Request $request, Product $product)
    {
        if (!($product->is_active && !$product->available_for_preorder)) {
            abort(404);
        }

        $product->load('variants');

        $currency = $this->resolveCurrency($request);
        $avg = round((float) Feedback::where('product_id', $product->id)->avg('rating'), 2);
        $count = (int) Feedback::where('product_id', $product->id)->count();
        $latest = Feedback::where('product_id', $product->id)->orderByDesc('created_at')->limit(6)->get();

        $currencyConfig = $this->getCurrencyConfig($currency);

        return view('products.show', [
            'product' => $product,
            'feedbackAvg' => $avg,
            'feedbackCount' => $count,
            'latestFeedback' => $latest,
            'currency' => $currency,
            'currencyConfig' => $currencyConfig,
        ]);
    }

    public function setCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|in:MYR,BND,IDR,SGD',
        ]);

        session(['currency' => $request->currency, 'currency_manual' => true]);

        return response()->json(['success' => true, 'currency' => $request->currency]);
    }

    public function cartAdd(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'product_variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string|max:10',
            'long_sleeve' => 'sometimes|boolean',
        ]);
        $result = DB::transaction(function () use ($data, $request) {
            $product = Product::where('id', $data['product_id'])->lockForUpdate()->first();
            if (!$product || (!$product->is_active && !$product->available_for_preorder)) {
                return ['error' => ['product_id' => 'Product not available']];
            }

            // Check variant if provided
            $variant = null;
            if (!empty($data['product_variant_id'])) {
                $variant = \App\Models\ProductVariant::where('id', $data['product_variant_id'])
                    ->where('product_id', $product->id)
                    ->first();
                if (!$variant) {
                    return ['error' => ['product_variant_id' => 'Invalid variant']];
                }
            }

            if (!$product->available_for_preorder && empty($data['size']) && !$variant) {
                return ['error' => ['size' => 'Please select a size']];
            }

            $cart = session()->get('cart', []);

            // Generate unique key
            $key = (string) $product->id;
            if ($variant) {
                $key .= '-' . $variant->id;
            } elseif (!empty($data['size'])) {
                // Fallback for unique key by size if no variant ID (legacy support)
                $key .= '-' . preg_replace('/[^a-zA-Z0-9]/', '', $data['size']);
            }

            $existingQty = isset($cart[$key]) ? (int) $cart[$key]['quantity'] : 0;
            $requestedTotal = $existingQty + (int) $data['quantity'];

            if (!$product->available_for_preorder) {
                if ($variant) {
                    if ($variant->stock < $requestedTotal) {
                        return ['error' => ['quantity' => 'Not enough stock available for this variant']];
                    }
                } else {
                    $reserved = $this->getReservedQty($product);
                    $free = (int) $product->stock - $reserved;
                    if ($free < $requestedTotal) {
                        return ['error' => ['quantity' => 'Not enough stock available']];
                    }
                }
            }

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = $requestedTotal;
                $cart[$key]['size'] = $data['size'] ?? $cart[$key]['size'];
                $cart[$key]['long_sleeve'] = $request->boolean('long_sleeve');
                // Ensure variant ID is set if it wasn't before (though key implies it)
                $cart[$key]['product_variant_id'] = $variant ? $variant->id : ($cart[$key]['product_variant_id'] ?? null);
            } else {
                $cart[$key] = [
                    'key' => $key,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'name' => $product->name,
                    'jersey_type' => $product->jersey_type,
                    'price' => (float) $product->price,
                    'quantity' => (int) $data['quantity'],
                    'size' => $data['size'] ?? ($variant ? $variant->name : null),
                    'long_sleeve' => $request->boolean('long_sleeve'),
                    'image' => $product->image_path,
                    'is_preorder' => (bool) $product->available_for_preorder,
                ];
            }
            session()->put('cart', $cart);
            return ['ok' => true];
        });
        if (isset($result['error'])) {
            return back()->withErrors($result['error'])->withInput();
        }
        return redirect()->route('cart.show')->with('success', 'Produk berhasil ditambahkan ke cart');
    }

    public function cartShow(Request $request)
    {
        $cart = session()->get('cart', []);
        $currency = $this->resolveCurrency($request);
        $config = $this->getCurrencyConfig($currency);
        $items = [];
        $total = 0.0;
        foreach ($cart as $it) {
            $variantSku = null;
            if (!empty($it['product_variant_id'])) {
                $variant = \App\Models\ProductVariant::find($it['product_variant_id']);
                if ($variant) {
                    $variantSku = $variant->sku;
                }
            }
            $unit = (float) $it['price'] * $config['rate'];
            if (!empty($it['long_sleeve'])) {
                $unit += $config['longSleeve'];
            }
            $line = round($unit * (int) $it['quantity'], 2);
            $items[] = array_merge($it, [
                'unit' => $unit,
                'line_total' => $line,
                'currency' => $currency,
                'variant_sku' => $variantSku,
            ]);
            $total += $line;
        }
        $total = round($total, 2);
        return view('cart.index', ['items' => $items, 'total' => $total, 'currency' => $currency, 'currencyConfig' => $config]);
    }

    public function cartUpdate(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'long_sleeve' => 'sometimes|boolean',
        ]);
        $cart = session()->get('cart', []);
        $key = $data['key'];

        if (!isset($cart[$key])) {
            return back()->withErrors(['key' => 'Item tidak ditemukan di cart']);
        }

        $cart[$key]['quantity'] = (int) $data['quantity'];
        $cart[$key]['long_sleeve'] = $request->boolean('long_sleeve');

        session()->put('cart', $cart);
        return back()->with('success', 'Cart diperbarui');
    }

    public function cartRemove(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string',
        ]);
        $cart = session()->get('cart', []);
        $key = $data['key'];

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
            return back()->with('success', 'Item dihapus dari cart');
        }

        return back()->with('error', 'Item tidak ditemukan');
    }

    public function checkoutCod(Request $request)
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
            'currency' => 'nullable|string|in:MYR,BND,IDR,SGD',
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
        $currency = $data['currency'] ?? $this->resolveCurrency($request);
        $config = $this->getCurrencyConfig($currency);
        $orders = [];
        foreach ($cart as $it) {
            $pre = DB::transaction(function () use ($it, $data, $config, $currency, $fullAddress) {
                $product = Product::where('id', $it['product_id'])->lockForUpdate()->first();
                if (!$product || (!$product->is_active && !$product->available_for_preorder)) {
                    return null;
                }

                $variant = null;
                if (!empty($it['product_variant_id'])) {
                    $variant = \App\Models\ProductVariant::lockForUpdate()->find($it['product_variant_id']);
                }

                if (!$product->available_for_preorder) {
                    if ($variant) {
                        if ($variant->stock < (int) $it['quantity']) {
                            return null;
                        }
                    } else {
                        $reserved = $this->getReservedQty($product);
                        $free = (int) $product->stock - $reserved;
                        if ($free < (int) $it['quantity']) {
                            return null;
                        }
                    }
                }
                $unit = (float) $product->price * $config['rate'];
                if (!empty($it['long_sleeve'])) {
                    $unit += $config['longSleeve'];
                }
                $quantity = (int) $it['quantity'];
                $total = round($unit * $quantity, 2);
                $orderNumber = $this->generateOrderNumberForProduct($product);
                $pre = Preorder::create([
                    'order_number' => $orderNumber,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'name' => $data['name'],
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'address' => $fullAddress,
                    'jersey_type' => $product->jersey_type ?? null,
                    'size' => $it['size'] ?? ($variant ? $variant->name : null),
                    'long_sleeve' => !empty($it['long_sleeve']),
                    'custom_fields' => null,
                    'quantity' => $quantity,
                    'unit_price' => $unit,
                    'total_amount' => $total,
                    'currency' => $currency,
                    'status' => 'pending',
                    'notes' => $data['notes'] ?? null,
                ]);
                PreorderHistory::create([
                    'preorder_id' => $pre->id,
                    'old_status' => null,
                    'new_status' => $pre->status,
                    'note' => 'Order via COD checkout',
                ]);

                if ($pre->email) {
                    Mail::to($pre->email)->send(new OrderCreated($pre));
                }

                return $pre;
            });
            if ($pre) {
                $orders[] = $pre;
            }
        }
        session()->forget('cart');
        return view('cart.thankyou', ['orders' => $orders, 'currency' => $currency]);
    }

    public function markDelivered(Request $request, Preorder $order)
    {
        if ($order->shipping_status !== 'shipped') {
            return back()->with('error', 'Order belum dikirim atau sudah diterima');
        }

        $order->shipping_status = 'delivered';
        $order->save();

        PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => $order->status,
            'note' => 'Order marked as received by customer',
        ]);

        return back()->with('status', 'Terima kasih! Order telah diterima.');
    }

    public function requestRefund(Request $request, Preorder $order)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        if ($order->shipping_status !== 'delivered') {
            return back()->with('error', 'Barang harus diterima terlebih dahulu sebelum request refund');
        }

        if (!$order->stripe_payment_intent_id) {
            return back()->with('error', 'Refund otomatis hanya tersedia untuk pembayaran via Stripe');
        }

        if ($order->refund_status && in_array($order->refund_status, ['pending', 'approved', 'completed'])) {
            return back()->with('error', 'Refund request sudah diajukan');
        }

        $order->refund_status = 'pending';
        $order->refund_reason = $request->input('reason');
        $order->refund_amount = $order->total_amount; // Default to full refund
        $order->save();

        PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => $order->status,
            'note' => 'Refund requested by customer: ' . $request->input('reason'),
        ]);

        if ($order->email) {
            Mail::to($order->email)->send(new RefundRequested($order));
        }

        return back()->with('status', 'Permintaan refund telah dikirim dan menunggu persetujuan admin.');
    }
}
