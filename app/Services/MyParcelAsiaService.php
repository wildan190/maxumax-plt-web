<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MyParcelAsiaService
{
    protected string $apiKey;
    protected ?string $apiSecret;
    protected string $baseUrl;

    public function __construct()
    {
        $conf = config('services.myparcelasia');
        $this->apiKey = (string) ($conf['api_key'] ?? '');
        $this->apiSecret = $conf['api_secret'] ?? null;
        $isProd = (bool) ($conf['is_production'] ?? true);
        $this->baseUrl = rtrim($isProd ? ($conf['base_url_prod'] ?? 'https://app.myparcelasia.com/apiv2') : ($conf['base_url_dev'] ?? 'https://demo.myparcelasia.com/apiv2'), '/');
    }

    protected function post(string $endpoint, array $params = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        $payload = ['api_key' => $this->apiKey] + $params;
        try {
            // MyParcel endpoints are often documented with JSON bodies.
            // To be resilient, try JSON first, then fallback to form-encoded.
            $resp = Http::timeout(12)->asJson()->post($url, $payload);
            if ($resp->failed() || $this->looksLikeAuthMissing($resp->json())) {
                $resp = Http::timeout(12)->asForm()->post($url, $payload);
            }
            if ($resp->failed()) {
                Log::error('MyParcelAsia API Request Failed', ['endpoint' => $endpoint, 'status' => $resp->status(), 'body' => $resp->body()]);
                return ['status' => false, 'message' => 'Request failed', 'error' => $resp->json()];
            }
            return $resp->json();
        } catch (\Throwable $e) {
            Log::error('MyParcelAsia API Exception: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    protected function looksLikeAuthMissing($json): bool
    {
        if (!is_array($json)) return false;
        $msg = strtolower((string) ($json['message'] ?? ''));
        return str_contains($msg, 'no auth key') || str_contains($msg, 'please include');
    }

    public function checkout(array $order, array $shipments): array
    {
        $params = [
            'order' => $order,
            'shipments' => $shipments,
        ];
        return $this->post('checkout', $params);
    }

    /**
     * Checkout cart by shipment keys (from get_cart_items).
     * Body: shipment_keys (array of key strings).
     */
    public function checkoutByShipmentKeys(array $shipmentKeys): array
    {
        return $this->post('checkout', ['shipment_keys' => $shipmentKeys]);
    }

    public function user(array $params = []): array
    {
        return $this->post('user', $params);
    }

    public function getPostcodeDetails(array $params): array
    {
        return $this->post('get_postcode_details', $params);
    }

    public function checkPrice(array $params): array
    {
        return $this->post('check_price', $params);
    }

    public function sddPrice(array $params): array
    {
        return $this->post('sdd_price', $params);
    }

    public function getParcelSizes(array $params = []): array
    {
        return $this->post('get_parcel_sizes', $params);
    }

    public function getContentTypes(array $params = []): array
    {
        return $this->post('get_content_types', $params);
    }

    public function createShipment(array $params): array
    {
        return $this->post('create_shipment', $params);
    }

    public function getCartItems(array $params = []): array
    {
        return $this->post('get_cart_items', $params);
    }

    public function getShipmentStatuses(array $params = []): array
    {
        return $this->post('get_shipment_statuses', $params);
    }

    /**
     * Get shipment history (list of past shipments with optional pagination).
     * ENDPOINT: POST /get_shipment_history
     * Request: api_key (optional: page, item_per_page)
     * Response: { "status": true, "message": "success", "data": { "shipments": [...], "pagination": { "current_page", "total_item", "item_per_page", "total_page", "next_page", "prev_page" } } }
     */
    public function getShipmentHistory(array $params = []): array
    {
        return $this->post('get_shipment_history', $params);
    }

    /**
     * Get consignment note (e.g. PDF or labels) for given tracking numbers.
     * ENDPOINT: POST /get_consignment_note
     * Request: api_key, tracking_no (array of strings, e.g. ["ERA311010700MY","ERA311010695MY"])
     */
    public function getConsignmentNote(array $params): array
    {
        $trackingNos = $params['tracking_no'] ?? $params['tracking_nos'] ?? [];
        if (!is_array($trackingNos)) {
            $trackingNos = array_filter([$trackingNos]);
        }
        return $this->post('get_consignment_note', ['tracking_no' => array_values($trackingNos)]);
    }

    public function checkPriceBulk(array $params): array
    {
        return $this->post('check_price_bulk', $params);
    }

    public function createBulkAwb(array $params): array
    {
        return $this->post('create_bulk_awb', $params);
    }

    /**
     * Trace a shipment by tracking number.
     * ENDPOINT: POST /trace
     * Request: api_key, tracking_no
     * Success: { "status": true, "message": "success", "data": { "tracking_no", "status", "updated_at" } }
     * Failed: { "status": false, "message": "...", "data": [] }
     */
    public function trace(array $params): array
    {
        $trackingNo = (string) ($params['tracking_no'] ?? $params['tracking'] ?? '');
        if ($trackingNo === '') {
            return ['status' => false, 'message' => 'tracking_no is required', 'data' => []];
        }
        return $this->post('trace', ['tracking_no' => $trackingNo]);
    }
}

