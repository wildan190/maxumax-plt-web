<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'collection' => 'nullable|string|max:100',
            'collections' => 'nullable|array',
            'material' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:100',
            'fit' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'available_for_preorder' => 'sometimes|boolean',
            'add_to_homepage' => 'sometimes|boolean',
            'on_sale' => 'sometimes|boolean',
            'discounted_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',
            'image_positions' => 'nullable|string',
            'deleted_images' => 'nullable|string',
            'delete_main_image' => 'nullable|boolean',
            'size_guide' => 'nullable|image|max:2048',
            'delete_size_guide' => 'sometimes|boolean',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.name' => 'nullable|string|max:100',
            'variants.*.stock' => 'nullable|integer|min:0',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Product|null $product */
            $product = $this->route('product');
            if (!$product || !$this->hasFile('images')) {
                return;
            }
            $existingCount = (int) $product->images()->count();
            $images = $this->file('images');
            if (!is_array($images)) {
                return;
            }
            if (($existingCount + count($images)) > 4) {
                $validator->errors()->add('images', 'Max 4 images allowed total');
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function toPersistableAttributes(Product $existing): array
    {
        $validated = collect($this->validated())
            ->except(['image', 'images', 'size_guide', 'delete_size_guide', 'variants', 'image_positions', 'deleted_images', 'delete_main_image'])
            ->all();

        $data = array_merge($validated, [
            'is_active' => $this->boolean('is_active'),
            'available_for_preorder' => $this->boolean('available_for_preorder'),
            'add_to_homepage' => $this->boolean('add_to_homepage'),
            'on_sale' => $this->boolean('on_sale'),
            'stock' => (int) $this->input('stock', 0),
            'collections' => $this->input('collections', []),
        ]);

        if ($existing->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        }

        return $data;
    }

    /**
     * Raw variants input for sync logic (preserve id keys).
     *
     * @return array<int, array<string, mixed>>
     */
    public function variantsInput(): array
    {
        return $this->input('variants', []);
    }

    /**
     * Get image positions for existing product images.
     *
     * @return array<int|string>
     */
    public function imagePositions(): array
    {
        $positions = $this->input('image_positions');
        if (!$positions) {
            return [];
        }
        try {
            return json_decode($positions, true) ?? [];
        } catch (\Exception) {
            return [];
        }
    }

    /**
     * Get IDs of images to be deleted.
     *
     * @return array<int|string>
     */
    public function deletedImageIds(): array
    {
        $deleted = $this->input('deleted_images');
        if (!$deleted) {
            return [];
        }
        try {
            return json_decode($deleted, true) ?? [];
        } catch (\Exception) {
            return [];
        }
    }

    /**
     * Should the main image be deleted?
     */
    public function shouldDeleteMainImage(): bool
    {
        return $this->boolean('delete_main_image');
    }
}
