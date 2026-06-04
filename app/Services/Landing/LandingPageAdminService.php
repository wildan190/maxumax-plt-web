<?php

namespace App\Services\Landing;

use App\Http\Requests\Admin\UpdateLandingPageRequest;
use App\Models\LandingFeaturedCollectionItem;
use App\Models\LandingHeroSlide;
use App\Models\LandingProjectItem;
use App\Models\LandingShopBySportItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LandingPageAdminService
{
    public function syncFromRequest(UpdateLandingPageRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $this->syncHero($request);
            $this->syncShop($request);
            $this->syncFeatured($request);
            $this->syncProjects($request);
        });
    }

    public function resetHeroToDefaults(): void
    {
        $this->deleteAllHeroSlides();
    }

    public function resetShopToDefaults(): void
    {
        $this->deleteAllShopItems();
    }

    public function resetFeaturedToDefaults(): void
    {
        $this->deleteAllFeaturedItems();
    }

    public function resetProjectsToDefaults(): void
    {
        $this->deleteAllProjectItems();
    }

    private function syncHero(UpdateLandingPageRequest $request): void
    {
        $rows = $request->input('hero', []);
        $keptIds = [];
        $toSave = [];

        foreach ($rows as $index => $row) {
            if ($this->isBlankHeroRow($request, $index)) {
                continue;
            }
            $id = isset($row['id']) ? (int) $row['id'] : null;
            if ($id) {
                $keptIds[] = $id;
            }
            $toSave[] = ['index' => $index, 'row' => $row, 'id' => $id];
        }

        $allIds = LandingHeroSlide::query()->pluck('id')->all();
        $removeIds = array_values(array_diff($allIds, $keptIds));
        if ($removeIds !== []) {
            LandingHeroSlide::query()->whereIn('id', $removeIds)->get()->each(function (LandingHeroSlide $slide) {
                $this->deleteStoredIfExists($slide->image_path);
                $slide->delete();
            });
        }

        foreach ($toSave as $order => $pack) {
            $row = $pack['row'];
            $index = $pack['index'];
            $buttons = $this->normalizeButtons($row['buttons'] ?? []);

            if ($pack['id']) {
                $slide = LandingHeroSlide::query()->findOrFail($pack['id']);
                $data = [
                    'sort_order' => $order,
                    'title' => $row['title'],
                    'body' => $row['body'] ?? null,
                    'buttons' => $buttons,
                ];
                if ($request->hasFile("hero.$index.image")) {
                    $this->deleteStoredIfExists($slide->image_path);
                    $data['image_path'] = $request->file("hero.$index.image")->store('landing/hero', 'public');
                }
                $slide->update($data);
            } else {
                $slideData = [
                    'sort_order' => $order,
                    'title' => $row['title'],
                    'body' => $row['body'] ?? null,
                    'buttons' => $buttons,
                ];
                if ($request->hasFile("hero.$index.image")) {
                    $slideData['image_path'] = $request->file("hero.$index.image")->store('landing/hero', 'public');
                }
                LandingHeroSlide::query()->create($slideData);
            }
        }
    }

    private function syncShop(UpdateLandingPageRequest $request): void
    {
        $rows = $request->input('shop', []);
        $keptIds = [];
        $toSave = [];

        foreach ($rows as $index => $row) {
            if ($this->isBlankShopRow($request, $index)) {
                continue;
            }
            $id = isset($row['id']) ? (int) $row['id'] : null;
            if ($id) {
                $keptIds[] = $id;
            }
            $toSave[] = ['index' => $index, 'row' => $row, 'id' => $id];
        }

        $allIds = LandingShopBySportItem::query()->pluck('id')->all();
        $removeIds = array_values(array_diff($allIds, $keptIds));
        if ($removeIds !== []) {
            LandingShopBySportItem::query()->whereIn('id', $removeIds)->get()->each(function (LandingShopBySportItem $item) {
                $this->deleteStoredIfExists($item->image_path);
                $item->delete();
            });
        }

        foreach ($toSave as $order => $pack) {
            $row = $pack['row'];
            $index = $pack['index'];
            if ($pack['id']) {
                $item = LandingShopBySportItem::query()->findOrFail($pack['id']);
                $data = [
                    'sort_order' => $order,
                    'label' => $row['label'],
                    'sport_param' => $row['sport_param'],
                ];
                if ($request->hasFile("shop.$index.image")) {
                    $this->deleteStoredIfExists($item->image_path);
                    $data['image_path'] = $request->file("shop.$index.image")->store('landing/shop-by-sport', 'public');
                }
                $item->update($data);
            } else {
                $itemData = [
                    'sort_order' => $order,
                    'label' => $row['label'],
                    'sport_param' => $row['sport_param'],
                ];
                if ($request->hasFile("shop.$index.image")) {
                    $itemData['image_path'] = $request->file("shop.$index.image")->store('landing/shop-by-sport', 'public');
                }
                LandingShopBySportItem::query()->create($itemData);
            }
        }
    }

    private function syncFeatured(UpdateLandingPageRequest $request): void
    {
        $rows = $request->input('featured', []);
        $keptIds = [];
        $toSave = [];

        foreach ($rows as $index => $row) {
            if ($this->isBlankFeaturedRow($request, $index)) {
                continue;
            }
            $id = isset($row['id']) ? (int) $row['id'] : null;
            if ($id) {
                $keptIds[] = $id;
            }
            $toSave[] = ['index' => $index, 'row' => $row, 'id' => $id];
        }

        $allIds = LandingFeaturedCollectionItem::query()->pluck('id')->all();
        $removeIds = array_values(array_diff($allIds, $keptIds));
        if ($removeIds !== []) {
            LandingFeaturedCollectionItem::query()->whereIn('id', $removeIds)->get()->each(function (LandingFeaturedCollectionItem $item) {
                $this->deleteStoredIfExists($item->image_path);
                $item->delete();
            });
        }

        foreach ($toSave as $order => $pack) {
            $row = $pack['row'];
            $index = $pack['index'];
            if ($pack['id']) {
                $item = LandingFeaturedCollectionItem::query()->findOrFail($pack['id']);
                $data = [
                    'sort_order' => $order,
                    'label' => $row['label'],
                    'filter_param' => $row['filter_param'],
                ];
                if ($request->hasFile("featured.$index.image")) {
                    $this->deleteStoredIfExists($item->image_path);
                    $data['image_path'] = $request->file("featured.$index.image")->store('landing/featured-collections', 'public');
                }
                $item->update($data);
            } else {
                $itemData = [
                    'sort_order' => $order,
                    'label' => $row['label'],
                    'filter_param' => $row['filter_param'],
                ];
                if ($request->hasFile("featured.$index.image")) {
                    $itemData['image_path'] = $request->file("featured.$index.image")->store('landing/featured-collections', 'public');
                }
                LandingFeaturedCollectionItem::query()->create($itemData);
            }
        }
    }

    private function syncProjects(UpdateLandingPageRequest $request): void
    {
        $rows = $request->input('projects', []);
        $keptIds = [];
        $toSave = [];

        foreach ($rows as $index => $row) {
            if ($this->isBlankProjectRow($request, $index)) {
                continue;
            }
            $id = isset($row['id']) ? (int) $row['id'] : null;
            if ($id) {
                $keptIds[] = $id;
            }
            $toSave[] = ['index' => $index, 'row' => $row, 'id' => $id];
        }

        $allIds = LandingProjectItem::query()->pluck('id')->all();
        $removeIds = array_values(array_diff($allIds, $keptIds));
        if ($removeIds !== []) {
            LandingProjectItem::query()->whereIn('id', $removeIds)->get()->each(function (LandingProjectItem $item) {
                $this->deleteStoredIfExists($item->image_path);
                $item->delete();
            });
        }

        foreach ($toSave as $order => $pack) {
            $row = $pack['row'];
            $index = $pack['index'];
            if ($pack['id']) {
                $item = LandingProjectItem::query()->findOrFail($pack['id']);
                $data = [
                    'sort_order' => $order,
                    'category' => $row['category'] ?? null,
                    'title' => $row['title'],
                    'headline' => $row['headline'] ?? null,
                    'subhead' => $row['subhead'] ?? null,
                    'description' => $row['description'] ?? null,
                ];
                if ($request->hasFile("projects.$index.image")) {
                    $this->deleteStoredIfExists($item->image_path);
                    $data['image_path'] = $request->file("projects.$index.image")->store('landing/projects', 'public');
                }

                // Handle Gallery
                $gallery = $item->gallery ?? [];
                // Remove selected images
                if (isset($row['remove_gallery']) && is_array($row['remove_gallery'])) {
                    foreach ($row['remove_gallery'] as $pathToRemove) {
                        $this->deleteStoredIfExists($pathToRemove);
                        $gallery = array_values(array_filter($gallery, fn($p) => $p !== $pathToRemove));
                    }
                }
                // Add new images
                if ($request->hasFile("projects.$index.gallery")) {
                    foreach ($request->file("projects.$index.gallery") as $file) {
                        $gallery[] = $file->store('landing/projects/gallery', 'public');
                    }
                }
                $data['gallery'] = $gallery;

                $item->update($data);
            } else {
                $itemData = [
                    'sort_order' => $order,
                    'category' => $row['category'] ?? null,
                    'title' => $row['title'],
                    'headline' => $row['headline'] ?? null,
                    'subhead' => $row['subhead'] ?? null,
                    'description' => $row['description'] ?? null,
                ];
                if ($request->hasFile("projects.$index.image")) {
                    $itemData['image_path'] = $request->file("projects.$index.image")->store('landing/projects', 'public');
                }

                // Handle Gallery
                $gallery = [];
                if ($request->hasFile("projects.$index.gallery")) {
                    foreach ($request->file("projects.$index.gallery") as $file) {
                        $gallery[] = $file->store('landing/projects/gallery', 'public');
                    }
                }
                $itemData['gallery'] = $gallery;

                LandingProjectItem::query()->create($itemData);
            }
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $buttons
     */
    private function normalizeButtons(array $buttons): ?array
    {
        $out = [];
        foreach ($buttons as $b) {
            if (! is_array($b)) {
                continue;
            }
            $label = trim((string) ($b['label'] ?? ''));
            $url = trim((string) ($b['url'] ?? ''));
            if ($label === '' || $url === '') {
                continue;
            }
            $out[] = [
                'label' => $label,
                'url' => $url,
                'primary' => filter_var($b['primary'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ];
        }

        return $out === [] ? null : $out;
    }

    private function isBlankHeroRow(UpdateLandingPageRequest $request, int $index): bool
    {
        $row = $request->input("hero.$index", []);

        return ! ($row['id'] ?? null)
            && trim((string) ($row['title'] ?? '')) === ''
            && trim((string) ($row['body'] ?? '')) === ''
            && ! $request->hasFile("hero.$index.image");
    }

    private function isBlankShopRow(UpdateLandingPageRequest $request, int $index): bool
    {
        $row = $request->input("shop.$index", []);

        return ! ($row['id'] ?? null)
            && trim((string) ($row['label'] ?? '')) === ''
            && trim((string) ($row['sport_param'] ?? '')) === ''
            && ! $request->hasFile("shop.$index.image");
    }

    private function isBlankFeaturedRow(UpdateLandingPageRequest $request, int $index): bool
    {
        $row = $request->input("featured.$index", []);

        return ! ($row['id'] ?? null)
            && trim((string) ($row['label'] ?? '')) === ''
            && trim((string) ($row['filter_param'] ?? '')) === ''
            && ! $request->hasFile("featured.$index.image");
    }

    private function isBlankProjectRow(UpdateLandingPageRequest $request, int $index): bool
    {
        $row = $request->input("projects.$index", []);

        return ! ($row['id'] ?? null)
            && trim((string) ($row['title'] ?? '')) === ''
            && trim((string) ($row['category'] ?? '')) === ''
            && trim((string) ($row['description'] ?? '')) === ''
            && trim((string) ($row['headline'] ?? '')) === ''
            && trim((string) ($row['subhead'] ?? '')) === ''
            && ! $request->hasFile("projects.$index.image")
            && ! $request->hasFile("projects.$index.gallery");
    }

    private function deleteStoredIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function deleteAllHeroSlides(): void
    {
        LandingHeroSlide::query()->get()->each(function (LandingHeroSlide $slide) {
            $this->deleteStoredIfExists($slide->image_path);
            $slide->delete();
        });
    }

    private function deleteAllShopItems(): void
    {
        LandingShopBySportItem::query()->get()->each(function (LandingShopBySportItem $item) {
            $this->deleteStoredIfExists($item->image_path);
            $item->delete();
        });
    }

    private function deleteAllFeaturedItems(): void
    {
        LandingFeaturedCollectionItem::query()->get()->each(function (LandingFeaturedCollectionItem $item) {
            $this->deleteStoredIfExists($item->image_path);
            $item->delete();
        });
    }

    private function deleteAllProjectItems(): void
    {
        LandingProjectItem::query()->get()->each(function (LandingProjectItem $item) {
            $this->deleteStoredIfExists($item->image_path);
            if (is_array($item->gallery)) {
                foreach ($item->gallery as $path) {
                    $this->deleteStoredIfExists($path);
                }
            }
            $item->delete();
        });
    }
}
