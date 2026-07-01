<?php

namespace App\Services\Shipping;

use App\Services\MyParcelAsiaService;
use App\Services\ShippingService;
use Illuminate\Http\Request;

/**
 * JSON/HTTP orchestration for public + admin shipping endpoints (MyParcel Asia + rate checks).
 */
class ShippingIntegrationHttpService
{
    public function __construct(
        protected ShippingService $shipping,
        protected MyParcelAsiaService $myParcel,
    ) {}

    public function myparcelDashboardView()
    {
        return view('admin.shipping.myparcel');
    }

    public function myparcelParcelSizes()
    {
        $resp = $this->myParcel->getParcelSizes([]);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }

        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to fetch parcel sizes'], 422);
    }

    public function myparcelContentTypes()
    {
        $resp = $this->myParcel->getContentTypes([]);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }

        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to fetch content types'], 422);
    }

    public function myparcelSddPrice(Request $request)
    {
        $request->validate([
            'pickup_address' => 'required|string',
            'pickup_postcode' => 'required|string',
            'receiver_address' => 'required|string',
            'receiver_postcode' => 'required|string',
            'declared_weight' => 'required|numeric|min:0.1',
            'pickup_lat' => 'nullable|string',
            'pickup_lng' => 'nullable|string',
            'receiver_lat' => 'nullable|string',
            'receiver_lng' => 'nullable|string',
        ]);
        $params = [
            'pickup_address' => $request->pickup_address,
            'pickup_postcode' => $request->pickup_postcode,
            'receiver_address' => $request->receiver_address,
            'receiver_postcode' => $request->receiver_postcode,
            'declared_weight' => (float) $request->declared_weight,
        ];
        if ($request->filled('pickup_lat')) {
            $params['pickup_lat'] = $request->pickup_lat;
        }
        if ($request->filled('pickup_lng')) {
            $params['pickup_lng'] = $request->pickup_lng;
        }
        if ($request->filled('receiver_lat')) {
            $params['receiver_lat'] = $request->receiver_lat;
        }
        if ($request->filled('receiver_lng')) {
            $params['receiver_lng'] = $request->receiver_lng;
        }

        $resp = $this->myParcel->sddPrice($params);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? [], 'prices' => $resp['data']['prices'] ?? []]);
        }

        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to get SDD price'], 422);
    }

    public function myparcelCartItems()
    {
        $resp = $this->myParcel->getCartItems([]);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }

        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to fetch cart items'], 422);
    }

    public function myparcelCheckout(Request $request)
    {
        $request->validate([
            'shipment_keys' => 'required|array',
            'shipment_keys.*' => 'string',
        ]);
        $keys = array_values(array_filter($request->input('shipment_keys', [])));
        if ($keys === []) {
            return response()->json(['success' => false, 'message' => 'At least one shipment_key is required'], 422);
        }
        $resp = $this->myParcel->checkoutByShipmentKeys($keys);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }

        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Checkout failed'], 422);
    }

    public function myparcelShipmentStatuses()
    {
        $resp = $this->myParcel->getShipmentStatuses([]);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }

        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to fetch shipment statuses'], 422);
    }

    public function myparcelTrace(Request $request)
    {
        $request->validate([
            'tracking_no' => 'required|string|max:100',
        ]);
        $resp = $this->myParcel->trace(['tracking_no' => $request->input('tracking_no')]);
        $ok = !empty($resp['status']) && ($resp['status'] === true || $resp['status'] === 'success');
        if ($ok) {
            return response()->json([
                'success' => true,
                'message' => $resp['message'] ?? 'success',
                'data' => $resp['data'] ?? [],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $resp['message'] ?? 'Trace failed',
            'data' => $resp['data'] ?? [],
        ], 422);
    }

    public function myparcelShipmentHistory(Request $request)
    {
        $params = [];
        if ($request->filled('page')) {
            $params['page'] = (int) $request->input('page');
        }
        if ($request->filled('item_per_page')) {
            $params['item_per_page'] = (int) $request->input('item_per_page');
        }
        $resp = $this->myParcel->getShipmentHistory($params);
        $ok = !empty($resp['status']) && ($resp['status'] === true || $resp['status'] === 'success');
        if ($ok) {
            return response()->json([
                'success' => true,
                'message' => $resp['message'] ?? 'success',
                'data' => $resp['data'] ?? ['shipments' => [], 'pagination' => []],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $resp['message'] ?? 'Failed to fetch shipment history',
            'data' => $resp['data'] ?? [],
        ], 422);
    }

    public function myparcelConsignmentNote(Request $request)
    {
        $request->validate([
            'tracking_no' => 'required|array',
            'tracking_no.*' => 'string|max:50',
        ]);
        $trackingNos = array_values(array_filter($request->input('tracking_no', [])));
        if ($trackingNos === []) {
            return response()->json(['success' => false, 'message' => 'At least one tracking_no is required'], 422);
        }
        $resp = $this->myParcel->getConsignmentNote(['tracking_no' => $trackingNos]);
        $ok = !empty($resp['status']) && ($resp['status'] === true || $resp['status'] === 'success');
        if ($ok) {
            return response()->json([
                'success' => true,
                'message' => $resp['message'] ?? 'success',
                'data' => $resp['data'] ?? $resp,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $resp['message'] ?? 'Failed to get consignment note',
            'data' => $resp['data'] ?? [],
        ], 422);
    }

    public function myparcelCreateShipment(Request $request)
    {
        $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:255',
            'sender_email' => 'nullable|email',
            'sender_address_line_1' => 'required|string',
            'sender_postcode' => 'required|string|max:20',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:255',
            'receiver_email' => 'nullable|email',
            'receiver_address_line_1' => 'required|string',
            'receiver_postcode' => 'required|string|max:20',
            'declared_weight' => 'required|numeric|min:0.1',
            'provider_code' => 'required|string|max:50',
            'parcel_size' => 'nullable|string|max:50',
            'content_type' => 'nullable|string|max:50',
            'content_description' => 'nullable|string',
            'content_value' => 'nullable|numeric|min:0',
            'send_date' => 'nullable|date',
            'send_method' => 'nullable|string|in:pickup,dropoff',
            'sender_address_line_2' => 'nullable|string',
            'sender_company_name' => 'nullable|string',
            'receiver_address_line_2' => 'nullable|string',
        ]);
        $res = $this->shipping->createShipmentStandalone($request->all());
        if ($res['success']) {
            return response()->json([
                'success' => true,
                'message' => $res['message'],
                'total_price' => $res['total_price'] ?? null,
                'awb' => $res['awb'] ?? null,
                'data' => $res['data'] ?? [],
            ]);
        }

        return response()->json(['success' => false, 'message' => $res['message']], 422);
    }

    public function checkRates(Request $request)
    {
        $request->validate([
            'postcode' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'weight' => 'nullable|numeric',
            'items' => 'nullable|array',
        ]);

        $postcode = (string) $request->input('postcode');
        $country = (string) $request->input('country', 'MY');

        $items = $request->input('items', []);
        $totalQty = 0;
        if (is_array($items)) {
            foreach ($items as $it) {
                if (is_array($it)) {
                    $totalQty += (int) ($it['quantity'] ?? 0);
                }
            }
        }

        $defaultWeight = (float) (config('services.myparcelasia.default_weight') ?? 2);
        $weight = (float) ($request->input('weight') ?? 0);
        if ($weight <= 0) {
            $weight = $totalQty > 0 ? max(1.0, (float) $totalQty) : $defaultWeight;
        }

        if (empty(config('services.myparcelasia.api_key'))) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping rates are temporarily unavailable. Please contact us.',
            ], 200);
        }

        $result = $this->shipping->fetchMyParcelRates([
            'postcode' => $postcode,
            'country' => $country,
            'weight' => $weight,
        ]);

        if (!empty($result['rates'])) {
            return response()->json(['success' => true, 'rates' => $result['rates']]);
        }

        return response()->json([
            'success' => false,
            'message' => strtoupper((string) ($result['message'] ?? 'NO SHIPPING RATES AVAILABLE FOR THIS LOCATION.')),
        ], 200);
    }

    public function envShippingCheck()
    {
        $easy = config('services.easyparcel');
        $mask = function ($v) {
            if (!$v) {
                return null;
            }
            $s = (string) $v;
            $len = strlen($s);
            if ($len <= 6) {
                return str_repeat('*', $len);
            }

            return substr($s, 0, 3) . str_repeat('*', max(0, $len - 7)) . substr($s, -4);
        };
        $epProd = !empty($easy['is_production']);
        $epBase = $epProd ? 'https://connect.easyparcel.my' : 'http://demo.connect.easyparcel.my';

        return response()->json([
            'easyparcel' => [
                'api_key_present' => !empty($easy['api_key']),
                'api_key_masked' => $mask($easy['api_key'] ?? null),
                'is_production' => (bool) $epProd,
                'base_url' => $epBase,
            ],
            'myparcelasia' => [
                'api_key_present' => !empty(config('services.myparcelasia.api_key')),
                'sender_postcode' => config('services.myparcelasia.sender_postcode'),
                'is_production' => (bool) config('services.myparcelasia.is_production'),
                'base_url' => (bool) config('services.myparcelasia.is_production') ? config('services.myparcelasia.base_url_prod') : config('services.myparcelasia.base_url_dev'),
            ],
        ]);
    }
}
