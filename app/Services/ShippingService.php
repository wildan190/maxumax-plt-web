<?php

namespace App\Services;

use App\Models\Preorder;
use Illuminate\Support\Facades\Cache;

class ShippingService
{
    protected EasyParcelService $easyParcel;

    public function __construct(?EasyParcelService $easyParcel = null)
    {
        $this->easyParcel = $easyParcel ?? new EasyParcelService();
    }

    public function getRates(array $params): array
    {
        $key = implode('|', [
            'rates',
            $params['pick_code'] ?? '',
            $params['pick_state'] ?? '',
            $params['pick_country'] ?? '',
            $params['send_code'] ?? '',
            $params['send_state'] ?? '',
            $params['send_country'] ?? '',
            (string) round((float) ($params['weight'] ?? 1), 2),
        ]);
        return Cache::remember($key, 600, function () use ($params) {
            $result = $this->easyParcel->checkRate($params);
            $rates = [];
            if (isset($result['api_status']) && $result['api_status'] === 'Success') {
                $list = $result['result'][0]['rates'] ?? [];
                $rates = collect($list)->map(function($rate) {
                    return [
                        'source' => 'easyparcel',
                        'service_id' => $rate['service_id'] ?? null,
                        'courier_name' => $rate['courier_name'] ?? 'EasyParcel',
                        'courier_logo' => $rate['courier_logo'] ?? null,
                        'service_name' => $rate['service_name'] ?? '',
                        'price' => isset($rate['price']) ? (float) $rate['price'] : null,
                        'delivery' => $rate['delivery'] ?? 'N/A',
                    ];
                })->filter(fn($x) => $x['service_id'] && $x['price'] !== null)->sortBy('price')->values()->toArray();
            }
            return $rates;
        });
    }

    public function bookShipment(Preorder $order, array $payload): array
    {
        $resp = $this->easyParcel->submitOrder($payload);
        if (isset($resp['api_status']) && $resp['api_status'] === 'Success') {
            $shipment = $resp['result'][0] ?? [];
            $awb = $shipment['awb'] ?? ($shipment['awb_no'] ?? null);
            if ($awb) {
                return ['success' => true, 'awb' => $awb, 'message' => 'Shipment booked successfully. AWB: ' . $awb];
            }
            $courier = $shipment['courier'] ?? 'Courier';
            $orderNo = $shipment['order_number'] ?? ($order->order_number ?? null);
            $msg = 'Shipment booked; AWB belum tersedia dari ' . $courier . '.';
            if ($orderNo) $msg .= ' Order: ' . $orderNo . '.';
            $msg .= ' Silakan klik Refresh Tracking beberapa menit lagi.';
            return ['success' => true, 'awb' => null, 'message' => $msg];
        }
        $err = $resp['error_remark'] ?? ($resp['result'] ?? 'Booking failed');
        return ['success' => false, 'message' => is_string($err) ? $err : 'Booking failed'];
    }

    public function track(string $trackingNumber): array
    {
        return $this->easyParcel->trackParcel($trackingNumber);
    }
}
