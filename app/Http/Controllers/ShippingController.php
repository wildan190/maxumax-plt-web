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

        $sendState = $request->state ?: $this->deriveStateFromPostcode($request->postcode) ?: 'Sabah';
        $params = [
            'pick_code' => '88000', // Shop Postcode
            'pick_state' => 'Sabah', // Shop State
            'pick_country' => 'MY', // Shop Country
            'send_code' => $request->postcode,
            'send_state' => $sendState,
            'send_country' => $request->country,
            'weight' => $weight,
        ];

        try {
            $result = $this->easyParcel->checkRate($params);
            $easyRates = [];
            $easyError = null;
            if (isset($result['api_status']) && $result['api_status'] === 'Success') {
                $easyRates = $result['result'][0]['rates'] ?? [];
            } else {
                $easyError = $result['error_remark'] ?? ($result['result'] ?? 'Unknown EasyParcel error');
                Log::warning('EasyParcel Rate Error: ' . $easyError);
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

            if ($formattedEasy->isEmpty() && $sendState !== 'Sabah') {
                $retryParams = $params;
                $retryParams['send_state'] = 'Sabah';
                $retry = $this->easyParcel->checkRate($retryParams);
                if (isset($retry['api_status']) && $retry['api_status'] === 'Success') {
                    $easyRates = $retry['result'][0]['rates'] ?? [];
                    $easyError = null; // Clear error if retry succeeds
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
                }
            }

            if (!$formattedEasy->isEmpty()) {
                return response()->json(['success' => true, 'rates' => $formattedEasy->sortBy('price')->values()]);
            }

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
            $delyvaError = null;

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
            } else {
                $delyvaError = $delyvaQuote['message'] ?? ($delyvaQuote['error']['message'] ?? 'Unknown Delyva error');
                Log::warning('Delyva Rate Error: ' . json_encode($delyvaQuote));
            }

            $merged = $formattedEasy->merge($formattedDelyva)->filter(function($r) {
                return isset($r['service_id']) && isset($r['price']);
            })->sortBy('price')->values();

            // Append MyParcelAsia option (no rate quote, selectable for checkout)
            $mpaConf = config('services.myparcelasia');
            if (!empty($mpaConf['api_key'])) {
                $merged->push([
                    'source' => 'myparcelasia',
                    'service_id' => 'mpa_checkout',
                    'courier_name' => 'MyParcelAsia',
                    'courier_logo' => null,
                    'service_name' => 'Checkout',
                    'price' => 0.0,
                    'delivery_period' => 'N/A',
                ]);
            }

            if ($merged->isEmpty()) {
                $msg = 'No rates available.';
                if ($easyError) $msg .= " EasyParcel: $easyError.";
                if ($delyvaError) $msg .= " Delyva: $delyvaError.";
                return response()->json(['success' => false, 'message' => $msg], 404);
            }
            return response()->json(['success' => true, 'rates' => $merged]);
        } catch (\Exception $e) {
            Log::error('Shipping Rate Check Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function envShippingCheck()
    {
        $easy = config('services.easyparcel');
        $delyva = config('services.delyva');
        $mpa = config('services.myparcelasia');
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
            'delyva' => [
                'base_url' => $delyva['base_url'] ?? null,
                'access_token_present' => !empty($delyva['access_token']) || !empty($delyva['api_key']),
                'access_token_masked' => $mask($delyva['access_token'] ?? ($delyva['api_key'] ?? null)),
                'company_code' => $delyva['company_code'] ?? null,
                'company_id_present' => !empty($delyva['company_id']),
                'customer_id_present' => !empty($delyva['customer_id']),
                'user_id_present' => !empty($delyva['user_id']),
            ],
            'myparcelasia' => [
                'api_key_present' => !empty($mpa['api_key']),
                'api_key_masked' => $mask($mpa['api_key'] ?? null),
                'is_production' => (bool) ($mpa['is_production'] ?? true),
                'base_url' => (($mpa['is_production'] ?? true) ? ($mpa['base_url_prod'] ?? null) : ($mpa['base_url_dev'] ?? null)),
            ],
        ]);
    }
}
