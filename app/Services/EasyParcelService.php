<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EasyParcelService
{
    protected $apiKey;
    protected $baseUrl;
    protected $prodUrl;
    protected $isProduction;

    public function __construct()
    {
        $this->apiKey = config('services.easyparcel.api_key');
        $this->isProduction = (bool) config('services.easyparcel.is_production');
        $this->baseUrl = $this->isProduction ? 'https://connect.easyparcel.my/?ac=' : 'http://demo.connect.easyparcel.my/?ac=';
        $this->prodUrl = 'https://connect.easyparcel.my/?ac=';
    }

    /**
     * Make a request to EasyParcel API
     * 
     * @param string $action The API action (e.g., EPRateCheckingBulk)
     * @param array $data The data to send (will be wrapped in 'bulk' array)
     * @return array
     */
    protected function makeRequest($action, $data = [])
    {
        // Increase execution time to handle slow API responses
        set_time_limit(120);

        $payload = [
            'api' => $this->apiKey,
        ];

        if (!empty($data)) {
            // EasyParcel expects 'bulk' to be an array of objects
            // If $data is associative (single item), wrap it
            // If $data is sequential (multiple items), use as is
            $payload['bulk'] = array_is_list($data) ? $data : [$data];
        }

        try {
            $response = Http::timeout(12)->asForm()->post($this->baseUrl . $action, $payload);
            if ($response->failed()) {
                Log::error('EasyParcel API Request Failed', ['action' => $action, 'status' => $response->status(), 'body' => $response->body()]);
                if (!$this->isProduction) {
                    $fallback = Http::timeout(12)->asForm()->post($this->prodUrl . $action, $payload);
                    if (!$fallback->failed()) {
                        return $fallback->json();
                    }
                    Log::error('EasyParcel API Fallback Failed', ['action' => $action, 'status' => $fallback->status(), 'body' => $fallback->body()]);
                }
                return ['api_status' => 'Error', 'result' => 'Request failed'];
            }
            return $response->json();
        } catch (\Exception $e) {
            Log::error('EasyParcel API Exception: ' . $e->getMessage());
            if (!$this->isProduction) {
                try {
                    $fallback = Http::timeout(12)->asForm()->post($this->prodUrl . $action, $payload);
                    if (!$fallback->failed()) {
                        return $fallback->json();
                    }
                    Log::error('EasyParcel API Fallback Exception', ['action' => $action, 'error' => $fallback->body()]);
                } catch (\Exception $e2) {
                    Log::error('EasyParcel API Fallback Exception: ' . $e2->getMessage());
                }
            }
            return ['api_status' => 'Error', 'result' => $e->getMessage()];
        }
    }

    /**
     * Check shipping rates
     * 
     * @param array $params
     * @return array
     */
    public function checkRate($params)
    {
        // Required: pick_code, pick_state, pick_country, send_code, send_state, send_country, weight
        return $this->makeRequest('EPRateCheckingBulk', $params);
    }

    /**
     * Submit an order (Create Shipment)
     * 
     * @param array $orderData
     * @return array
     */
    public function submitOrder($orderData)
    {
        return $this->makeRequest('EPSubmitOrderBulk', $orderData);
    }

    /**
     * Check Order Status
     * 
     * @param string $orderNumber
     * @return array
     */
    public function checkOrderStatus($orderNumber)
    {
        return $this->makeRequest('EPOrderStatusBulk', ['order_no' => $orderNumber]);
    }

    /**
     * Track Parcel
     * 
     * @param string $trackingNumber
     * @return array
     */
    public function trackParcel($trackingNumber)
    {
        return $this->makeRequest('EPTrackingBulk', ['awb_no' => $trackingNumber]);
    }
}
