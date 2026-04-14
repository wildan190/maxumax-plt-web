<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\PreorderHistory;
use App\Services\EasyParcelService;
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

        $autoBookingMsg = null;
        if (empty($preorder->tracking_number) && !empty($preorder->shipping_service_id)) {
            try {
                $weight = max(1, (int) $preorder->quantity * 0.5);
                $addr = (string) ($preorder->address ?? '');
                $segments = preg_split('/,\s*/', $addr);
                $postal = null;
                $state = null;
                $city = null;
                foreach ($segments as $i => $seg) {
                    if (stripos($seg, 'Postal ') === 0) {
                        $postal = trim(substr($seg, 7));
                        $state = $segments[$i - 1] ?? null;
                        $city = $segments[$i - 2] ?? null;
                        break;
                    }
                }
                $isDelyva = !empty($preorder->shipping_courier_name) && stripos($preorder->shipping_courier_name, 'delyva') !== false;
                if ($isDelyva) {
                    $delyva = new \App\Services\DelyvaService();
                    $origin = [
                        'name' => config('app.name'),
                        'address1' => 'Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens',
                        'postcode' => '88000',
                        'state' => 'Sabah',
                        'city' => 'Kota Kinabalu',
                        'country' => 'MY',
                        'phone' => $preorder->phone ?? '',
                        'email' => $preorder->email ?? null,
                    ];
                    $destination = [
                        'name' => $preorder->name,
                        'address1' => $preorder->address ?? '',
                        'postcode' => $postal,
                        'state' => $state,
                        'city' => $city,
                        'country' => 'MY',
                        'phone' => $preorder->phone ?? '',
                        'email' => $preorder->email ?? null,
                    ];
                    $items = [
                        [
                            'name' => 'Jersey',
                            'quantity' => $preorder->quantity,
                            'weight' => ['unit' => 'kg', 'value' => $weight],
                        ]
                    ];
                    $meta = [
                        'reference' => $preorder->order_number,
                        'cod' => ['amount' => 0, 'currency' => $preorder->currency],
                        'price' => ['amount' => $preorder->shipping_cost ?? 0, 'currency' => $preorder->currency],
                    ];
                    $created = $delyva->createOrder($origin, $destination, $items, $meta);
                    $orderId = $created['data']['id'] ?? null;
                    if ($orderId) {
                        $serviceCode = $preorder->shipping_service_id;
                        $delyva->processOrder($orderId, $serviceCode);
                        $details = $delyva->getOrder($orderId);
                        $consignmentNo = $details['data']['consignmentNo'] ?? null;
                        if ($consignmentNo) {
                            $preorder->tracking_number = $consignmentNo;
                            $preorder->shipping_status = 'shipped';
                            $preorder->save();
                            PreorderHistory::create([
                                'preorder_id' => $preorder->id,
                                'old_status' => $preorder->status,
                                'new_status' => $preorder->status,
                                'note' => 'Auto-booked via Delyva. Consignment: ' . $consignmentNo,
                            ]);
                            $autoBookingMsg = 'Auto-booked shipment (Consignment: ' . $consignmentNo . ')';
                        }
                    }
                } else {
                    $easyParcel = new EasyParcelService();
                    $orderData = [
                        'weight' => $weight,
                        'content' => 'Jersey',
                        'value' => $preorder->total_amount,
                        'service_id' => $preorder->shipping_service_id,
                        'order_number' => $preorder->order_number,
                        'pick_name' => config('app.name'),
                        'pick_company' => config('app.name'),
                        'pick_contact' => $preorder->phone ?? '',
                        'pick_mobile' => $preorder->phone ?? '',
                        'pick_addr1' => 'Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens',
                        'pick_code' => '88000',
                        'pick_state' => 'Sabah',
                        'pick_province' => 'Sabah',
                        'pick_country' => 'MY',
                        'send_name' => $preorder->name,
                        'send_contact' => $preorder->phone ?? '',
                        'send_mobile' => $preorder->phone ?? '',
                        'send_addr1' => $preorder->address ?? '',
                        'send_code' => $postal,
                        'send_state' => $state,
                        'send_province' => $state,
                        'send_country' => 'MY',
                        'send_email' => $preorder->email,
                    ];
                    $result = $easyParcel->submitOrder($orderData);
                    if (isset($result['api_status']) && $result['api_status'] === 'Success') {
                        $shipment = $result['result'][0] ?? [];
                        $awb = $shipment['awb'] ?? null;
                        if ($awb) {
                            $preorder->tracking_number = $awb;
                            $preorder->shipping_status = 'shipped';
                            $preorder->save();
                            PreorderHistory::create([
                                'preorder_id' => $preorder->id,
                                'old_status' => $preorder->status,
                                'new_status' => $preorder->status,
                                'note' => 'Auto-booked via EasyParcel. AWB: ' . $awb,
                            ]);
                            $autoBookingMsg = 'Auto-booked shipment (AWB: ' . $awb . ')';
                        } else {
                            $autoBookingMsg = 'Booking success without AWB';
                        }
                    } else {
                        $autoBookingMsg = 'Booking failed: ' . json_encode($result);
                    }
                }
            } catch (\Throwable $e) {
                $autoBookingMsg = 'Booking error: ' . $e->getMessage();
            }
        }
 
        return back()->with('status', trim('Marked as paid' . ($autoBookingMsg ? ' — ' . $autoBookingMsg : '')));
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
            fputcsv($handle, ['id', 'name', 'email', 'phone', 'address', 'jersey_type', 'size', 'long_sleeve', 'nameset', 'nameset_text', 'quantity', 'unit_price', 'shipping_courier', 'shipping_service', 'shipping_cost', 'tracking_number', 'total_amount', 'status', 'created_at']);

            Preorder::whereHas('product', function ($q) {
                $q->where('available_for_preorder', true);
            })->orderByDesc('created_at')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->name,
                        $r->email,
                        $r->phone,
                        $r->address,
                        $r->jersey_type,
                        $r->size,
                        $r->long_sleeve ? '1' : '0',
                        $r->nameset ? '1' : '0',
                        $r->nameset_text,
                        $r->quantity,
                        number_format($r->unit_price, 2, '.', ''),
                        $r->shipping_courier_name,
                        $r->shipping_service_name,
                        number_format($r->shipping_cost, 2, '.', ''),
                        $r->tracking_number,
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

    public function shipping(Preorder $preorder)
    {
        $rates = session('rates');
        return view('admin.preorders.shipping', compact('preorder', 'rates'));
    }

    public function checkRates(Request $request, Preorder $preorder)
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
        $shipping = new \App\Services\ShippingService();
        $rates = $shipping->getRates($data);
        if (empty($rates)) {
            return back()->withInput()->with('error', 'No rates available');
        }

        return redirect()->route('admin.preorders.shipping', $preorder)
            ->with('rates', $rates)
            ->withInput();
    }

    public function bookShipping(Request $request, Preorder $preorder)
    {
        // For simplicity, we assume the user selected a rate and we just need to submit the order
        // We need all details for EPSubmitOrderBulk
        
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
            'courier_source' => 'nullable|string|in:easyparcel,delyva,myparcelasia',
        ]);

        $courierSource = $request->input('courier_source', 'easyparcel');
        if ($courierSource === 'myparcelasia') {
            $shipping = new \App\Services\ShippingService();
            $res = $shipping->bookShipmentMyParcel($preorder, $request->all());
            if ($res['success']) {
                if (!empty($res['awb'])) {
                    $preorder->tracking_number = $res['awb'];
                    $preorder->shipping_status = 'shipped';
                    $preorder->save();
                    PreorderHistory::create([
                        'preorder_id' => $preorder->id,
                        'old_status' => $preorder->status,
                        'new_status' => $preorder->status,
                        'note' => 'Booked via MyParcel Asia. Ref: ' . $res['awb'],
                    ]);
                }
                return redirect()->route('admin.preorders.show', $preorder)->with('status', $res['message']);
            }
            return back()->with('error', $res['message']);
        }

        $orderData = [
             'weight' => $request->weight,
             'content' => 'Jersey',
             'value' => $preorder->total_amount,
             'service_id' => $request->service_id,
             'order_number' => $preorder->order_number,
             
             'pick_name' => $request->pick_name,
             'pick_company' => $request->pick_company ?? config('app.name'),
             'pick_contact' => $request->pick_contact,
             'pick_mobile' => $request->pick_contact, // Required by EasyParcel
             'pick_addr1' => $request->pick_addr1,
             'pick_addr2' => $request->pick_addr2 ?? '',
             'pick_code' => $request->pick_code,
             'pick_state' => $request->pick_state,
             'pick_province' => $request->pick_state, // Sometimes required
             'pick_country' => $request->pick_country,
             'pick_city' => 'Kota Kinabalu',
             'pick_email' => config('mail.from.address'),
             
             'send_name' => $request->send_name,
             'send_contact' => $request->send_contact,
             'send_mobile' => $request->send_contact, // Required by EasyParcel
             'send_addr1' => $request->send_addr1,
             'send_addr2' => $request->send_addr2 ?? '',
             'send_code' => $request->send_code,
             'send_state' => $request->send_state,
             'send_province' => $request->send_state, // Sometimes required
             'send_country' => $request->send_country,
             'send_city' => $request->send_city ?? '',
             
             // Optional: Email
             'send_email' => $preorder->email,
        ];
        
        $shipping = new \App\Services\ShippingService();
        $res = $shipping->bookShipment($preorder, $orderData);
        if ($res['success']) {
            if (!empty($res['awb'])) {
                $preorder->tracking_number = $res['awb'];
                $preorder->shipping_status = 'shipped';
                $preorder->save();
                PreorderHistory::create([
                    'preorder_id' => $preorder->id,
                    'old_status' => $preorder->status,
                    'new_status' => $preorder->status,
                    'note' => 'Booked via EasyParcel. AWB: ' . $res['awb'],
                ]);
            }
            return redirect()->route('admin.preorders.show', $preorder)->with('status', $res['message']);
        }
        return back()->with('error', $res['message']);
    }

    public function refreshTracking(Preorder $preorder, EasyParcelService $easyParcel)
    {
        if (empty($preorder->tracking_number)) {
            return back()->with('error', 'No tracking number found for this order');
        }
        try {
            $isMyParcel = !empty($preorder->shipping_courier_name) && stripos($preorder->shipping_courier_name, 'myparcel') !== false;
            $status = null;
            if ($isMyParcel) {
                $mpa = new \App\Services\MyParcelAsiaService();
                $trace = $mpa->trace(['tracking' => $preorder->tracking_number]);
                if (!empty($trace['status'])) {
                    $data = $trace['data'] ?? [];
                    $status = is_array($data) ? ($data['status'] ?? ($data['current_status'] ?? null)) : null;
                }
                if (!$status) {
                    $statResp = $mpa->getShipmentStatuses(['tracking_no' => $preorder->tracking_number]);
                    if (!empty($statResp['status'])) {
                        $d = $statResp['data'] ?? [];
                        $status = is_array($d) ? ($d['status'] ?? ($d['current_status'] ?? null)) : null;
                    }
                }
            }
            if (!$isMyParcel) {
                $result = $easyParcel->trackParcel($preorder->tracking_number);
                if (isset($result['api_status']) && $result['api_status'] === 'Success') {
                    $track = $result['result'][0] ?? [];
                    $status = $track['status'] ?? null;
                } else {
                    return back()->with('error', 'Failed to refresh tracking: ' . json_encode($result));
                }
            }
            if ($status) {
                $normalized = strtolower($status);
                if (str_contains($normalized, 'deliver')) {
                    $preorder->shipping_status = 'delivered';
                } elseif (str_contains($normalized, 'ship')) {
                    $preorder->shipping_status = 'shipped';
                } elseif (str_contains($normalized, 'pack')) {
                    $preorder->shipping_status = 'packing';
                }
                $preorder->save();
                PreorderHistory::create([
                    'preorder_id' => $preorder->id,
                    'old_status' => $preorder->status,
                    'new_status' => $preorder->status,
                    'note' => 'Tracking refreshed: ' . ($status ?? 'N/A'),
                ]);
                return back()->with('status', 'Tracking refreshed: ' . ($status ?? 'N/A'));
            }
            return back()->with('error', 'Failed to refresh tracking: status not available');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error refreshing tracking: ' . $e->getMessage());
        }
    }
}
