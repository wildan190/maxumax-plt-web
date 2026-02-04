<?php

namespace App\Http\Controllers;

use App\Services\EasyParcelService;
use App\Services\DelyvaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    protected $easyParcel;
    protected $delyva;

    public function __construct(EasyParcelService $easyParcel, DelyvaService $delyva)
    {
        $this->easyParcel = $easyParcel;
        $this->delyva = $delyva;
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

        $weight = $request->input('weight');

        // Calculate weight from items if not provided directly
        if (!$weight && $request->has('items')) {
            $items = $request->input('items');
            $totalQty = 0;
            foreach ($items as $item) {
                // Handle different structures (cart vs preorder form)
                $qty = 0;
                if (isset($item['quantity'])) {
                    $qty = (int) $item['quantity'];
                } elseif (isset($item['quantity_ss']) || isset($item['quantity_ls'])) {
                    $qty = ((int) ($item['quantity_ss'] ?? 0)) + ((int) ($item['quantity_ls'] ?? 0));
                }
                $totalQty += $qty;
            }
            // Estimate: 0.5kg per jersey
            $weight = max(1, $totalQty * 0.5);
        }

        // Default weight if still empty
        if (!$weight) {
            $weight = 1;
        }

        $params = [
            'pick_code' => '88000', // Shop Postcode
            'pick_state' => 'Sabah', // Shop State
            'pick_country' => 'MY', // Shop Country
            'send_code' => $request->postcode,
            'send_state' => $request->state,
            'send_country' => $request->country,
            'weight' => $weight,
        ];

        try {
            $result = $this->easyParcel->checkRate($params);
            $easyRates = [];
            if (isset($result['api_status']) && $result['api_status'] === 'Success') {
                $easyRates = $result['result'][0]['rates'] ?? [];
            }
            $formattedEasy = collect($easyRates)->map(function($rate) {
                return [
                    'source' => 'easyparcel',
                    'service_id' => $rate['service_id'],
                    'courier_name' => $rate['courier_name'],
                    'courier_logo' => $rate['courier_logo'] ?? null,
                    'service_name' => $rate['service_name'],
                    'price' => (float) $rate['price'],
                    'delivery_period' => $rate['delivery'] ?? 'N/A',
                ];
            });

            $origin = [
                'address1' => 'Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens',
                'postcode' => '88000',
                'state' => 'Sabah',
                'city' => 'Kota Kinabalu',
                'country' => 'MY',
            ];
            $destination = [
                'address1' => '',
                'postcode' => $request->postcode,
                'state' => $request->state,
                'city' => $request->input('city'),
                'country' => $request->country,
            ];
            $items = [
                [
                    'name' => 'Jersey',
                    'quantity' => max(1, (int) ($request->input('items.0.quantity') ?? 1)),
                    'weight' => ['unit' => 'kg', 'value' => $weight],
                ]
            ];
            $delyvaQuote = $this->delyva->quote($origin, $destination, $items);
            $formattedDelyva = collect([]);
            if (!empty($delyvaQuote['data'])) {
                $list = is_array($delyvaQuote['data']) ? $delyvaQuote['data'] : [];
                $formattedDelyva = collect($list)->map(function($q) {
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
                        'delivery_period' => $q['estimatedDelivery'] ?? ($q['delivery'] ?? 'N/A'),
                    ];
                });
            }

            $merged = $formattedEasy->merge($formattedDelyva)->filter(function($r) {
                return isset($r['service_id']) && isset($r['price']);
            })->sortBy('price')->values();

            if ($merged->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No rates available'], 404);
            }
            return response()->json(['success' => true, 'rates' => $merged]);
        } catch (\Exception $e) {
            Log::error('Shipping Rate Check Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal server error'], 500);
        }
    }
}
