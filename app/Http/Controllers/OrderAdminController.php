<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\PreorderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\EasyParcelService;
use App\Services\DelyvaService;
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

    public function shipping(Preorder $order)
    {
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders', 'url' => route('admin.orders.index')],
            ['label' => '#' . $order->order_number, 'url' => route('admin.orders.show', $order)],
            ['label' => 'Shipping', 'url' => route('admin.orders.shipping', $order)]
        ));
        return view('admin.orders.shipping', ['order' => $order, 'rates' => session('rates') ?? []]);
    }

    public function checkRates(Request $request, Preorder $order, EasyParcelService $easyParcel, DelyvaService $delyva)
    {
        $data = $request->validate([
            'pick_code' => 'required',
            'pick_state' => 'required',
            'pick_country' => 'required',
            'send_code' => 'required',
            'send_state' => 'required',
            'send_country' => 'required',
            'weight' => 'required|numeric',
        ]);

        $ep = $easyParcel->checkRate($data);
        $epRates = [];
        if (isset($ep['api_status']) && $ep['api_status'] === 'Success') {
            $epRates = $ep['result'][0]['rates'] ?? [];
        }

        $origin = [
            'name' => config('app.name'),
            'address1' => 'Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens',
            'postcode' => $data['pick_code'],
            'state' => $data['pick_state'],
            'city' => 'Kota Kinabalu',
            'country' => $data['pick_country'],
            'phone' => $request->pick_contact ?? '',
            'email' => null,
        ];
        $destination = [
            'name' => $order->name,
            'address1' => $order->address ?? '',
            'postcode' => $data['send_code'],
            'state' => $data['send_state'],
            'city' => null,
            'country' => $data['send_country'],
            'phone' => $order->phone ?? '',
            'email' => $order->email ?? null,
        ];
        $items = [
            [
                'name' => 'Jersey',
                'quantity' => max(1, (int) $order->quantity),
                'weight' => ['unit' => 'kg', 'value' => (float) $data['weight']],
            ]
        ];
        $dq = $delyva->quote($origin, $destination, $items);

        $formattedEp = collect($epRates)->map(function($r) {
            return [
                'source' => 'easyparcel',
                'service_id' => $r['service_id'] ?? null,
                'courier_name' => $r['courier_name'] ?? 'EasyParcel',
                'courier_logo' => $r['courier_logo'] ?? null,
                'service_name' => $r['service_name'] ?? '',
                'price' => isset($r['price']) ? (float) $r['price'] : null,
                'delivery' => $r['delivery'] ?? 'N/A',
            ];
        });

        $formattedDelyva = collect([]);
        if (!empty($dq['data']) && is_array($dq['data'])) {
            $formattedDelyva = collect($dq['data'])->map(function($q) {
                $price = null;
                if (isset($q['price'])) {
                    $price = is_array($q['price']) ? ($q['price']['amount'] ?? null) : $q['price'];
                }
                return [
                    'source' => 'delyva',
                    'service_id' => $q['serviceCode'] ?? $q['serviceId'] ?? ($q['service']['code'] ?? null),
                    'courier_name' => 'Delyva',
                    'courier_logo' => null,
                    'service_name' => $q['serviceName'] ?? ($q['serviceCode'] ?? ($q['service']['name'] ?? 'Delyva Service')),
                    'price' => $price ? (float) $price : null,
                    'delivery' => $q['estimatedDelivery'] ?? ($q['delivery'] ?? 'N/A'),
                ];
            });
        }

        $rates = $formattedEp->merge($formattedDelyva)->filter(fn($x) => $x['service_id'] && $x['price'] !== null)->sortBy('price')->values()->toArray();
        if (empty($rates)) {
            return back()->withInput()->with('error', 'No rates available');
        }

        return redirect()->route('admin.orders.shipping', $order)
            ->with('rates', $rates)
            ->withInput();
    }

    public function bookShipping(Request $request, Preorder $order, EasyParcelService $easyParcel, DelyvaService $delyva)
    {
        $request->validate([
            'service_id' => 'required',
            'weight' => 'required|numeric',
            'pick_code' => 'required',
            'pick_state' => 'required',
            'pick_country' => 'required',
            'pick_name' => 'required',
            'pick_contact' => 'required',
            'pick_addr1' => 'required',
            'send_code' => 'required',
            'send_state' => 'required',
            'send_country' => 'required',
            'send_name' => 'required',
            'send_contact' => 'required',
            'send_addr1' => 'required',
            'courier_source' => 'nullable|string|in:easyparcel,delyva',
        ]);
        $source = $request->input('courier_source', 'easyparcel');
        if ($source === 'delyva') {
            $origin = [
                'name' => $request->pick_name,
                'address1' => $request->pick_addr1,
                'postcode' => $request->pick_code,
                'state' => $request->pick_state,
                'city' => 'Kota Kinabalu',
                'country' => $request->pick_country,
                'phone' => $request->pick_contact,
                'email' => $order->email,
            ];
            $destination = [
                'name' => $request->send_name,
                'address1' => $request->send_addr1,
                'postcode' => $request->send_code,
                'state' => $request->send_state,
                'city' => null,
                'country' => $request->send_country,
                'phone' => $request->send_contact,
                'email' => $order->email,
            ];
            $items = [
                [
                    'name' => 'Jersey',
                    'quantity' => max(1, (int) $order->quantity),
                    'weight' => ['unit' => 'kg', 'value' => (float) $request->weight],
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
                $serviceCode = $request->service_id;
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
                    return redirect()->route('admin.orders.show', $order)->with('status', 'Shipment booked successfully. Consignment: ' . $consignmentNo);
                }
                return back()->with('error', 'Booking successful but consignment not returned.');
            }
            return back()->with('error', 'Booking failed: ' . json_encode($created));
        }

        $orderData = [
            'weight' => $request->weight,
            'content' => 'Jersey',
            'value' => $order->total_amount,
            'service_id' => $request->service_id,
            'order_number' => $order->order_number,
            
            'pick_name' => $request->pick_name,
            'pick_company' => $request->pick_company ?? config('app.name'),
            'pick_contact' => $request->pick_contact,
            'pick_mobile' => $request->pick_contact,
            'pick_addr1' => $request->pick_addr1,
            'pick_addr2' => $request->pick_addr2 ?? '',
            'pick_code' => $request->pick_code,
            'pick_state' => $request->pick_state,
            'pick_province' => $request->pick_state,
            'pick_country' => $request->pick_country,
            'pick_city' => 'Kota Kinabalu',
            'pick_email' => config('mail.from.address'),
            
            'send_name' => $request->send_name,
            'send_contact' => $request->send_contact,
            'send_mobile' => $request->send_contact,
            'send_addr1' => $request->send_addr1,
            'send_addr2' => $request->send_addr2 ?? '',
            'send_code' => $request->send_code,
            'send_state' => $request->send_state,
            'send_province' => $request->send_state,
            'send_country' => $request->send_country,
            'send_city' => $request->send_city ?? '',
            'send_email' => $order->email,
        ];
        
        $result = $easyParcel->submitOrder($orderData);
        
        if (isset($result['api_status']) && $result['api_status'] === 'Success') {
            $shipment = $result['result'][0] ?? null;
            $awb = $shipment['awb'] ?? ($shipment['awb_no'] ?? null);
            
            if ($awb) {
                $order->tracking_number = $awb;
                $order->shipping_status = 'shipped'; 
                $order->save();
                
                PreorderHistory::create([
                    'preorder_id' => $order->id,
                    'old_status' => $order->status,
                    'new_status' => $order->status,
                    'note' => 'Booked via EasyParcel. AWB: ' . $awb,
                ]);
                
                return redirect()->route('admin.orders.show', $order)->with('status', 'Shipment booked successfully. AWB: ' . $awb);
            } else {
                return back()->with('error', 'Booking successful but no AWB returned. Response: ' . json_encode($result));
            }
        }
        
        return back()->with('error', 'Booking failed: ' . json_encode($result));
    }

    public function refreshTracking(Preorder $order, EasyParcelService $easyParcel)
    {
        if (empty($order->tracking_number)) {
            return back()->with('error', 'No tracking number found for this order');
        }
        try {
            $result = $easyParcel->trackParcel($order->tracking_number);
            if (isset($result['api_status']) && $result['api_status'] === 'Success') {
                $track = $result['result'][0] ?? [];
                $status = $track['status'] ?? null;
                if ($status) {
                    $normalized = strtolower($status);
                    if (str_contains($normalized, 'deliver')) {
                        $order->shipping_status = 'delivered';
                    } elseif (str_contains($normalized, 'ship')) {
                        $order->shipping_status = 'shipped';
                    } elseif (str_contains($normalized, 'pack')) {
                        $order->shipping_status = 'packing';
                    }
                    $order->save();
                    PreorderHistory::create([
                        'preorder_id' => $order->id,
                        'old_status' => $order->status,
                        'new_status' => $order->status,
                        'note' => 'Tracking refreshed: ' . ($status ?? 'N/A'),
                    ]);
                }
                return back()->with('status', 'Tracking refreshed: ' . ($status ?? 'N/A'));
            }
            return back()->with('error', 'Failed to refresh tracking: ' . json_encode($result));
        } catch (\Throwable $e) {
            return back()->with('error', 'Error refreshing tracking: ' . $e->getMessage());
        }
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
