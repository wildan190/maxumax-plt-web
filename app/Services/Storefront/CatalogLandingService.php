<?php

namespace App\Services\Storefront;

use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogLandingService
{
    public function __construct(
        protected \App\Services\CurrencyService $currencyService,
    ) {}

    /**
     * Home page storefront (featured products open for preorder + gallery teasers).
     *
     * @return array{products: \Illuminate\Support\Collection, highlightedGallery: \Illuminate\Support\Collection, currency: string, currencyConfig: array}
     */
    public function homeDataset(Request $request): array
    {
        // Fetch latest active products (can be preorder or ready stock)
        $products = Product::where('is_active', true)->latest()->take(4)->get();
        $highlightedGallery = Gallery::where('is_highlight', true)->latest()->take(6)->get();
        $currency = $this->currencyService->resolveCurrency($request);
        $currencyConfig = $this->currencyService->getCurrencyConfig($currency);

        return compact('products', 'highlightedGallery', 'currency', 'currencyConfig');
    }

    /**
     * @return array{products: \Illuminate\Database\Eloquent\Collection, highlightedGallery: \Illuminate\Support\Collection, currency: string, currencyConfig: array}
     */
    public function preorderLandingDataset(Request $request): array
    {
        $products = Product::where('available_for_preorder', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $highlightedGallery = Gallery::where('is_highlight', true)->latest()->take(6)->get();

        $currency = $this->currencyService->resolveCurrency($request);
        $currencyConfig = $this->currencyService->getCurrencyConfig($currency);

        return compact('products', 'highlightedGallery', 'currency', 'currencyConfig');
    }
}
