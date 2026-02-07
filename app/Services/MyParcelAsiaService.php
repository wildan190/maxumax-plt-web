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
            $resp = Http::timeout(12)->asForm()->post($url, $payload);
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

    public function checkout(array $order, array $shipments): array
    {
        $params = [
            'order' => $order,
            'shipments' => $shipments,
        ];
        return $this->post('checkout', $params);
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

    public function getShipmentHistory(array $params = []): array
    {
        return $this->post('get_shipment_history', $params);
    }

    public function checkPriceBulk(array $params): array
    {
        return $this->post('check_price_bulk', $params);
    }

    public function createBulkAwb(array $params): array
    {
        return $this->post('create_bulk_awb', $params);
    }

    public function trace(array $params): array
    {
        return $this->post('trace', $params);
    }
}

