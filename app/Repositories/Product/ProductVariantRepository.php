<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantRepository
{
    /**
     * @param  array<int, array{name: string, stock: int}>  $variants
     */
    public function createManyForProduct(Product $product, array $variants): void
    {
        foreach ($variants as $row) {
            ProductVariant::create([
                'product_id' => $product->id,
                'name' => $row['name'],
                'stock' => $row['stock'],
                'is_available' => true,
            ]);
        }
    }

    /**
     * Create/update from admin form payloads; deletes variants omitted from payload.
     *
     * @param  array<int, array<string, mixed>>  $variantsInput
     */
    public function syncFromForm(Product $product, array $variantsInput): void
    {
        $variantIds = [];

        foreach ($variantsInput as $variantData) {
            $name = trim((string) ($variantData['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $stock = (int) ($variantData['stock'] ?? 0);

            $id = $variantData['id'] ?? null;
            if ($id) {
                $variant = ProductVariant::find((int) $id);
                if ($variant && (int) $variant->product_id === (int) $product->id) {
                    $variant->update([
                        'name' => $name,
                        'stock' => $stock,
                    ]);
                    $variantIds[] = $variant->id;
                }

                continue;
            }

            $created = ProductVariant::create([
                'product_id' => $product->id,
                'name' => $name,
                'stock' => $stock,
                'is_available' => true,
            ]);
            $variantIds[] = $created->id;
        }

        $product->variants()->whereNotIn('id', $variantIds)->delete();
    }
}
