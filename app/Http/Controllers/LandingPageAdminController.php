<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\UpdateLandingPageRequest;
use App\Models\LandingFeaturedCollectionItem;
use App\Models\LandingHeroSlide;
use App\Models\LandingProjectItem;
use App\Models\LandingShopBySportItem;
use App\Services\Landing\LandingPageAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LandingPageAdminController extends Controller
{
    public function __construct(
        protected LandingPageAdminService $landingPageAdminService,
    ) {}

    public function edit(): View
    {
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Landing page', 'url' => route('admin.landing-page.edit')]
        ));

        $heroSlides = LandingHeroSlide::query()->orderBy('sort_order')->orderBy('id')->get();
        $shopItems = LandingShopBySportItem::query()->orderBy('sort_order')->orderBy('id')->get();
        $featuredItems = LandingFeaturedCollectionItem::query()->orderBy('sort_order')->orderBy('id')->get();
        $projectItems = LandingProjectItem::query()->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.landing-page.edit', compact('heroSlides', 'shopItems', 'featuredItems', 'projectItems'));
    }

    public function update(UpdateLandingPageRequest $request): RedirectResponse
    {
        $this->landingPageAdminService->syncFromRequest($request);

        return redirect()->route('admin.landing-page.edit')->with('success', 'Landing page berhasil disimpan.');
    }

    public function resetHero(): RedirectResponse
    {
        $this->landingPageAdminService->resetHeroToDefaults();

        return redirect()->route('admin.landing-page.edit')->with('success', 'Hero dikembalikan ke default situs.');
    }

    public function resetShop(): RedirectResponse
    {
        $this->landingPageAdminService->resetShopToDefaults();

        return redirect()->route('admin.landing-page.edit')->with('success', 'Shop by sport dikembalikan ke default situs.');
    }

    public function resetFeatured(): RedirectResponse
    {
        $this->landingPageAdminService->resetFeaturedToDefaults();

        return redirect()->route('admin.landing-page.edit')->with('success', 'Featured collections dikembalikan ke default situs.');
    }

    public function resetProjects(): RedirectResponse
    {
        $this->landingPageAdminService->resetProjectsToDefaults();

        return redirect()->route('admin.landing-page.edit')->with('success', 'Trusted projects dikembalikan ke default situs.');
    }

    public function partialHero(): View
    {
        return view('admin.landing-page.partials.hero-row', [
            'index' => request('index', 0),
            'slide' => null
        ]);
    }

    public function partialShop(): View
    {
        return view('admin.landing-page.partials.shop-row', [
            'index' => request('index', 0),
            'item' => null
        ]);
    }

    public function partialFeatured(): View
    {
        return view('admin.landing-page.partials.featured-row', [
            'index' => request('index', 0),
            'item' => null
        ]);
    }

    public function partialProject(): View
    {
        return view('admin.landing-page.partials.project-row', [
            'index' => request('index', 0),
            'item' => null
        ]);
    }
}
