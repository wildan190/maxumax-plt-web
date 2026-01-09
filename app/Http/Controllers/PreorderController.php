<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Preorder;
use App\Models\PreorderHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreorderController extends Controller
{
    /**
     * Get currency configuration for pricing.
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

        return view('preorder.landing', compact('products'));
    }

    /**
     * Show the preorder creation form for a specific product.
     */
    public function create(Request $request, Product $product)
    {
        if (! ($product->is_active || $product->available_for_preorder)) {
            abort(404);
        }

        return view('preorder.create', ['product' => $product]);
    }

    /**
     * Store a new preorder.
     */
    public function store(Request $request)
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
            if (! $p || (! $p->is_active && ! $p->available_for_preorder)) {
                throw new \RuntimeException('Product not available');
            }
            if (! $p->available_for_preorder) {
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

        if ($request->boolean('long_sleeve')) {
            $unit += $config['longSleeve'];
        }

        // Add nameset cost if custom fields are provided
        $hasCustomization = ! empty($data['custom_fields']);
        if ($hasCustomization) {
            $unit += $config['nameset'];
        }

        $quantity = (int) ($data['quantity'] ?? 1);
        $total = round($unit * $quantity, 2);

        $orderNumber = $this->generateOrderNumberForProduct($product);

        $pre = Preorder::create([
            'order_number' => $orderNumber,
            'product_id' => $product->id,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'jersey_type' => $product->jersey_type ?? null,
            'size' => $data['size'] ?? null,
            'long_sleeve' => $request->boolean('long_sleeve'),
            'custom_fields' => $data['custom_fields'] ?? null,
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
            'note' => 'Order created',
        ]);

        $redirect = $product->available_for_preorder ? 'preorder.thankyou' : 'order.thankyou';

        return redirect()->route($redirect, ['id' => $pre->id]);
    }

    /**
     * Generate a unique, non-ID-based order number.
     */
    private function generateOrderNumberForProduct(Product $product): string
    {
        $prefix = $product->available_for_preorder ? 'MM-PO-' : 'MM-OR-';
        do {
            $code = $prefix.strtoupper(str()->random(8));
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
            $pre = Preorder::where('order_number', $order)->first();
            if (! $pre) {
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
     * Show the thank you page after placing a preorder.
     */
    public function thankyou(Request $request, $id)
    {
        $pre = Preorder::findOrFail($id);

        return view('preorder.thankyou', ['preorder' => $pre]);
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

        return view('products.index', compact('products'));
    }

    /**
     * Show public product detail with feedback and rating.
     * Only shows products that are available (not preorder).
     */
    public function showProduct(Request $request, Product $product)
    {
        if (! ($product->is_active && !$product->available_for_preorder)) {
            abort(404);
        }

        $currency = session()->get('currency', 'MYR');
        $avg = round((float) Feedback::where('product_id', $product->id)->avg('rating'), 2);
        $count = (int) Feedback::where('product_id', $product->id)->count();
        $latest = Feedback::where('product_id', $product->id)->orderByDesc('created_at')->limit(6)->get();

        return view('products.show', [
            'product' => $product,
            'feedbackAvg' => $avg,
            'feedbackCount' => $count,
            'latestFeedback' => $latest,
            'currency' => $currency,
        ]);
    }
    
    public function setCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|in:MYR,BND,IDR',
        ]);
        
        session()->put('currency', $request->currency);
        
        return response()->json(['success' => true, 'currency' => $request->currency]);
    }

    public function cartAdd(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string|max:10',
            'long_sleeve' => 'sometimes|boolean',
        ]);
        $result = DB::transaction(function () use ($data, $request) {
            $product = Product::where('id', $data['product_id'])->lockForUpdate()->first();
            if (! $product || (! $product->is_active && ! $product->available_for_preorder)) {
                return ['error' => ['product_id' => 'Produk tidak tersedia']];
            }
            if (! $product->available_for_preorder && empty($data['size'])) {
                return ['error' => ['size' => 'Pilih ukuran terlebih dahulu']];
            }
            $cart = session()->get('cart', []);
            $key = (string) $product->id;
            $existingQty = isset($cart[$key]) ? (int) $cart[$key]['quantity'] : 0;
            $requestedTotal = $existingQty + (int) $data['quantity'];
            if (! $product->available_for_preorder) {
                $reserved = $this->getReservedQty($product);
                $free = (int) $product->stock - $reserved;
                if ($free < $requestedTotal) {
                    return ['error' => ['quantity' => 'Stok tidak cukup untuk jumlah yang diminta']];
                }
            }
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = $requestedTotal;
                $cart[$key]['size'] = $data['size'] ?? $cart[$key]['size'];
                $cart[$key]['long_sleeve'] = $request->boolean('long_sleeve');
            } else {
                $cart[$key] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'jersey_type' => $product->jersey_type,
                    'price' => (float) $product->price,
                    'quantity' => (int) $data['quantity'],
                    'size' => $data['size'] ?? null,
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
        $currency = session()->get('currency', 'MYR');
        $config = $this->getCurrencyConfig($currency);
        $items = [];
        $total = 0.0;
        foreach ($cart as $it) {
            $unit = (float) $it['price'] * $config['rate'];
            if (! empty($it['long_sleeve'])) {
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
        return view('cart.index', ['items' => $items, 'total' => $total, 'currency' => $currency]);
    }

    public function cartUpdate(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string|max:10',
            'long_sleeve' => 'sometimes|boolean',
        ]);
        $cart = session()->get('cart', []);
        $key = (string) $data['product_id'];
        if (! isset($cart[$key])) {
            return back()->withErrors(['product_id' => 'Item tidak ditemukan di cart']);
        }
        $cart[$key]['quantity'] = (int) $data['quantity'];
        $cart[$key]['size'] = $data['size'] ?? $cart[$key]['size'];
        $cart[$key]['long_sleeve'] = $request->boolean('long_sleeve');
        session()->put('cart', $cart);
        return back()->with('success', 'Cart diperbarui');
    }

    public function cartRemove(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
        ]);
        $cart = session()->get('cart', []);
        $key = (string) $data['product_id'];
        unset($cart[$key]);
        session()->put('cart', $cart);
        return back()->with('success', 'Item dihapus dari cart');
    }

    public function checkoutCod(Request $request)
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
        $orders = [];
        foreach ($cart as $it) {
            $pre = DB::transaction(function () use ($it, $data, $config, $currency) {
                $product = Product::where('id', $it['product_id'])->lockForUpdate()->first();
                if (! $product || (! $product->is_active && ! $product->available_for_preorder)) {
                    return null;
                }
                if (! $product->available_for_preorder) {
                    $reserved = $this->getReservedQty($product);
                    $free = (int) $product->stock - $reserved;
                    if ($free < (int) $it['quantity']) {
                        return null;
                    }
                }
                $unit = (float) $product->price * $config['rate'];
                if (! empty($it['long_sleeve'])) {
                    $unit += $config['longSleeve'];
                }
                $quantity = (int) $it['quantity'];
                $total = round($unit * $quantity, 2);
                $orderNumber = $this->generateOrderNumberForProduct($product);
                $pre = Preorder::create([
                    'order_number' => $orderNumber,
                    'product_id' => $product->id,
                    'name' => $data['name'],
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'address' => $data['address'] ?? null,
                    'jersey_type' => $product->jersey_type ?? null,
                    'size' => $it['size'] ?? null,
                    'long_sleeve' => ! empty($it['long_sleeve']),
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
                return $pre;
            });
            if ($pre) {
                $orders[] = $pre;
            }
        }
        session()->forget('cart');
        return view('cart.thankyou', ['orders' => $orders, 'currency' => $currency]);
    }
}
