<?php

namespace App\Services\Product;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Product\ProductImageRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductVariantRepository;

class ProductService
{
    public function __construct(
        protected ProductRepository $products,
        protected ProductImageRepository $images,
        protected ProductVariantRepository $variants,
        protected ProductImageStorageService $imageStorage,
    ) {}

    public function create(StoreProductRequest $request): Product
    {
        $attrs = $request->toPersistableAttributes();
        $gallery = [];

        if ($request->hasFile('image')) {
            $attrs['image_path'] = $this->imageStorage->storeUploaded($request->file('image'));
        }

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (is_array($files)) {
                foreach ($files as $idx => $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                        $gallery[] = [
                            'path' => $this->imageStorage->storeUploaded($file),
                            'position' => $idx,
                        ];
                    }
                }
            }
        }

        if (!isset($attrs['image_path']) && $gallery !== []) {
            $main = array_shift($gallery);
            $attrs['image_path'] = $main['path'];
            // Re-index gallery positions
            foreach ($gallery as $idx => &$g) {
                $g['position'] = $idx;
            }
        }

        $product = $this->products->create($attrs);

        if ($gallery !== []) {
            $this->images->createGalleryRows($product, $gallery);
        }

        $variantsPayload = $request->normalizedVariants();
        if ($variantsPayload !== []) {
            $this->variants->createManyForProduct($product, $variantsPayload);
        }

        return $product;
    }

    public function update(UpdateProductRequest $request, Product $product): void
    {
        $attrs = $request->toPersistableAttributes($product);

        if ($request->hasFile('image')) {
            $attrs['image_path'] = $this->imageStorage->storeUploaded($request->file('image'));
        } elseif ($request->shouldDeleteMainImage()) {
            $attrs['image_path'] = null;
        }

        $this->products->update($product, $attrs);

        // Handle gallery image deletions
        $deletedIds = $request->deletedImageIds();
        if (!empty($deletedIds)) {
            $this->images->deleteMany($product, $deletedIds);
        }

        $existingImageCount = (int) $product->images()->count();

        // Handle image position updates for existing images
        $imagePositions = $request->imagePositions();
        if (!empty($imagePositions)) {
            $this->images->updateImagePositions($product, $imagePositions);
        }

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (is_array($files)) {
                $this->images->appendUploadedFiles(
                    $product,
                    $files,
                    $existingImageCount,
                    fn (\Illuminate\Http\UploadedFile $f) => $this->imageStorage->storeUploaded($f)
                );
            }
        }

        if ($request->has('variants')) {
            $this->variants->syncFromForm($product, $request->variantsInput());
        }

        // --- Post-Update Image Consolidation ---
        $product->refresh();
        
        // 1. If main image is missing, promote the first gallery image
        if (!$product->image_path) {
            $firstGallery = $product->images()->orderBy('position', 'asc')->first();
            if ($firstGallery) {
                $this->products->update($product, ['image_path' => $firstGallery->path]);
                $firstGallery->delete();
            }
        }
        
        // 2. Ensure no duplicates (gallery image with same path as main image)
        if ($product->image_path) {
            $product->images()->where('path', $product->image_path)->delete();
        }
    }

    public function delete(Product $product): void
    {
        $this->products->delete($product);
    }
}
