<?php

namespace App\Http\Controllers;

use App\Services\MyParcelAsiaService;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected ShippingService $shipping;
    protected MyParcelAsiaService $myParcel;

    private function deriveStateFromPostcode(?string $postcode): ?string
    {
        if (!$postcode) return null;
        $pc = preg_replace('/\D/', '', $postcode);
        if (preg_match('/^(88|89)\d{3}$/', $pc)) {
            return 'Sabah';
        }
        if (preg_match('/^(93|94|95|96|97|98)\d{3}$/', $pc)) {
            return 'Sarawak';
        }
        return null;
    }

    public function __construct(ShippingService $shipping, MyParcelAsiaService $myParcel)
    {
        $this->shipping = $shipping;
        $this->myParcel = $myParcel;
    }

    /**
     * MyParcel Asia: admin dashboard UI.
     * GET /admin/shipping/myparcel
     */
    public function myparcelDashboard()
    {
        return view('admin.shipping.myparcel');
    }

    /**
     * MyParcel Asia: get parcel sizes (for dropdown).
     * GET /admin/shipping/myparcel/parcel-sizes
     */
    public function myparcelParcelSizes()
    {
        $resp = $this->myParcel->getParcelSizes([]);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }
        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to fetch parcel sizes'], 422);
    }

    /**
     * MyParcel Asia: get content types (for dropdown).
     * GET /admin/shipping/myparcel/content-types
     */
    public function myparcelContentTypes()
    {
        $resp = $this->myParcel->getContentTypes([]);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }
        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to fetch content types'], 422);
    }

    /**
     * MyParcel Asia: same-day delivery price quote.
     * POST /admin/shipping/myparcel/sdd-price
     * Body: pickup_address, pickup_postcode, pickup_lat, pickup_lng, receiver_address, receiver_postcode, receiver_lat, receiver_lng, declared_weight
     */
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
        if ($request->filled('pickup_lat')) $params['pickup_lat'] = $request->pickup_lat;
        if ($request->filled('pickup_lng')) $params['pickup_lng'] = $request->pickup_lng;
        if ($request->filled('receiver_lat')) $params['receiver_lat'] = $request->receiver_lat;
        if ($request->filled('receiver_lng')) $params['receiver_lng'] = $request->receiver_lng;

        $resp = $this->myParcel->sddPrice($params);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? [], 'prices' => $resp['data']['prices'] ?? []]);
        }
        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to get SDD price'], 422);
    }

    /**
     * MyParcel Asia: get cart items (shipments in cart).
     * GET /admin/shipping/myparcel/cart-items
     */
    public function myparcelCartItems()
    {
        $resp = $this->myParcel->getCartItems([]);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }
        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to fetch cart items'], 422);
    }

    /**
     * MyParcel Asia: checkout with shipment keys.
     * POST /admin/shipping/myparcel/checkout
     * Body: shipment_keys (array of strings, e.g. from get_cart_items item.key)
     */
    public function myparcelCheckout(Request $request)
    {
        $request->validate([
            'shipment_keys' => 'required|array',
            'shipment_keys.*' => 'string',
        ]);
        $keys = array_values(array_filter($request->input('shipment_keys', [])));
        if (empty($keys)) {
            return response()->json(['success' => false, 'message' => 'At least one shipment_key is required'], 422);
        }
        $resp = $this->myParcel->checkoutByShipmentKeys($keys);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }
        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Checkout failed'], 422);
    }

    /**
     * MyParcel Asia: get shipment status labels (code => label).
     * GET /admin/shipping/myparcel/shipment-statuses
     */
    public function myparcelShipmentStatuses()
    {
        $resp = $this->myParcel->getShipmentStatuses([]);
        if (!empty($resp['status']) && $resp['status'] === true) {
            return response()->json(['success' => true, 'data' => $resp['data'] ?? []]);
        }
        return response()->json(['success' => false, 'message' => $resp['message'] ?? 'Failed to fetch shipment statuses'], 422);
    }

    /**
     * MyParcel Asia: create a standalone shipment (create order).
     * POST /admin/shipping/myparcel/create-shipment
     * Returns success, total_price, awb, data, message.
     */
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
        $country = strtoupper((string) $request->input('country', 'MY'));

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

        $myparcelKeyPresent = !empty(config('services.myparcelasia.api_key'));
        if ($myparcelKeyPresent) {
            $rates = $this->shipping->getRatesMyParcel([
                'postcode' => $postcode,
                'country' => $country,
                'weight' => $weight,
            ]);
            if (!empty($rates)) {
                return response()->json(['success' => true, 'rates' => $rates]);
            }
            return response()->json(['success' => false, 'message' => 'NO SHIPPING RATES AVAILABLE FOR THIS LOCATION.'], 200);
        }

        // Fallback: if MyParcel is not configured, keep a predictable response.
        $fallback = [[
            'source' => 'fallback',
            'service_id' => 'flat',
            'courier_name' => 'Shipping',
            'courier_logo' => null,
            'service_name' => 'Flat Rate',
            'price' => 12.0,
            'delivery' => 'N/A',
        ]];
        return response()->json(['success' => true, 'rates' => $fallback]);
    }

    public function envShippingCheck()
    {
        $easy = config('services.easyparcel');
        $mask = function ($v) {
            if (!$v) return null;
            $s = (string) $v;
            $len = strlen($s);
            if ($len <= 6) return str_repeat('*', $len);
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
