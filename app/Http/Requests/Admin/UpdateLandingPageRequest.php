<?php

namespace App\Http\Requests\Admin;

use App\Models\LandingFeaturedCollectionItem;
use App\Models\LandingHeroSlide;
use App\Models\LandingShopBySportItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

class UpdateLandingPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $webpFile = ['nullable', 'file', 'mimetypes:image/webp', 'max:2048'];

        return [
            'hero' => ['nullable', 'array'],
            'hero.*.id' => ['nullable', 'integer', 'exists:landing_hero_slides,id'],
            'hero.*.title' => ['nullable', 'string', 'max:500'],
            'hero.*.body' => ['nullable', 'string', 'max:5000'],
            'hero.*.image' => $webpFile,
            'hero.*.buttons' => ['nullable', 'array', 'max:3'],
            'hero.*.buttons.*.label' => ['nullable', 'string', 'max:200'],
            'hero.*.buttons.*.url' => ['nullable', 'string', 'max:2000'],
            'hero.*.buttons.*.primary' => ['nullable', 'boolean'],

            'shop' => ['nullable', 'array'],
            'shop.*.id' => ['nullable', 'integer', 'exists:landing_shop_by_sport_items,id'],
            'shop.*.label' => ['nullable', 'string', 'max:255'],
            'shop.*.sport_param' => ['nullable', 'string', 'max:255'],
            'shop.*.image' => $webpFile,

            'featured' => ['nullable', 'array'],
            'featured.*.id' => ['nullable', 'integer', 'exists:landing_featured_collection_items,id'],
            'featured.*.label' => ['nullable', 'string', 'max:255'],
            'featured.*.filter_param' => ['nullable', 'string', 'max:255'],
            'featured.*.image' => $webpFile,
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $this->validateHeroImages($validator);
            $this->validateShopImages($validator);
            $this->validateFeaturedImages($validator);
        });
    }

    private function validateHeroImages(Validator $validator): void
    {
        foreach ($this->input('hero', []) as $index => $row) {
            if ($this->isBlankHeroRow($index)) {
                continue;
            }
            if (trim((string) ($row['title'] ?? '')) === '') {
                $validator->errors()->add("hero.$index.title", 'Judul slide wajib diisi.');
            }
            $id = $row['id'] ?? null;
            $hasNewFile = $this->hasFile("hero.$index.image");
            if (! $hasNewFile) {
                if (! $id) {
                    $validator->errors()->add(
                        "hero.$index.image",
                        'Unggah gambar .webp wajib untuk slide baru (maks 2MB).'
                    );

                    continue;
                }
                $slide = LandingHeroSlide::query()->find($id);
                $path = $slide?->image_path;
                if (! $path || ! Storage::disk('public')->exists($path)) {
                    $validator->errors()->add(
                        "hero.$index.image",
                        'File gambar tidak ada di penyimpanan. Unggah .webp baru (maks 2MB).'
                    );
                }
            }
        }
    }

    private function validateShopImages(Validator $validator): void
    {
        foreach ($this->input('shop', []) as $index => $row) {
            if ($this->isBlankShopRow($index)) {
                continue;
            }
            if (trim((string) ($row['label'] ?? '')) === '') {
                $validator->errors()->add("shop.$index.label", 'Label wajib diisi.');
            }
            if (trim((string) ($row['sport_param'] ?? '')) === '') {
                $validator->errors()->add("shop.$index.sport_param", 'Nilai sport (query) wajib diisi.');
            }
            $id = $row['id'] ?? null;
            $hasNewFile = $this->hasFile("shop.$index.image");
            if (! $hasNewFile) {
                if (! $id) {
                    $validator->errors()->add(
                        "shop.$index.image",
                        'Unggah gambar .webp wajib untuk item baru (maks 2MB).'
                    );

                    continue;
                }
                $item = LandingShopBySportItem::query()->find($id);
                $path = $item?->image_path;
                if (! $path || ! Storage::disk('public')->exists($path)) {
                    $validator->errors()->add(
                        "shop.$index.image",
                        'File gambar tidak ada di penyimpanan. Unggah .webp baru (maks 2MB).'
                    );
                }
            }
        }
    }

    private function validateFeaturedImages(Validator $validator): void
    {
        foreach ($this->input('featured', []) as $index => $row) {
            if ($this->isBlankFeaturedRow($index)) {
                continue;
            }
            if (trim((string) ($row['label'] ?? '')) === '') {
                $validator->errors()->add("featured.$index.label", 'Label wajib diisi.');
            }
            if (trim((string) ($row['filter_param'] ?? '')) === '') {
                $validator->errors()->add("featured.$index.filter_param", 'Nilai filter (query) wajib diisi.');
            }
            $id = $row['id'] ?? null;
            $hasNewFile = $this->hasFile("featured.$index.image");
            if (! $hasNewFile) {
                if (! $id) {
                    $validator->errors()->add(
                        "featured.$index.image",
                        'Unggah gambar .webp wajib untuk item baru (maks 2MB).'
                    );

                    continue;
                }
                $item = LandingFeaturedCollectionItem::query()->find($id);
                $path = $item?->image_path;
                if (! $path || ! Storage::disk('public')->exists($path)) {
                    $validator->errors()->add(
                        "featured.$index.image",
                        'File gambar tidak ada di penyimpanan. Unggah .webp baru (maks 2MB).'
                    );
                }
            }
        }
    }

    private function isBlankHeroRow(int $index): bool
    {
        $row = $this->input("hero.$index", []);

        return ! ($row['id'] ?? null)
            && trim((string) ($row['title'] ?? '')) === ''
            && trim((string) ($row['body'] ?? '')) === ''
            && ! $this->hasFile("hero.$index.image");
    }

    private function isBlankShopRow(int $index): bool
    {
        $row = $this->input("shop.$index", []);

        return ! ($row['id'] ?? null)
            && trim((string) ($row['label'] ?? '')) === ''
            && trim((string) ($row['sport_param'] ?? '')) === ''
            && ! $this->hasFile("shop.$index.image");
    }

    private function isBlankFeaturedRow(int $index): bool
    {
        $row = $this->input("featured.$index", []);

        return ! ($row['id'] ?? null)
            && trim((string) ($row['label'] ?? '')) === ''
            && trim((string) ($row['filter_param'] ?? '')) === ''
            && ! $this->hasFile("featured.$index.image");
    }
}
