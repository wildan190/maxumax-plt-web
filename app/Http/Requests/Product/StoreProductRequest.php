<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class StoreProductRequest extends FormRequest
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
            'size_guide' => 'nullable|image|max:2048',
            'variants.*.name' => 'nullable|string|max:100',
            'variants.*.stock' => 'nullable|integer|min:0',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (!$this->hasFile('images')) {
                return;
            }
            $images = $this->file('images');
            if (is_array($images) && count($images) > 4) {
                $validator->errors()->add('images', 'Max 4 images allowed');
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function toPersistableAttributes(): array
    {
        $validated = collect($this->validated())
            ->except(['image', 'images', 'size_guide', 'variants'])
            ->all();

        return array_merge($validated, [
            'slug' => Str::slug($this->input('name')) . '-' . Str::random(6),
            'uuid' => (string) Str::uuid(),
            'is_active' => $this->boolean('is_active'),
            'available_for_preorder' => $this->boolean('available_for_preorder'),
            'add_to_homepage' => $this->boolean('add_to_homepage'),
            'on_sale' => $this->boolean('on_sale'),
            'stock' => (int) $this->input('stock', 0),
            'collections' => $this->input('collections', []),
        ]);
    }

    /**
     * @return array<int, array{name: string, stock: int}>
     */
    public function normalizedVariants(): array
    {
        $out = [];
        foreach ($this->input('variants', []) as $variant) {
            $name = trim((string) ($variant['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $out[] = [
                'name' => $name,
                'stock' => (int) ($variant['stock'] ?? 0),
            ];
        }

        return $out;
    }
}
