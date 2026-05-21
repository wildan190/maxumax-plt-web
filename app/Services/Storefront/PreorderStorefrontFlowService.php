<?php

namespace App\Services\Storefront;

use App\Http\Requests\Preorder\CheckoutCodRequest;
use App\Http\Requests\Preorder\StorePreorderRequest;
use App\Jobs\SendEmailJob;
use App\Mail\OrderCreated;
use App\Mail\RefundRequested;
use App\Models\Feedback;
use App\Models\Preorder;
use App\Models\PreorderHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Notifications\NewPreorderNotification;
use App\Services\CurrencyService;
use App\Services\EasyParcelService;
use App\Services\MyParcelAsiaService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PreorderStorefrontFlowService
{
    public function __construct(
        protected OrderService $orderService,
        protected CurrencyService $currencyService,
    ) {}

    public function submitProductOrderForm(StorePreorderRequest $request)
    {
        $data = $request->validated();

        $product = Product::findOrFail($data['product_id']);
        if (!$product->is_active && !$product->available_for_preorder) {
            abort(404);
        }

        $itemsData = array_filter($data['items'] ?? [], fn ($item) => ($item['quantity_ss'] ?? 0) > 0 || ($item['quantity_ls'] ?? 0) > 0);

        if ($itemsData === []) {
            return back()->withErrors(['items' => 'Please select at least one item quantity.'])->withInput();
        }

        $currency = $data['currency'] ?? $this->currencyService->resolveCurrency($request);
        $config = $this->currencyService->getCurrencyConfig($currency);

        $order = $this->orderService->createOrder(
            array_merge($data, [
                'status' => 'pending',
                'history_note' => 'Order created via ' . ($product->available_for_preorder ? 'Preorder' : 'Ready Stock'),
            ]),
            $product,
            $itemsData,
            $currency,
            $config
        );

        if ($order && $order->email) {
            SendEmailJob::dispatch($order->email, new OrderCreated($order), 2);
        }

        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        if ($admins->isNotEmpty()) {
            $notification = str_starts_with($order->order_number, 'MM-PO-')
                ? new NewPreorderNotification($order)
                : new NewOrderNotification($order);
            Notification::send($admins, $notification);

            if ($order->email) {
                $buyer = User::where('email', $order->email)->first();
                if ($buyer) {
                    $buyer->notify($notification);
                }
            }
        }

        $redirect = $product->available_for_preorder ? 'preorder.thankyou' : 'order.thankyou';

        return redirect()->route($redirect, ['uuid' => $order->uuid]);
    }

    public function thankYouView(string $uuid)
    {
        $preorders = Preorder::with('product')->where('uuid', $uuid)->get();

        if ($preorders->isEmpty()) {
            if (is_numeric($uuid)) {
                $pre = Preorder::with('product')->find($uuid);
                if ($pre) {
                    $preorders = collect([$pre]);
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }

        return view('preorder.thankyou', ['preorders' => $preorders]);
    }

    public function trackOrderView(Request $request, EasyParcelService $easyParcel)
    {
        $order = $request->query('order');
        $pre = null;
        $error = null;
        $tracking = null;
        if ($order) {
            $pre = Preorder::with(['product', 'histories', 'complaints'])->where('order_number', $order)->first();
            if (!$pre) {
                $error = 'Order tidak ditemukan';
            } elseif (!empty($pre->tracking_number)) {
                $isMyParcel = !empty($pre->shipping_courier_name) && stripos($pre->shipping_courier_name, 'myparcel') !== false;
                if ($isMyParcel) {
                    $mpa = new MyParcelAsiaService();
                    $trace = $mpa->trace(['tracking' => $pre->tracking_number]);
                    $statusText = null;
                    if (!empty($trace['status'])) {
                        $d = $trace['data'] ?? [];
                        $statusText = is_array($d) ? ($d['status'] ?? ($d['current_status'] ?? null)) : null;
                    }
                    if (!$statusText) {
                        $statResp = $mpa->getShipmentStatuses(['tracking_no' => $pre->tracking_number]);
                        if (!empty($statResp['status'])) {
                            $dd = $statResp['data'] ?? [];
                            $statusText = is_array($dd) ? ($dd['status'] ?? ($dd['current_status'] ?? null)) : null;
                        }
                    }
                    if ($statusText) {
                        $tracking = ['api_status' => 'Success', 'result' => [['status' => $statusText]]];
                    }
                } else {
                    $tracking = $easyParcel->trackParcel($pre->tracking_number);
                }
            }
        }

        return view('order.track', [
            'orderInput' => $order,
            'preorder' => $pre,
            'error' => $error,
            'tracking' => $tracking,
        ]);
    }

    public function retailProductCatalogView(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('available_for_preorder', false);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('collection', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('sport')) {
            $query->where(function ($q) use ($request) {
                $q->where('collection', $request->sport)
                    ->orWhereJsonContains('collections', $request->sport);
            });
        }

        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('fit')) {
            $query->where('fit', $request->fit);
        }

        if ($request->filled('filter')) {
            if ($request->filter === 'new-arrivals') {
                $query->latest();
            }
        } elseif ($request->filled('sort')) {
            if ($request->sort === 'price_low') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_high') {
                $query->orderBy('price', 'desc');
            }
        } else {
            $query->orderBy('position', 'asc')->orderBy('created_at', 'desc');
        }

        $products = $query->get();

        $currency = $this->currencyService->resolveCurrency($request);
        $currencyConfig = $this->currencyService->getCurrencyConfig($currency);

        return view('products.index', compact('products', 'currency', 'currencyConfig'));
    }

    public function retailProductDetailView(Request $request, Product $product)
    {
        if (!($product->is_active && !$product->available_for_preorder)) {
            abort(404);
        }

        $product->load('variants');

        $currency = $this->currencyService->resolveCurrency($request);
        $avg = round((float) Feedback::where('product_id', $product->id)->avg('rating'), 2);
        $count = (int) Feedback::where('product_id', $product->id)->count();
        $latest = Feedback::where('product_id', $product->id)->orderByDesc('created_at')->limit(6)->get();

        $currencyConfig = $this->currencyService->getCurrencyConfig($currency);

        return view('products.show', [
            'product' => $product,
            'feedbackAvg' => $avg,
            'feedbackCount' => $count,
            'latestFeedback' => $latest,
            'currency' => $currency,
            'currencyConfig' => $currencyConfig,
        ]);
    }

    public function setCurrencyJson(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|in:MYR,BND,IDR,SGD',
        ]);

        session(['currency' => $request->currency, 'currency_manual' => true]);

        return response()->json(['success' => true, 'currency' => $request->currency]);
    }

    public function cartAddLine(Request $request)
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

            $variant = null;
            if (!empty($data['product_variant_id'])) {
                $variant = ProductVariant::where('id', $data['product_variant_id'])
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

            $key = (string) $product->id;
            if ($variant) {
                $key .= '-' . $variant->id;
            } elseif (!empty($data['size'])) {
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
                    $reserved = $this->orderService->getReservedQty($product);
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
                $cart[$key]['product_variant_id'] = $variant ? $variant->id : ($cart[$key]['product_variant_id'] ?? null);
            } else {
                $cart[$key] = [
                    'key' => $key,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'name' => $product->name,
                    'jersey_type' => $product->jersey_type,
                    'price' => (float) ($product->on_sale && $product->discounted_price !== null ? $product->discounted_price : $product->price),
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

    public function cartOverviewView(Request $request)
    {
        $cart = session()->get('cart', []);
        $currency = $this->currencyService->resolveCurrency($request);
        $config = $this->currencyService->getCurrencyConfig($currency);
        $items = [];
        $total = 0.0;
        foreach ($cart as $it) {
            $unit = (float) $it['price'] * $config['rate'];
            if (!empty($it['long_sleeve'])) {
                $unit += $config['longSleeve'];
            }
            $line = round($unit * (int) $it['quantity'], 2);
            $items[] = array_merge($it, [
                'unit' => $unit,
                'line_total' => $line,
                'currency' => $currency,
            ]);
            $total += $line;
        }
        $total = round($total, 2);

        return view('cart.index', ['items' => $items, 'total' => $total, 'currency' => $currency, 'currencyConfig' => $config]);
    }

    public function cartUpdateQuantities(Request $request)
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

    public function cartRemoveLine(Request $request)
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

    public function checkoutCodView(CheckoutCodRequest $request)
    {
        $data = $request->validated();
        $cart = session()->get('cart', []);
        if ($cart === []) {
            return back()->withErrors(['cart' => 'Cart kosong']);
        }

        $currency = $data['currency'] ?? $this->currencyService->resolveCurrency($request);
        $config = $this->currencyService->getCurrencyConfig($currency);
        $orders = [];

        foreach ($cart as $it) {
            $product = Product::find($it['product_id']);
            if (!$product) {
                continue;
            }

            $variantKey = $it['product_variant_id'] ?? null;
            $itemsData = [
                ($variantKey ?? 'no_variant') => [
                    'quantity_ss' => $it['long_sleeve'] ? 0 : $it['quantity'],
                    'quantity_ls' => $it['long_sleeve'] ? $it['quantity'] : 0,
                    'namesets_ss' => [],
                    'namesets_ls' => [],
                    '_variant_id_override' => $variantKey,
                ],
            ];

            try {
                $order = $this->orderService->createOrder(
                    array_merge($data, [
                        'status' => 'pending',
                        'history_note' => 'Order via COD checkout',
                    ]),
                    $product,
                    $itemsData,
                    $currency,
                    $config
                );

                if ($order && $order->email) {
                    Mail::to($order->email)->send(new OrderCreated($order));
                }

                $orders[] = $order;
            } catch (\Exception $e) {
                Log::error('COD creation failed: ' . $e->getMessage());
            }
        }

        session()->forget('cart');

        return view('cart.thankyou', ['orders' => collect($orders), 'currency' => $currency]);
    }

    public function customerMarkDelivered(Preorder $order)
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

    public function customerRequestRefund(Request $request, Preorder $order)
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
        $order->refund_amount = $order->total_amount;
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
