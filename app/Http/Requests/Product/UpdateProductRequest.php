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
            'jersey_type' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'stock' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'available_for_preorder' => 'sometimes|boolean',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',
            'image_positions' => 'nullable|string',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.name' => 'nullable|string|max:100',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
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
            ->except(['image', 'images', 'variants'])
            ->all();

        $data = array_merge($validated, [
            'is_active' => $this->boolean('is_active'),
            'available_for_preorder' => $this->boolean('available_for_preorder'),
            'stock' => (int) $this->input('stock', 0),
            'sku' => $this->input('sku'),
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
}
