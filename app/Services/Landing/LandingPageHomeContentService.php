<?php

namespace App\Services\Landing;

use App\Models\LandingFeaturedCollectionItem;
use App\Models\LandingHeroSlide;
use App\Models\LandingProjectItem;
use App\Models\LandingShopBySportItem;
use Illuminate\Support\Facades\Storage;

class LandingPageHomeContentService
{
    /**
     * @return array<int, array{img: string, title: string, text: string, btns: array<int, array{label: string, url: string, primary: bool}>}>
     */
    public function heroSlides(): array
    {
        $defaults = LandingPageDefaults::heroSlides();
        $rows = LandingHeroSlide::query()->orderBy('sort_order')->orderBy('id')->get();
        if ($rows->isEmpty()) {
            return $defaults;
        }

        $n = count($defaults);

        return $rows->values()->map(function (LandingHeroSlide $row, int $i) use ($defaults, $n) {
            $def = $defaults[$i % $n];
            $img = $this->publicUrlOrNull($row->image_path) ?? $def['img'];
            $title = trim((string) $row->title) !== '' ? $row->title : $def['title'];
            $text = trim((string) $row->body) !== '' ? (string) $row->body : $def['text'];
            $btns = ($row->buttons && count($row->buttons) > 0) ? $row->buttons : $def['btns'];

            return [
                'img' => $img,
                'title' => $title,
                'text' => $text,
                'btns' => $btns,
            ];
        })->all();
    }

    /**
     * @return array<int, array{label: string, href: string, img: string}>
     */
    public function shopBySportItems(): array
    {
        $defaults = LandingPageDefaults::shopBySport();
        $rows = LandingShopBySportItem::query()->orderBy('sort_order')->orderBy('id')->get();
        if ($rows->isEmpty()) {
            return collect($defaults)->map(fn (array $d) => [
                'label' => $d['label'],
                'href' => route('products.index', ['sport' => $d['sport_param']]),
                'img' => $d['img'],
            ])->all();
        }

        $n = count($defaults);

        return $rows->values()->map(function (LandingShopBySportItem $row, int $i) use ($defaults, $n) {
            $def = $defaults[$i % $n];
            $img = $this->publicUrlOrNull($row->image_path) ?? $def['img'];
            $label = trim((string) $row->label) !== '' ? $row->label : $def['label'];
            $sport = trim((string) $row->sport_param) !== '' ? $row->sport_param : $def['sport_param'];

            return [
                'label' => $label,
                'href' => route('products.index', ['sport' => $sport]),
                'img' => $img,
            ];
        })->all();
    }

    /**
     * @return array<int, array{label: string, href: string, img: string}>
     */
    public function featuredCollectionItems(): array
    {
        $defaults = LandingPageDefaults::featuredCollections();
        $rows = LandingFeaturedCollectionItem::query()->orderBy('sort_order')->orderBy('id')->get();
        if ($rows->isEmpty()) {
            return collect($defaults)->map(fn (array $d) => [
                'label' => $d['label'],
                'href' => route('products.index', ['filter' => $d['filter_param']]),
                'img' => $d['img'],
            ])->all();
        }

        $n = count($defaults);

        return $rows->values()->map(function (LandingFeaturedCollectionItem $row, int $i) use ($defaults, $n) {
            $def = $defaults[$i % $n];
            $img = $this->publicUrlOrNull($row->image_path) ?? $def['img'];
            $label = trim((string) $row->label) !== '' ? $row->label : $def['label'];
            $filter = trim((string) $row->filter_param) !== '' ? $row->filter_param : $def['filter_param'];

            return [
                'label' => $label,
                'href' => route('products.index', ['filter' => $filter]),
                'img' => $img,
            ];
        })->all();
    }

    /**
     * @return array<int, array{category: string, title: string, description: string, img: string}>
     */
    public function trustedProjectItems(): array
    {
        $defaults = LandingPageDefaults::trustedProjects();
        $rows = LandingProjectItem::query()->orderBy('sort_order')->orderBy('id')->get();
        if ($rows->isEmpty()) {
            return array_slice($defaults, 0, 4);
        }

        $n = count($defaults);

        return $rows->values()->take(4)->map(function (LandingProjectItem $row, int $i) use ($defaults, $n) {
            $def = $defaults[$i % $n];
            $img = $this->publicUrlOrNull($row->image_path) ?? $def['img'];
            $category = trim((string) $row->category) !== '' ? $row->category : $def['category'];
            $title = trim((string) $row->title) !== '' ? $row->title : $def['title'];
            $description = trim((string) $row->description) !== '' ? $row->description : $def['description'];

            return [
                'category' => $category,
                'title' => $title,
                'description' => $description,
                'img' => $img,
            ];
        })->all();
    }

    /**
     * @return array<int, array{title: string, description: string, icon: string}>
     */
    public function whyChooseItems(): array
    {
        return LandingPageDefaults::whyChoose();
    }

    private function publicUrlOrNull(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($path);
    }
}
