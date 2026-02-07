<?php

namespace App\Http\Controllers;

use App\Services\EasyParcelService;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    protected $easyParcel;
 

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

    public function __construct(EasyParcelService $easyParcel)
    {
        $this->easyParcel = $easyParcel;
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

        $flatRate = [
            'source' => 'easyparcel',
            'service_id' => 'ep_flat',
            'courier_name' => 'EasyParcel',
            'courier_logo' => null,
            'service_name' => 'Flat Rate',
            'price' => 12.0,
            'delivery_period' => 'N/A',
        ];
        return response()->json(['success' => true, 'rates' => collect([$flatRate])]);
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
        ]);
    }
}
