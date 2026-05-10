<?php

namespace App\Services\Admin;

use App\Models\Preorder;
use App\Repositories\Preorder\PreorderHistoryRepository;
use App\Services\EasyParcelService;
use App\Services\MyParcelAsiaService;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class PreorderShippingBackofficeService
{
    public function __construct(
        protected PreorderHistoryRepository $history,
        protected ShippingService $shipping,
    ) {}

    /**
     * @param  string  $redirectAfterRatesRoute  Named route for shipping page
     */
    public function checkRates(Request $request, Preorder $preorder, string $redirectAfterRatesRoute): \Illuminate\Http\RedirectResponse
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
        $rates = $this->shipping->getRates($data);
        if ($rates === []) {
            return back()->withInput()->with('error', 'No rates available');
        }

        return redirect()->route($redirectAfterRatesRoute, $preorder)
            ->with('rates', $rates)
            ->withInput();
    }

    /**
     * @param  string  $redirectShowRoute  e.g. admin.orders.show
     */
    public function bookShipping(Request $request, Preorder $preorder, string $redirectShowRoute): \Illuminate\Http\RedirectResponse
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
            'courier_source' => 'nullable|string|in:easyparcel,delyva,myparcelasia',
        ]);

        $courierSource = $request->input('courier_source', 'easyparcel');
        if ($courierSource === 'myparcelasia') {
            $res = $this->shipping->bookShipmentMyParcel($preorder, $request->all());
            if ($res['success']) {
                if (!empty($res['awb'])) {
                    $preorder->tracking_number = $res['awb'];
                    $preorder->shipping_status = 'shipped';
                    $preorder->save();
                    $this->history->add($preorder->id, $preorder->status, $preorder->status, 'Booked via MyParcel Asia. Ref: ' . $res['awb']);
                }

                return redirect()->route($redirectShowRoute, $preorder)->with('status', $res['message']);
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
            'send_email' => $preorder->email,
        ];

        if ($request->service_id === 'ep_flat') {
            return redirect()->route($redirectShowRoute, $preorder)
                ->with('status', 'Flat rate selected. Booking skipped. Silakan gunakan Book Shipment saat diperlukan.');
        }

        $res = $this->shipping->bookShipment($preorder, $orderData);
        if ($res['success']) {
            if (!empty($res['awb'])) {
                $preorder->tracking_number = $res['awb'];
                $preorder->shipping_status = 'shipped';
                $preorder->save();
                $this->history->add($preorder->id, $preorder->status, $preorder->status, 'Booked via EasyParcel. AWB: ' . $res['awb']);
            }

            return redirect()->route($redirectShowRoute, $preorder)->with('status', $res['message']);
        }

        return back()->with('error', $res['message']);
    }

    public function refreshTracking(Preorder $preorder, EasyParcelService $easyParcel, bool $failIfStatusMissing = false): \Illuminate\Http\RedirectResponse
    {
        if ($preorder->tracking_number === null || $preorder->tracking_number === '') {
            return back()->with('error', 'No tracking number found for this order');
        }
        try {
            $isMyParcel = !empty($preorder->shipping_courier_name) && stripos($preorder->shipping_courier_name, 'myparcel') !== false;
            $status = null;
            if ($isMyParcel) {
                $mpa = new MyParcelAsiaService();
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
                $this->history->add($preorder->id, $preorder->status, $preorder->status, 'Tracking refreshed: ' . ($status ?? 'N/A'));
            }

            if ($failIfStatusMissing && !$status) {
                return back()->with('error', 'Failed to refresh tracking: status not available');
            }

            return back()->with('status', 'Tracking refreshed: ' . ($status ?? 'N/A'));
        } catch (\Throwable $e) {
            $message = $failIfStatusMissing
                ? ('Error refreshing tracking: ' . $e->getMessage())
                : ('Error: ' . $e->getMessage());

            return back()->with('error', $message);
        }
    }
}
