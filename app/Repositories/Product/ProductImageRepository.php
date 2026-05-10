<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;

class ProductImageRepository
{
    /**
     * @param  array<int, array{path: string, position: int}>  $gallery
     */
    public function createGalleryRows(Product $product, array $gallery): void
    {
        foreach ($gallery as $g) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $g['path'],
                'position' => $g['position'],
            ]);
        }
    }

    /**
     * Persist additional gallery files (admin update).
     *
     * @param  array<int, UploadedFile|array|null>  $files
     * @param  callable(UploadedFile): string  $storeFile
     * @return array<int, array{path: string, position: int}>
     */
    public function appendUploadedFiles(Product $product, array $files, int $positionOffset, callable $storeFile): array
    {
        $created = [];
        foreach ($files as $idx => $file) {
            if (!$file instanceof UploadedFile || !$file->isValid()) {
                continue;
            }
            $path = $storeFile($file);
            $position = $positionOffset + (int) $idx;
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'position' => $position,
            ]);
            $created[] = ['path' => $path, 'position' => $position];
        }

        return $created;
    }
}
