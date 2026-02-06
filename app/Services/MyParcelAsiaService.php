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
}

