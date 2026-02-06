<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DelyvaService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected ?string $accessToken;
    protected ?string $companyCode;
    protected ?string $companyId;
    protected ?string $userId;
    protected ?int $customerId;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.delyva.base_url', 'https://api.delyva.app/v1.0'), '/');
        $this->apiKey = config('services.delyva.api_key');
        $this->accessToken = config('services.delyva.access_token');
        $this->companyCode = config('services.delyva.company_code');
        $this->companyId = config('services.delyva.company_id');
        $this->userId = config('services.delyva.user_id');
        $this->customerId = config('services.delyva.customer_id');
    }

    protected function request(string $method, string $path, array $payload = [], array $query = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        $headers = [];
        // Prefer official access token if provided, fallback to api key
        $token = $this->accessToken ?: $this->apiKey;
        if (!empty($token)) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
        try {
            $req = Http::timeout(12)->withHeaders($headers);
            if (strtoupper($method) === 'GET') {
                $resp = $req->get($url, $query);
            } else {
                $resp = $req->withQueryParameters($query)->withHeaders(['Content-Type' => 'application/json'])->send($method, $url, ['json' => $payload]);
            }
            if ($resp->failed()) {
                Log::error('Delyva API Request Failed', ['method' => $method, 'path' => $path, 'status' => $resp->status(), 'body' => $resp->body()]);
                return ['error' => true, 'status' => $resp->status(), 'body' => $resp->json()];
            }
            return $resp->json();
        } catch (\Throwable $e) {
            Log::error('Delyva API Exception: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function createOrder(array $origin, array $destination, array $items, array $meta = []): array
    {
        $payload = [
            'companyId' => $this->companyId,
            'userId' => $this->userId,
            'customerId' => $this->customerId,
            'origin' => $origin,
            'destination' => $destination,
            'items' => $items,
        ] + $meta;
        return $this->request('POST', '/order', $payload);
    }

    public function processOrder(string $orderId, string $serviceCode, ?string $originScheduledAt = null, ?string $destinationScheduledAt = null): array
    {
        $payload = [
            'serviceCode' => $serviceCode,
        ];
        if ($originScheduledAt) {
            $payload['originScheduledAt'] = $originScheduledAt;
        }
        if ($destinationScheduledAt) {
            $payload['destinationScheduledAt'] = $destinationScheduledAt;
        }
        return $this->request('POST', "/order/{$orderId}/process", $payload);
    }

    public function getOrder(string $orderId): array
    {
        return $this->request('GET', "/order/{$orderId}");
    }

    public function getLabelUrl(string $orderId): string
    {
        $companyId = $this->companyId;
        return "{$this->baseUrl}/order/{$orderId}/label?companyId={$companyId}";
    }

    public function quote(array $origin, array $destination, array $items): array
    {
        $payload = [
            'companyId' => $this->companyId,
            'customerId' => $this->customerId,
            'origin' => $origin,
            'destination' => $destination,
            'items' => $items,
        ];
        $candidates = [
            '/quote',
            '/order/quote',
            '/quote/price',
            '/service/quote',
            '/orders/quote',
            '/order/price',
        ];
        foreach ($candidates as $path) {
            $resp = $this->request('POST', $path, $payload, ['companyId' => $this->companyId]);
            if (isset($resp['data']) || (isset($resp['list']) && is_array($resp['list']))) {
                return $resp;
            }
        }
        return ['error' => true, 'message' => 'No valid quote endpoint'];
    }
}
