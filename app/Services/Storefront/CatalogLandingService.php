<?php

namespace App\Services\Storefront;

use App\Models\Gallery;
use App\Models\Product;
use App\Services\Landing\LandingPageHomeContentService;
use Illuminate\Http\Request;

class CatalogLandingService
{
    public function __construct(
        protected \App\Services\CurrencyService $currencyService,
        protected LandingPageHomeContentService $landingPageHomeContent,
    ) {}

    /**
     * Home page storefront (featured products open for preorder + gallery teasers).
     *
     * @return array{products: \Illuminate\Support\Collection, highlightedGallery: \Illuminate\Support\Collection, currency: string, currencyConfig: array, heroSlides: array, shopBySportItems: array, featuredCollectionItems: array}
     */
    public function homeDataset(Request $request): array
    {
        // Fetch active products selected for homepage, ordered by custom position ascending
        $products = Product::where('is_active', true)
            ->where('add_to_homepage', true)
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        $highlightedGallery = Gallery::where('is_highlight', true)->latest()->take(6)->get();
        $currency = $this->currencyService->resolveCurrency($request);
        $currencyConfig = $this->currencyService->getCurrencyConfig($currency);
        $heroSlides = $this->landingPageHomeContent->heroSlides();
        $shopBySportItems = $this->landingPageHomeContent->shopBySportItems();
        $featuredCollectionItems = $this->landingPageHomeContent->featuredCollectionItems();

        return compact(
            'products',
            'highlightedGallery',
            'currency',
            'currencyConfig',
            'heroSlides',
            'shopBySportItems',
            'featuredCollectionItems',
        );
    }

    /**
     * @return array{products: \Illuminate\Database\Eloquent\Collection, highlightedGallery: \Illuminate\Support\Collection, currency: string, currencyConfig: array}
     */
    public function preorderLandingDataset(Request $request): array
    {
        $products = Product::where('available_for_preorder', true)
            ->where('is_active', true)
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $highlightedGallery = Gallery::where('is_highlight', true)->latest()->take(6)->get();

        $currency = $this->currencyService->resolveCurrency($request);
        $currencyConfig = $this->currencyService->getCurrencyConfig($currency);

        return compact('products', 'highlightedGallery', 'currency', 'currencyConfig');
    }
}
