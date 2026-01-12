<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\PreorderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RefundApproved;
use App\Mail\PaymentSuccess;
use App\Jobs\SendEmailJob;
use Stripe\Stripe;
use Stripe\Refund;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreorderAdminController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    public function index(Request $request)
    {
        // Only show preorders (available_for_preorder = true)
        $query = Preorder::query()
            ->whereHas('product', function ($q) {
                $q->where('available_for_preorder', true);
            })
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
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

        $preorders = $query->paginate(10)->withQueryString();

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Preorders', 'url' => route('admin.preorders.index')]
        ));

        return view('admin.preorders.index', compact('preorders', 'counts'));
    }

    public function markPaid(Request $request, Preorder $preorder)
    {
        if ($preorder->status !== 'confirmed') {
            return back()->with('error', 'Order harus dikonfirmasi admin terlebih dahulu sebelum ditandai sebagai paid');
        }
        $old = $preorder->status;
        $preorder->status = 'paid';
        $preorder->save();

        // decrement stock if product exists and stock available
        $note = 'Marked as paid by admin';
        if ($preorder->product) {
            // Check if order has multiple items in JSON
            if (!empty($preorder->items) && is_array($preorder->items)) {
                $note .= '. Stock decremented for items:';
                foreach ($preorder->items as $item) {
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
            // Fallback / Legacy: Check if order has a specific variant
            elseif ($preorder->product_variant_id) {
                $variant = \App\Models\ProductVariant::find($preorder->product_variant_id);
                if ($variant && $variant->stock >= $preorder->quantity && $variant->stock > 0) {
                    $variant->stock = max(0, $variant->stock - $preorder->quantity);
                    $variant->save();
                    $note .= '. Variant stock decremented by ' . $preorder->quantity . ' (variant: ' . $variant->name . ', remaining: ' . $variant->stock . ')';
                } else {
                    $note .= '. Variant stock insufficient or zero; no decrement performed.';
                }
            } else {
                // Fallback to product stock if no variant
                $product = $preorder->product;
                if ($product->stock >= $preorder->quantity && $product->stock > 0) {
                    $product->stock = max(0, $product->stock - $preorder->quantity);
                    $product->save();
                    $note .= '. Stock decremented by ' . $preorder->quantity . ' (remaining: ' . $product->stock . ')';
                } else {
                    $note .= '. Product stock insufficient or zero; no decrement performed.';
                }
            }
        }

        \App\Models\PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $old,
            'new_status' => 'paid',
            'note' => $note,
        ]);

        if ($preorder->email) {
            Mail::to($preorder->email)->send(new PaymentSuccess($preorder));
        }

        return back()->with('status', 'Marked as paid');
    }

    public function confirm(Request $request, Preorder $preorder)
    {
        $old = $preorder->status;

        if ($preorder->status === 'paid') {
            return back()->with('status', 'Order sudah paid');
        }

        if ($preorder->status === 'confirmed') {
            return back()->with('status', 'Order sudah dikonfirmasi');
        }

        $preorder->status = 'confirmed';
        $preorder->save();

        \App\Models\PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $old,
            'new_status' => 'confirmed',
            'note' => 'Confirmed by admin',
        ]);

        return back()->with('status', 'Order dikonfirmasi');
    }

    public function markPacking(Request $request, Preorder $preorder)
    {
        if (!in_array($preorder->status, ['confirmed', 'paid'])) {
            return back()->with('error', 'Order harus dalam status confirmed atau paid sebelum dipacking');
        }

        $oldShippingStatus = $preorder->shipping_status;
        $preorder->shipping_status = 'packing';
        $preorder->save();

        PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $preorder->status,
            'new_status' => $preorder->status,
            'note' => 'Order sedang dipacking' . ($oldShippingStatus ? ' (dari: ' . $oldShippingStatus . ')' : ''),
        ]);

        return back()->with('status', 'Order ditandai sebagai packing');
    }

    public function markShipped(Request $request, Preorder $preorder)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        if ($preorder->shipping_status !== 'packing') {
            return back()->with('error', 'Order harus dalam status packing sebelum dikirim');
        }

        $preorder->shipping_status = 'shipped';
        $preorder->tracking_number = $request->input('tracking_number');
        $preorder->save();

        PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $preorder->status,
            'new_status' => $preorder->status,
            'note' => 'Order telah dikirim. Nomor resi: ' . $preorder->tracking_number,
        ]);

        return back()->with('status', 'Order ditandai sebagai shipped dengan nomor resi: ' . $preorder->tracking_number);
    }

    public function markDelivered(Request $request, Preorder $preorder)
    {
        if ($preorder->shipping_status !== 'shipped') {
            return back()->with('error', 'Order harus dalam status shipped sebelum ditandai sebagai delivered');
        }

        $preorder->shipping_status = 'delivered';
        $preorder->save();

        PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $preorder->status,
            'new_status' => $preorder->status,
            'note' => 'Order telah diterima oleh customer',
        ]);

        return back()->with('status', 'Order ditandai sebagai delivered');
    }

    public function destroy(Preorder $preorder)
    {
        // record deletion in history before deleting
        \App\Models\PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $preorder->status,
            'new_status' => 'deleted',
            'note' => 'Deleted by admin',
        ]);

        $preorder->delete();

        return back()->with('status', 'Preorder deleted successfully');
    }

    public function show(Preorder $preorder)
    {
        $preorder->load('product', 'histories');

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Preorders', 'url' => route('admin.preorders.index')],
            ['label' => '#' . $preorder->order_number, 'url' => route('admin.preorders.show', $preorder)]
        ));

        return view('admin.preorders.show', compact('preorder'));
    }

    public function history(Request $request)
    {
        $type = $request->query('type', 'all'); // all | preorder | order
        $query = Preorder::query()->with(['product', 'histories'])->orderByDesc('created_at');

        if ($type === 'preorder') {
            $query->whereHas('product', function ($q) {
                $q->where('available_for_preorder', true);
            });
        } elseif ($type === 'order') {
            $query->whereHas('product', function ($q) {
                $q->where('available_for_preorder', false)->where('is_active', true);
            });
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        $counts = [
            'all' => Preorder::count(),
            'preorder' => Preorder::whereHas('product', fn($q) => $q->where('available_for_preorder', true))->count(),
            'order' => Preorder::whereHas('product', fn($q) => $q->where('available_for_preorder', false)->where('is_active', true))->count(),
        ];

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders History', 'url' => route('admin.orders.history')],
            ['label' => ucfirst($type), 'url' => route('admin.orders.history', ['type' => $type])]
        ));

        return view('admin.orders.history', compact('orders', 'type', 'counts'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $fileName = 'preorders_' . date('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['id', 'name', 'email', 'phone', 'jersey_type', 'size', 'long_sleeve', 'nameset', 'nameset_text', 'quantity', 'unit_price', 'total_amount', 'status', 'created_at']);

            Preorder::whereHas('product', function ($q) {
                $q->where('available_for_preorder', true);
            })->orderByDesc('created_at')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->name,
                        $r->email,
                        $r->phone,
                        $r->jersey_type,
                        $r->size,
                        $r->long_sleeve ? '1' : '0',
                        $r->nameset ? '1' : '0',
                        $r->nameset_text,
                        $r->quantity,
                        number_format($r->unit_price, 2, '.', ''),
                        number_format($r->total_amount, 2, '.', ''),
                        $r->status,
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
     * Request refund for a preorder
     */
    public function requestRefund(Request $request, Preorder $preorder)
    {
        $request->validate([
            'refund_reason' => 'required|string|max:1000',
            'refund_amount' => 'nullable|numeric|min:0|max:' . $preorder->total_amount,
        ]);

        if (!$preorder->stripe_payment_intent_id) {
            return back()->with('error', 'Preorder ini tidak menggunakan Stripe payment, tidak dapat direfund');
        }

        if ($preorder->refund_status && in_array($preorder->refund_status, ['pending', 'approved', 'completed'])) {
            return back()->with('error', 'Refund request sudah ada untuk preorder ini');
        }

        $refundAmount = $request->input('refund_amount', $preorder->total_amount);

        $preorder->refund_status = 'pending';
        $preorder->refund_amount = $refundAmount;
        $preorder->refund_reason = $request->input('refund_reason');
        $preorder->save();

        PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $preorder->status,
            'new_status' => $preorder->status,
            'note' => 'Refund requested: ' . $request->input('refund_reason') . ' (Amount: ' . $preorder->currency . ' ' . number_format($refundAmount, 2) . ')',
        ]);

        return back()->with('status', 'Refund request telah dibuat, menunggu konfirmasi admin');
    }

    /**
     * Approve and process refund
     */
    public function approveRefund(Request $request, Preorder $preorder)
    {
        if ($preorder->refund_status !== 'pending') {
            return back()->with('error', 'Refund request tidak dalam status pending');
        }

        if (!$preorder->stripe_payment_intent_id) {
            return back()->with('error', 'Preorder ini tidak memiliki Stripe payment intent ID');
        }

        try {
            DB::beginTransaction();

            // Create refund in Stripe
            $refundAmount = $this->convertToCents($preorder->refund_amount, $preorder->currency);

            $refund = Refund::create([
                'payment_intent' => $preorder->stripe_payment_intent_id,
                'amount' => $refundAmount,
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'order_number' => $preorder->order_number,
                    'refund_reason' => $preorder->refund_reason ?? 'Admin approved refund',
                ],
            ]);

            // Update preorder
            $preorder->refund_status = 'approved';
            $preorder->stripe_refund_id = $refund->id;
            $preorder->status = 'refunded';
            $preorder->save();

            PreorderHistory::create([
                'preorder_id' => $preorder->id,
                'old_status' => 'pending',
                'new_status' => 'refunded',
                'note' => 'Refund approved and processed via Stripe. Refund ID: ' . $refund->id . ' (Amount: ' . $preorder->currency . ' ' . number_format($preorder->refund_amount, 2) . ')',
            ]);

            // Restore stock if product exists
            // Restore stock if product exists
            if ($preorder->product && !$preorder->product->available_for_preorder) {
                if (!empty($preorder->items) && is_array($preorder->items)) {
                    foreach ($preorder->items as $item) {
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
                elseif ($preorder->product_variant_id) {
                    $variant = \App\Models\ProductVariant::find($preorder->product_variant_id);
                    if ($variant) {
                        $variant->stock = $variant->stock + $preorder->quantity;
                        $variant->save();
                    }
                } else {
                    // Fallback to product stock if no variant
                    $product = $preorder->product;
                    $product->stock = $product->stock + $preorder->quantity;
                    $product->save();
                }
            }

            if ($preorder->email) {
                // Send email with delay to avoid rate limiting
                SendEmailJob::dispatch($preorder->email, new RefundApproved($preorder), 2);
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
    public function rejectRefund(Request $request, Preorder $preorder)
    {
        if ($preorder->refund_status !== 'pending') {
            return back()->with('error', 'Refund request tidak dalam status pending');
        }

        $preorder->refund_status = 'rejected';
        $preorder->save();

        PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $preorder->status,
            'new_status' => $preorder->status,
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
