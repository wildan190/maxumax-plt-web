<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\PreorderHistory;
use App\Models\Product;
use Illuminate\Http\Request;

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

    /**
     * Show the preorder landing page with available products.
     */
    public function showLanding(Request $request)
    {
        $products = Product::where(function ($q) {
            $q->where('is_active', true)
                ->orWhere('available_for_preorder', true);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('preorder.landing', compact('products'));
    }

    /**
     * Show the preorder creation form for a specific product.
     */
    public function create(Request $request, Product $product)
    {
        if (!($product->is_active || $product->available_for_preorder)) {
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

        $product = Product::find($data['product_id']);
        if (!$product || (!$product->is_active && !$product->available_for_preorder)) {
            return back()->withErrors(['product_id' => 'Product not available']);
        }

        // Check stock if not a preorder-only item
        if (!$product->available_for_preorder) {
            $available = (int) $product->stock;
            if ($available < $data['quantity']) {
                return back()->withErrors(['quantity' => 'Not enough stock available.']);
            }
        }

        $currency = $data['currency'] ?? 'MYR';
        $config = $this->getCurrencyConfig($currency);

        $unit = (float) $product->price * $config['rate'];

        if ($request->boolean('long_sleeve')) {
            $unit += $config['longSleeve'];
        }

        // Add nameset cost if custom fields are provided
        $hasCustomization = !empty($data['custom_fields']);
        if ($hasCustomization) {
            $unit += $config['nameset'];
        }

        $quantity = (int) ($data['quantity'] ?? 1);
        $total = round($unit * $quantity, 2);

        $pre = Preorder::create([
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

        return redirect()->route('preorder.thankyou', ['id' => $pre->id]);
    }

    /**
     * Show the thank you page after placing a preorder.
     */
    public function thankyou(Request $request, $id)
    {
        $pre = Preorder::findOrFail($id);
        return view('preorder.thankyou', ['preorder' => $pre]);
    }
}
