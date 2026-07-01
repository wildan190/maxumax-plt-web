<?php

namespace App\Services;

use App\Models\Preorder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected EasyParcelService $easyParcel;
    protected MyParcelAsiaService $myParcel;

    public function __construct(?EasyParcelService $easyParcel = null)
    {
        $this->easyParcel = $easyParcel ?? new EasyParcelService();
        $this->myParcel = new MyParcelAsiaService();
    }

    public function getRates(array $params): array
    {
        $mpaConf = (array) config('services.myparcelasia', []);
        $mpaOnly = (bool) ($mpaConf['only'] ?? false);
        $mpaKeyPresent = !empty($mpaConf['api_key'] ?? null);
        if ($mpaOnly && $mpaKeyPresent) {
            return $this->getRatesMyParcel($params);
        }

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

    public function getRatesMyParcel(array $params): array
    {
        return $this->fetchMyParcelRates($params)['rates'];
    }

    /**
     * @return array{rates: array<int, array<string, mixed>>, message: ?string}
     */
    public function fetchMyParcelRates(array $params): array
    {
        $conf = (array) config('services.myparcelasia', []);
        $senderPostcode = (string) ($conf['sender_postcode'] ?? '88000');
        $defaultWeight = (float) ($conf['default_weight'] ?? 2);

        $country = $this->normalizeCountryCode((string) ($params['country'] ?? 'MY'));
        $postcode = preg_replace('/\D/', '', (string) ($params['postcode'] ?? ''));
        $receiverCountryCode = $this->normalizeCountryCode((string) ($params['receiver_country_code'] ?? $country));

        $weight = (float) ($params['weight'] ?? 0);
        if ($weight <= 0) {
            $weight = $defaultWeight;
        }
        $weight = max(0.1, round($weight, 2));

        if ($country === 'MY' && strlen($postcode) < 4) {
            return ['rates' => [], 'message' => 'Invalid postcode for Malaysia.'];
        }

        $cacheKey = implode('|', [
            'rates',
            'myparcelasia',
            $senderPostcode,
            $country,
            $postcode,
            $receiverCountryCode,
            (string) $weight,
        ]);

        return Cache::remember($cacheKey, 600, function () use ($country, $postcode, $senderPostcode, $receiverCountryCode, $weight) {
            return $this->requestMyParcelRates($country, $postcode, $senderPostcode, $receiverCountryCode, $weight);
        });
    }

    /**
     * @return array{rates: array<int, array<string, mixed>>, message: ?string}
     */
    protected function requestMyParcelRates(
        string $country,
        string $postcode,
        string $senderPostcode,
        string $receiverCountryCode,
        float $weight,
    ): array {
        $payload = [
            'sender_postcode' => $senderPostcode,
            'declared_weight' => $weight,
        ];

        if ($postcode !== '') {
            $payload['receiver_postcode'] = $postcode;
        }
        if ($country !== 'MY') {
            $payload['receiver_country_code'] = $receiverCountryCode;
        }

        $resp = $this->myParcel->checkPrice($payload);
        if (!$this->isMyParcelResponseOk($resp)) {
            $message = is_string($resp['message'] ?? null) ? $resp['message'] : 'MyParcel Asia rate check failed.';
            Log::warning('MyParcel Asia check_price returned no rates', [
                'payload' => $payload,
                'response' => $resp,
            ]);

            return ['rates' => [], 'message' => $message];
        }

        $prices = $resp['data']['prices'] ?? [];
        if (!is_array($prices)) {
            return ['rates' => [], 'message' => 'No shipping rates returned from MyParcel Asia.'];
        }

        $rates = collect($prices)->map(function ($p) {
            $providerCode = (string) ($p['provider_code'] ?? '');
            $label = (string) ($p['provider_label'] ?? ($p['provider_code'] ?? 'Courier'));
            $logo = $p['provider_logo'] ?? null;
            $transit = (string) ($p['transit_time'] ?? '');
            $effective = (float) ($p['effective_price'] ?? ($p['exclusive_price'] ?? ($p['normal_price'] ?? 0)));

            return [
                'source' => 'myparcelasia',
                'service_id' => $providerCode,
                'courier_name' => $label,
                'courier_logo' => is_string($logo) ? $logo : null,
                'service_name' => trim($label . ($transit ? (' - ' . $transit) : '')),
                'price' => $effective,
                'delivery' => $transit ?: 'N/A',
            ];
        })
            ->filter(fn ($r) => !empty($r['service_id']) && isset($r['price']) && (float) $r['price'] > 0)
            ->sortBy('price')
            ->values()
            ->toArray();

        if ($rates === []) {
            return ['rates' => [], 'message' => 'No shipping rates available for this location.'];
        }

        return ['rates' => $rates, 'message' => null];
    }

    protected function normalizeCountryCode(string $country): string
    {
        $normalized = strtoupper(trim($country));
        if (strlen($normalized) === 2 && ctype_alpha($normalized)) {
            return $normalized;
        }

        $map = [
            'MALAYSIA' => 'MY',
            'SINGAPORE' => 'SG',
            'BRUNEI' => 'BN',
            'BRUNEI DARUSSALAM' => 'BN',
            'INDONESIA' => 'ID',
        ];

        return $map[$normalized] ?? $map[str_replace(' ', '', $normalized)] ?? 'MY';
    }

    protected function isMyParcelResponseOk(?array $resp): bool
    {
        if (!is_array($resp)) {
            return false;
        }

        $status = $resp['status'] ?? null;
        if ($status === true || $status === 1 || $status === '1') {
            return true;
        }
        if (is_string($status) && in_array(strtolower($status), ['true', 'success', 'ok'], true)) {
            return true;
        }

        return filter_var($resp['success'] ?? false, FILTER_VALIDATE_BOOLEAN);
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

    /**
     * Book shipment via MyParcel Asia create_shipment API.
     * $data must contain: service_id (provider_code), weight, pick_*, send_* (sender/receiver).
     */
    public function bookShipmentMyParcel(Preorder $order, array $data): array
    {
        $weight = max(0.1, (float) ($data['weight'] ?? 1));
        $items = $order->items;
        $lineItem = [];
        if (!empty($items) && is_array($items)) {
            $numLines = count($items);
            $weightPerLine = $weight / max(1, $numLines);
            foreach ($items as $idx => $it) {
                $qty = (int) ($it['quantity'] ?? 1);
                $unitPrice = (float) ($it['unit_price'] ?? ($it['price'] ?? 0));
                $subTotal = $unitPrice * $qty;
                $lineItem[] = [
                    'product_id' => (int) ($it['product_id'] ?? $idx + 1),
                    'name' => (string) ($it['name'] ?? 'Item ' . ($idx + 1)),
                    'sku' => '',
                    'hscode' => '334221',
                    'duty_percent' => 0,
                    'duty_currency' => 'MYR',
                    'duty_amount' => 0,
                    'weight' => (string) round($weightPerLine, 2),
                    'sub_weight' => round($weightPerLine, 2),
                    'currency' => $order->currency ?? 'MYR',
                    'quantity' => $qty,
                    'price' => (string) round($unitPrice, 2),
                    'tax' => '0',
                    'sub_total' => (string) round($subTotal, 2),
                ];
            }
        }
        if (empty($lineItem)) {
            $lineItem[] = [
                'product_id' => (int) ($order->product_id ?? 0),
                'name' => 'Order #' . $order->order_number,
                'sku' => '',
                'hscode' => '334221',
                'duty_percent' => 0,
                'duty_currency' => 'MYR',
                'duty_amount' => 0,
                'weight' => (string) $weight,
                'sub_weight' => $weight,
                'currency' => $order->currency ?? 'MYR',
                'quantity' => (int) $order->quantity ?: 1,
                'price' => (string) round((float) $order->unit_price, 2),
                'tax' => '0',
                'sub_total' => (string) round((float) $order->total_amount, 2),
            ];
        }

        $integrationOrderData = [
            'connote_show_invoice' => 'yes',
            'total_weight' => $weight,
            'line_item' => $lineItem,
        ];

        $payload = [
            'integration_order_id' => (string) $order->id,
            'integration_order_data' => $integrationOrderData,
            'send_method' => $data['send_method'] ?? 'pickup',
            'send_date' => $data['send_date'] ?? date('Y-m-d', strtotime('+1 day')),
            'type' => 'parcel',
            'declared_weight' => $weight,
            'size' => $data['parcel_size'] ?? 'box',
            'width' => (int) ($data['width'] ?? 20),
            'length' => (int) ($data['length'] ?? 100),
            'height' => (int) ($data['height'] ?? 10),
            'provider_code' => (string) ($data['service_id'] ?? ''),
            'content_type' => $data['content_type'] ?? 'general',
            'content_description' => $data['content_description'] ?? 'Order ' . $order->order_number,
            'content_value' => (float) ($order->total_amount ?? 0),
            'has_cod' => ($data['has_cod'] ?? 'no') ? 'yes' : 'no',
            'has_sms' => ($data['has_sms'] ?? 'no') ? 'yes' : 'no',
            'sender_name' => (string) ($data['pick_name'] ?? config('app.name')),
            'sender_phone' => (string) ($data['pick_contact'] ?? ''),
            'sender_email' => (string) ($data['pick_email'] ?? config('mail.from.address')),
            'sender_company_name' => (string) ($data['pick_company'] ?? config('app.name')),
            'sender_address_line_1' => (string) ($data['pick_addr1'] ?? ''),
            'sender_address_line_2' => (string) ($data['pick_addr2'] ?? ''),
            'sender_address_line_3' => '',
            'sender_address_line_4' => '',
            'sender_postcode' => (string) ($data['pick_code'] ?? config('services.myparcelasia.sender_postcode', '88000')),
            'receiver_name' => (string) ($data['send_name'] ?? $order->name),
            'receiver_phone' => (string) ($data['send_contact'] ?? $order->phone),
            'receiver_email' => (string) ($order->email ?? ''),
            'receiver_address_line_1' => (string) ($data['send_addr1'] ?? $order->address),
            'receiver_address_line_2' => (string) ($data['send_addr2'] ?? ''),
            'receiver_address_line_3' => '',
            'receiver_address_line_4' => '',
            'receiver_postcode' => (string) ($data['send_code'] ?? ''),
        ];

        $resp = $this->myParcel->createShipment($payload);
        if (!empty($resp['status']) && $resp['status'] === true) {
            $d = $resp['data'] ?? [];
            $awb = $d['shipment_key'] ?? $d['tracking_number'] ?? $d['awb_no'] ?? null;
            $totalPrice = isset($d['total_price']) ? (float) $d['total_price'] : null;
            $msg = 'Shipment created via MyParcel Asia.';
            if ($awb) {
                $msg = 'Shipment booked successfully. Reference: ' . $awb;
            }
            if ($totalPrice !== null) {
                $msg .= ' Total: RM ' . number_format($totalPrice, 2);
            }
            return ['success' => true, 'awb' => $awb, 'message' => $msg, 'total_price' => $totalPrice];
        }
        $message = is_string($resp['message'] ?? null) ? $resp['message'] : 'MyParcel Asia create_shipment failed.';
        return ['success' => false, 'message' => $message];
    }

    /**
     * Auto-create MyParcel shipment when order/preorder is created with MyParcel shipping.
     * Adds shipment to MyParcel cart; admin can checkout later from Cart & Checkout.
     */
    public function createShipmentForPreorder(Preorder $preorder, array $data): ?string
    {
        $conf = config('services.myparcelasia', []);
        if (empty($conf['api_key'])) {
            \Illuminate\Support\Facades\Log::info('MyParcel auto-create skipped: no api_key');
            return null;
        }

        $serviceId = (string) ($preorder->shipping_service_id ?? $data['shipping_service_id'] ?? '');
        if ($serviceId === '' || $serviceId === 'self_collection') {
            return null;
        }

        $weight = max(0.1, (float) ($data['weight'] ?? max(1, (int) $preorder->quantity)));
        $payload = $this->buildCreateShipmentPayload($preorder, array_merge($data, ['weight' => $weight, 'service_id' => $serviceId]));
        if (!$payload) {
            \Illuminate\Support\Facades\Log::warning('MyParcel auto-create skipped: buildCreateShipmentPayload returned null', ['order' => $preorder->order_number]);
            return null;
        }

        \Illuminate\Support\Facades\Log::info('MyParcel auto-create attempt', ['order' => $preorder->order_number, 'provider_code' => $payload['provider_code'] ?? '']);

        $resp = $this->myParcel->createShipment($payload);

        $ok = !empty($resp['status']) && ($resp['status'] === true || $resp['status'] === 'success');
        if (!$ok && !empty($resp['success'])) {
            $ok = $resp['success'] === true || $resp['success'] === 'true';
        }
        if ($ok) {
            $d = $resp['data'] ?? $resp;
            $shipmentKey = $d['shipment_key'] ?? $d['key'] ?? $d['tracking_number'] ?? $d['awb_no'] ?? null;
            if ($shipmentKey) {
                \Illuminate\Support\Facades\Log::info('MyParcel auto-create success', ['order' => $preorder->order_number, 'shipment_key' => $shipmentKey]);
                return (string) $shipmentKey;
            }
        }

        \Illuminate\Support\Facades\Log::warning('MyParcel create_shipment failed on order create', [
            'order' => $preorder->order_number,
            'message' => $resp['message'] ?? 'Unknown',
            'response' => $resp,
        ]);
        return null;
    }

    protected function buildCreateShipmentPayload(Preorder $order, array $data): ?array
    {
        $weight = max(0.1, (float) ($data['weight'] ?? 1));
        $providerCode = (string) ($data['service_id'] ?? $order->shipping_service_id ?? '');
        if ($providerCode === '') {
            return null;
        }

        $items = $order->items;
        $lineItem = [];
        if (!empty($items) && is_array($items)) {
            $numLines = count($items);
            $weightPerLine = $weight / max(1, $numLines);
            foreach ($items as $idx => $it) {
                $qty = (int) ($it['quantity'] ?? 1);
                $unitPrice = (float) ($it['unit_price'] ?? ($it['price'] ?? 0));
                $subTotal = $unitPrice * $qty;
                $lineItem[] = [
                    'product_id' => (int) ($it['product_id'] ?? $order->product_id ?? $idx + 1),
                    'name' => (string) ($it['variant_name'] ?? $it['name'] ?? 'Item ' . ($idx + 1)),
                    'sku' => '',
                    'hscode' => '334221',
                    'duty_percent' => 0,
                    'duty_currency' => 'MYR',
                    'duty_amount' => 0,
                    'weight' => (string) round($weightPerLine, 2),
                    'sub_weight' => round($weightPerLine, 2),
                    'currency' => $order->currency ?? 'MYR',
                    'quantity' => $qty,
                    'price' => (string) round($unitPrice, 2),
                    'tax' => '0',
                    'sub_total' => (string) round($subTotal, 2),
                ];
            }
        }
        if (empty($lineItem)) {
            $lineItem[] = [
                'product_id' => (int) ($order->product_id ?? 0),
                'name' => 'Order #' . $order->order_number,
                'sku' => '',
                'hscode' => '334221',
                'duty_percent' => 0,
                'duty_currency' => 'MYR',
                'duty_amount' => 0,
                'weight' => (string) $weight,
                'sub_weight' => $weight,
                'currency' => $order->currency ?? 'MYR',
                'quantity' => (int) $order->quantity ?: 1,
                'price' => (string) round((float) $order->unit_price, 2),
                'tax' => '0',
                'sub_total' => (string) round((float) $order->total_amount, 2),
            ];
        }

        $postcode = (string) ($data['postal_code'] ?? '');
        if ($postcode === '') {
            $postcode = preg_match('/\b(\d{5})\b/', $order->address ?? '', $m) ? $m[1] : config('services.myparcelasia.sender_postcode', '88000');
        }

        return [
            'integration_order_id' => (string) $order->id,
            'integration_order_data' => [
                'connote_show_invoice' => 'yes',
                'total_weight' => $weight,
                'line_item' => $lineItem,
            ],
            'send_method' => 'pickup',
            'send_date' => date('Y-m-d', strtotime('+1 day')),
            'type' => 'parcel',
            'declared_weight' => $weight,
            'size' => 'box',
            'width' => 20,
            'length' => 100,
            'height' => 10,
            'provider_code' => $providerCode,
            'content_type' => 'general',
            'content_description' => 'Order ' . $order->order_number,
            'content_value' => (float) ($order->total_amount ?? 0),
            'has_cod' => 'no',
            'has_sms' => 'no',
            'sender_name' => config('app.name'),
            'sender_phone' => (string) (config('services.myparcelasia.sender_phone') ?? '0143436496'),
            'sender_email' => config('mail.from.address'),
            'sender_company_name' => config('app.name'),
            'sender_address_line_1' => 'LOT 1-35, 1ST FLOOR, SURIA SABAH SHOPPING MALL',
            'sender_address_line_2' => 'JALAN TUN FUAD',
            'sender_address_line_3' => '',
            'sender_address_line_4' => '',
            'sender_postcode' => (string) (config('services.myparcelasia.sender_postcode') ?? '88000'),
            'receiver_name' => (string) $order->name,
            'receiver_phone' => (string) $order->phone,
            'receiver_email' => (string) ($order->email ?? ''),
            'receiver_address_line_1' => (string) $order->address,
            'receiver_address_line_2' => '',
            'receiver_address_line_3' => '',
            'receiver_address_line_4' => '',
            'receiver_postcode' => $postcode,
        ];
    }

    /**
     * Create a standalone shipment (no Preorder). For admin "Create Shipment" form.
     * $data: sender_*, receiver_*, weight, provider_code, parcel_size, content_type, content_description, content_value, send_date, send_method, etc.
     */
    public function createShipmentStandalone(array $data): array
    {
        $weight = max(0.1, (float) ($data['declared_weight'] ?? $data['weight'] ?? 1));
        $contentValue = (float) ($data['content_value'] ?? 0);
        $integrationOrderId = 'manual-' . time();

        $lineItem = [
            'product_id' => 0,
            'name' => (string) ($data['line_item_name'] ?? 'Shipment'),
            'sku' => '',
            'hscode' => '334221',
            'duty_percent' => 0,
            'duty_currency' => 'MYR',
            'duty_amount' => 0,
            'weight' => (string) $weight,
            'sub_weight' => $weight,
            'currency' => 'MYR',
            'quantity' => 1,
            'price' => (string) $contentValue,
            'tax' => '0',
            'sub_total' => (string) $contentValue,
        ];

        $payload = [
            'integration_order_id' => $integrationOrderId,
            'integration_order_data' => [
                'connote_show_invoice' => 'yes',
                'total_weight' => $weight,
                'line_item' => [$lineItem],
            ],
            'send_method' => $data['send_method'] ?? 'pickup',
            'send_date' => $data['send_date'] ?? date('Y-m-d', strtotime('+1 day')),
            'type' => 'parcel',
            'declared_weight' => $weight,
            'size' => $data['parcel_size'] ?? 'box',
            'width' => (int) ($data['width'] ?? 20),
            'length' => (int) ($data['length'] ?? 100),
            'height' => (int) ($data['height'] ?? 10),
            'provider_code' => (string) ($data['provider_code'] ?? ''),
            'content_type' => $data['content_type'] ?? 'general',
            'content_description' => (string) ($data['content_description'] ?? 'Shipment'),
            'content_value' => $contentValue,
            'has_cod' => !empty($data['has_cod']) ? 'yes' : 'no',
            'has_sms' => !empty($data['has_sms']) ? 'yes' : 'no',
            'sender_name' => (string) ($data['sender_name'] ?? ''),
            'sender_phone' => (string) ($data['sender_phone'] ?? ''),
            'sender_email' => (string) ($data['sender_email'] ?? config('mail.from.address')),
            'sender_company_name' => (string) ($data['sender_company_name'] ?? config('app.name')),
            'sender_address_line_1' => (string) ($data['sender_address_line_1'] ?? ''),
            'sender_address_line_2' => (string) ($data['sender_address_line_2'] ?? ''),
            'sender_address_line_3' => '',
            'sender_address_line_4' => '',
            'sender_postcode' => (string) ($data['sender_postcode'] ?? config('services.myparcelasia.sender_postcode', '88000')),
            'receiver_name' => (string) ($data['receiver_name'] ?? ''),
            'receiver_phone' => (string) ($data['receiver_phone'] ?? ''),
            'receiver_email' => (string) ($data['receiver_email'] ?? ''),
            'receiver_address_line_1' => (string) ($data['receiver_address_line_1'] ?? ''),
            'receiver_address_line_2' => (string) ($data['receiver_address_line_2'] ?? ''),
            'receiver_address_line_3' => '',
            'receiver_address_line_4' => '',
            'receiver_postcode' => (string) ($data['receiver_postcode'] ?? ''),
        ];

        $resp = $this->myParcel->createShipment($payload);
        if (!empty($resp['status']) && $resp['status'] === true) {
            $d = $resp['data'] ?? [];
            $awb = $d['shipment_key'] ?? $d['tracking_number'] ?? $d['awb_no'] ?? null;
            $totalPrice = isset($d['total_price']) ? (float) $d['total_price'] : null;
            return [
                'success' => true,
                'awb' => $awb,
                'total_price' => $totalPrice,
                'data' => $d,
                'message' => 'Shipment created. ' . ($totalPrice !== null ? 'Total: RM ' . number_format($totalPrice, 2) . '. ' : '') . ($awb ? 'Reference: ' . $awb : ''),
            ];
        }
        $message = is_string($resp['message'] ?? null) ? $resp['message'] : 'Create shipment failed.';
        return ['success' => false, 'message' => $message];
    }

    public function track(string $trackingNumber): array
    {
        return $this->easyParcel->trackParcel($trackingNumber);
    }
}
