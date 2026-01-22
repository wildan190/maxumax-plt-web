<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\PreorderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Refund;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderAdminController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    public function index(Request $request)
    {
        // Only show orders (available_for_preorder = false, is_active = true)
        $query = Preorder::query()
            ->whereHas('product', function ($q) {
                $q->where('available_for_preorder', false)
                    ->where('is_active', true);
            })
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $allQuery = clone $query;
        $counts = [
            'total' => $allQuery->count(),
            'pending' => $allQuery->clone()->where('status', 'pending')->count(),
            'confirmed' => $allQuery->clone()->where('status', 'confirmed')->count(),
            'paid' => $allQuery->clone()->where('status', 'paid')->count(),
        ];

        $orders = $query->paginate(10)->withQueryString();

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders', 'url' => route('admin.orders.index')]
        ));

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function printIndex(Request $request)
    {
        $query = Preorder::query()
            ->whereHas('product', function ($q) {
                $q->where('available_for_preorder', false)
                    ->where('is_active', true);
            })
            ->orderByDesc('created_at');
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('order_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }
        $orders = $query->with(['product', 'variant'])->get();
        return view('admin.orders.print', compact('orders'));
    }

    public function markPaid(Request $request, Preorder $order)
    {
        if ($order->status !== 'confirmed') {
            return back()->with('error', 'Order harus dikonfirmasi admin terlebih dahulu sebelum ditandai sebagai paid');
        }
        $old = $order->status;
        $order->status = 'paid';
        $order->save();

        // decrement stock if product exists and stock available
        $note = 'Marked as paid by admin';
        if ($order->product) {
            // Check if order has multiple items in JSON
            if (!empty($order->items) && is_array($order->items)) {
                $note .= '. Stock decremented for items:';
                foreach ($order->items as $item) {
                    $vid = $item['variant_id'] ?? null;
                    $qty = $item['quantity'] ?? 0;
                    if ($vid && $qty > 0) {
                        $variant = \App\Models\ProductVariant::lockForUpdate()->find($vid);
                        if ($variant) {
                            $variant->stock = max(0, $variant->stock - $qty);
                            $variant->save();
                            $note .= " [{$variant->name}: -{$qty}]";
                        }
                    }
                }
            }
            // Check if order has a specific variant
            elseif ($order->product_variant_id) {
                $variant = \App\Models\ProductVariant::find($order->product_variant_id);
                if ($variant && $variant->stock >= $order->quantity && $variant->stock > 0) {
                    $variant->stock = max(0, $variant->stock - $order->quantity);
                    $variant->save();
                    $note .= '. Variant stock decremented by ' . $order->quantity . ' (variant: ' . $variant->name . ', remaining: ' . $variant->stock . ')';
                } else {
                    $note .= '. Variant stock insufficient or zero; no decrement performed.';
                }
            } else {
                // Fallback to product stock if no variant
                $product = $order->product;
                if ($product->stock >= $order->quantity && $product->stock > 0) {
                    $product->stock = max(0, $product->stock - $order->quantity);
                    $product->save();
                    $note .= '. Stock decremented by ' . $order->quantity . ' (remaining: ' . $product->stock . ')';
                } else {
                    $note .= '. Product stock insufficient or zero; no decrement performed.';
                }
            }
        }

        \App\Models\PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $old,
            'new_status' => 'paid',
            'note' => $note,
        ]);

        return back()->with('status', 'Marked as paid');
    }

    public function confirm(Request $request, Preorder $order)
    {
        $old = $order->status;

        if ($order->status === 'paid') {
            return back()->with('status', 'Order sudah paid');
        }

        if ($order->status === 'confirmed') {
            return back()->with('status', 'Order sudah dikonfirmasi');
        }

        $order->status = 'confirmed';
        $order->save();

        \App\Models\PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $old,
            'new_status' => 'confirmed',
            'note' => 'Confirmed by admin',
        ]);

        return back()->with('status', 'Order dikonfirmasi');
    }

    public function markPacking(Request $request, Preorder $order)
    {
        if (!in_array($order->status, ['confirmed', 'paid'])) {
            return back()->with('error', 'Order harus dalam status confirmed atau paid sebelum dipacking');
        }

        $oldShippingStatus = $order->shipping_status;
        $order->shipping_status = 'packing';
        $order->save();

        PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => $order->status,
            'note' => 'Order sedang dipacking' . ($oldShippingStatus ? ' (dari: ' . $oldShippingStatus . ')' : ''),
        ]);

        return back()->with('status', 'Order ditandai sebagai packing');
    }

    public function markShipped(Request $request, Preorder $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        if ($order->shipping_status !== 'packing') {
            return back()->with('error', 'Order harus dalam status packing sebelum dikirim');
        }

        $order->shipping_status = 'shipped';
        $order->tracking_number = $request->input('tracking_number');
        $order->save();

        PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => $order->status,
            'note' => 'Order telah dikirim. Nomor resi: ' . $order->tracking_number,
        ]);

        return back()->with('status', 'Order ditandai sebagai shipped dengan nomor resi: ' . $order->tracking_number);
    }

    public function markDelivered(Request $request, Preorder $order)
    {
        if ($order->shipping_status !== 'shipped') {
            return back()->with('error', 'Order harus dalam status shipped sebelum ditandai sebagai delivered');
        }

        $order->shipping_status = 'delivered';
        $order->save();

        PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => $order->status,
            'note' => 'Order telah diterima oleh customer',
        ]);

        return back()->with('status', 'Order ditandai sebagai delivered');
    }

    public function destroy(Preorder $order)
    {
        // record deletion in history before deleting
        \App\Models\PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => 'deleted',
            'note' => 'Deleted by admin',
        ]);

        $order->delete();

        return back()->with('status', 'Order deleted successfully');
    }

    public function show(Preorder $order)
    {
        $order->load('product', 'histories');

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders', 'url' => route('admin.orders.index')],
            ['label' => '#' . $order->order_number, 'url' => route('admin.orders.show', $order)]
        ));

        return view('admin.orders.show', compact('order'));
    }

    public function printShow(Preorder $order)
    {
        $order->load('product', 'variant', 'histories');
        return view('admin.orders.print_show', compact('order'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $fileName = 'orders_' . date('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['id', 'order_number', 'name', 'email', 'phone', 'address', 'jersey_type', 'size', 'long_sleeve', 'quantity', 'unit_price', 'total_amount', 'currency', 'status', 'notes', 'created_at']);

            Preorder::whereHas('product', function ($q) {
                $q->where('available_for_preorder', false)
                    ->where('is_active', true);
            })->orderByDesc('created_at')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->order_number,
                        $r->name,
                        $r->email,
                        $r->phone,
                        $r->address,
                        $r->jersey_type,
                        $r->size,
                        $r->long_sleeve ? '1' : '0',
                        $r->quantity,
                        number_format($r->unit_price, 2, '.', ''),
                        number_format($r->total_amount, 2, '.', ''),
                        $r->currency,
                        $r->status,
                        $r->notes,
                        $r->created_at,
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    /**
     * Request refund for an order
     */
    public function requestRefund(Request $request, Preorder $order)
    {
        $request->validate([
            'refund_reason' => 'required|string|max:1000',
            'refund_amount' => 'nullable|numeric|min:0|max:' . $order->total_amount,
        ]);

        if (!$order->stripe_payment_intent_id) {
            return back()->with('error', 'Order ini tidak menggunakan Stripe payment, tidak dapat direfund');
        }

        if ($order->refund_status && in_array($order->refund_status, ['pending', 'approved', 'completed'])) {
            return back()->with('error', 'Refund request sudah ada untuk order ini');
        }

        $refundAmount = $request->input('refund_amount', $order->total_amount);

        $order->refund_status = 'pending';
        $order->refund_amount = $refundAmount;
        $order->refund_reason = $request->input('refund_reason');
        $order->save();

        PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => $order->status,
            'note' => 'Refund requested: ' . $request->input('refund_reason') . ' (Amount: ' . $order->currency . ' ' . number_format($refundAmount, 2) . ')',
        ]);

        return back()->with('status', 'Refund request telah dibuat, menunggu konfirmasi admin');
    }

    /**
     * Approve and process refund
     */
    public function approveRefund(Request $request, Preorder $order)
    {
        if ($order->refund_status !== 'pending') {
            return back()->with('error', 'Refund request tidak dalam status pending');
        }

        if (!$order->stripe_payment_intent_id) {
            return back()->with('error', 'Order ini tidak memiliki Stripe payment intent ID');
        }

        try {
            DB::beginTransaction();

            // Create refund in Stripe
            $refundAmount = $this->convertToCents($order->refund_amount, $order->currency);

            $refund = Refund::create([
                'payment_intent' => $order->stripe_payment_intent_id,
                'amount' => $refundAmount,
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'order_number' => $order->order_number,
                    'refund_reason' => $order->refund_reason ?? 'Admin approved refund',
                ],
            ]);

            // Update order
            $order->refund_status = 'approved';
            $order->stripe_refund_id = $refund->id;
            $order->status = 'refunded';
            $order->save();

            PreorderHistory::create([
                'preorder_id' => $order->id,
                'old_status' => 'pending',
                'new_status' => 'refunded',
                'note' => 'Refund approved and processed via Stripe. Refund ID: ' . $refund->id . ' (Amount: ' . $order->currency . ' ' . number_format($order->refund_amount, 2) . ')',
            ]);

            // Restore stock if product exists
            if ($order->product && !$order->product->available_for_preorder) {
                if (!empty($order->items) && is_array($order->items)) {
                    foreach ($order->items as $item) {
                        $vid = $item['variant_id'] ?? null;
                        $qty = $item['quantity'] ?? 0;
                        if ($vid && $qty > 0) {
                            $variant = \App\Models\ProductVariant::lockForUpdate()->find($vid);
                            if ($variant) {
                                $variant->stock += $qty;
                                $variant->save();
                            }
                        }
                    }
                }
                // Check if order has a specific variant
                elseif ($order->product_variant_id) {
                    $variant = \App\Models\ProductVariant::find($order->product_variant_id);
                    if ($variant) {
                        $variant->stock = $variant->stock + $order->quantity;
                        $variant->save();
                    }
                } else {
                    // Fallback to product stock if no variant
                    $product = $order->product;
                    $product->stock = $product->stock + $order->quantity;
                    $product->save();
                }
            }

            DB::commit();

            return back()->with('status', 'Refund telah disetujui dan diproses melalui Stripe');
        } catch (ApiErrorException $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing refund: ' . $e->getMessage());
        }
    }

    /**
     * Reject refund request
     */
    public function rejectRefund(Request $request, Preorder $order)
    {
        if ($order->refund_status !== 'pending') {
            return back()->with('error', 'Refund request tidak dalam status pending');
        }

        $order->refund_status = 'rejected';
        $order->save();

        PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => $order->status,
            'note' => 'Refund request rejected by admin',
        ]);

        return back()->with('status', 'Refund request telah ditolak');
    }

    /**
     * Convert amount to cents for Stripe
     */
    private function convertToCents(float $amount, string $currency): int
    {
        if ($currency === 'IDR') {
            return (int) round($amount);
        }
        return (int) round($amount * 100);
    }
}
