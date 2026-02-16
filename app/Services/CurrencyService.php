<?php

namespace App\Services;

use Illuminate\Http\Request;

class CurrencyService
{
    /**
     * Get currency configuration for pricing.
     */
    public function getCurrencyConfig(string $currency): array
    {
        $currencies = [
            'MYR' => ['rate' => 1, 'longSleeve' => 10, 'nameset' => 35],
            'BND' => ['rate' => 1.05, 'longSleeve' => 3, 'nameset' => 13],
            'SGD' => ['rate' => 1.05, 'longSleeve' => 3, 'nameset' => 13],
            'IDR' => ['rate' => 5200, 'longSleeve' => 15600, 'nameset' => 67600],
        ];

        return $currencies[$currency] ?? $currencies['MYR'];
    }

    /**
     * Resolve currency from session or IP.
     */
    public function resolveCurrency(Request $request): string
    {
        if (session('currency_manual', false)) {
            return session('currency', 'MYR');
        }

        if (session()->has('currency')) {
            return session('currency');
        }

        try {
            $ip = $request->ip();
            $ctx = stream_context_create(['http' => ['timeout' => 2]]);
            $json = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode", false, $ctx);

            if ($json) {
                $data = json_decode($json, true);
                $country = $data['countryCode'] ?? null;
                $currency = match ($country) {
                    'ID' => 'IDR',
                    'BN' => 'BND',
                    'SG' => 'SGD',
                    default => 'MYR',
                };
                session(['currency' => $currency]);
                return $currency;
            }
        } catch (\Throwable $e) {
            // Fallback
        }

        session(['currency' => 'MYR']);
        return 'MYR';
    }

    /**
     * Convert amount to cents for Stripe.
     */
    public function convertToCents(float $amount, string $currency): int
    {
        $zeroDecimalCurrencies = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VUV', 'VND', 'XAF', 'XOF', 'XPF'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return (int) round($amount);
        }

        return (int) round($amount * 100);
    }
}
