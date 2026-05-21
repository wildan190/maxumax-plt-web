<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductImageRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductVariantRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductCsvService
{
    public function __construct(
        protected ProductRepository $products,
        protected ProductImageRepository $images,
        protected ProductVariantRepository $variants,
        protected ProductImageStorageService $imageStorage,
    ) {}

    /**
     * @return array<int, string>
     */
    protected function csvHeaderRow(): array
    {
        return [
            'name',
            'description',
            'category',
            'collections',
            'material',
            'gender',
            'fit',
            'color',
            'jersey_type',
            'price',
            'stock',
            'is_active',
            'available_for_preorder',
            'add_to_homepage',
            'on_sale',
            'discounted_price',
            'size_guide',
            'position',
            'variants',
            'images',
        ];
    }

    public function streamTemplate(): StreamedResponse
    {
        $filename = 'product_import_template_' . date('Y-m-d') . '.csv';

        return new StreamedResponse(function () {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, $this->csvHeaderRow());
            foreach ($this->dummyTemplateRows() as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * @return array<int, array<int, string>>
     */
    protected function dummyTemplateRows(): array
    {
        return [
            ['Player Home Jersey', 'Premium home jersey for league', 'Jerseys', 'Football Series;Outdoor Series', 'Dry-fit', 'Men', 'Slim Fit', 'Red/White', 'Player Home', '199.00', '50', '1', '1', '1', '1', '149.00', '', '1', 'S:10;M:20;L:15', 'https://placehold.co/600x400;https://placehold.co/600x400'],
            ['Polo Classic', 'Classic cotton polo', 'Polos', 'Casual / Lifestyle', 'Cotton', 'Unisex', 'Regular Fit', 'Navy', '', '89.00', '30', '1', '0', '0', '0', '', '', '2', 'M:10;L:10;XL:10', 'https://placehold.co/600x400'],
            ['Tracksuit Pro', 'Professional tracksuits', 'Tracksuits', 'Run & Training Series', 'Polyester', 'Men', 'Regular Fit', 'Black', '', '259.00', '15', '1', '0', '0', '0', '', '', '3', 'L:15', ''],
        ];
    }

    public function streamExport(): StreamedResponse
    {
        $filename = 'products_export_' . date('Y-m-d') . '.csv';

        return new StreamedResponse(function () {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, $this->csvHeaderRow());

            $this->products->chunkWithRelationsForExport(100, function ($products) use ($out) {
                foreach ($products as $p) {
                    $variants = $p->variants->map(function ($v) {
                        return "{$v->name}:{$v->stock}";
                    })->implode(';');

                    $images = $p->images->map(function ($img) {
                        return asset('storage/' . $img->path);
                    })->implode(';');

                    $collections = is_array($p->collections) ? implode(';', $p->collections) : $p->collections;

                    fputcsv($out, [
                        $p->name,
                        $p->description,
                        $p->category,
                        $collections,
                        $p->material,
                        $p->gender,
                        $p->fit,
                        $p->color,
                        $p->jersey_type,
                        $p->price,
                        $p->stock,
                        $p->is_active ? '1' : '0',
                        $p->available_for_preorder ? '1' : '0',
                        $p->add_to_homepage ? '1' : '0',
                        $p->on_sale ? '1' : '0',
                        $p->discounted_price,
                        $p->size_guide,
                        $p->position,
                        $variants,
                        $images,
                    ]);
                }
            });
            fclose($out);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * @return array{created: int, errors: array<int, string>}
     */
    public function importFromUploadedFile(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        if ($path === false || !is_readable($path)) {
            return ['created' => 0, 'errors' => ['Could not read file.']];
        }

        $rows = $this->parseImportFile($path);
        if ($rows === false) {
            return ['created' => 0, 'errors' => ['Could not read file.']];
        }
        if ($rows === null) {
            return ['created' => 0, 'errors' => ['Invalid template. First column must be "name". Use the downloaded template.']];
        }

        if ($rows === []) {
            return ['created' => 0, 'errors' => ['No valid rows to import.']];
        }

        $created = 0;
        $errors = [];

        foreach ($rows as $idx => $row) {
            try {
                $this->importSingleRow($row);
                $created++;
            } catch (\Throwable $e) {
                $errors[] = 'Row ' . ($idx + 2) . ' (' . $row['name'] . '): ' . $e->getMessage();
            }
        }

        return ['created' => $created, 'errors' => $errors];
    }

    /**
     * @return array<int, array<string, mixed>>|null|false false = unreadable, null = invalid header
     */
    protected function parseImportFile(string $path): array|null|false
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return false;
        }

        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
            rewind($handle);
        }

        $header = fgetcsv($handle);
        if (!$header || strtolower(trim($header[0] ?? '')) !== 'name') {
            fclose($handle);

            return null;
        }

        $header = array_map(function ($h) {
            return strtolower(trim((string) $h));
        }, $header);

        $indexMap = [];
        foreach ($this->csvHeaderRow() as $col) {
            $index = array_search(strtolower($col), $header);
            $indexMap[$col] = $index !== false ? $index : null;
        }

        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) {
                continue;
            }

            $getValue = function (string $col, $default = null) use ($row, $indexMap) {
                $idx = $indexMap[$col] ?? null;
                if ($idx !== null && isset($row[$idx])) {
                    return $row[$idx];
                }

                return $default;
            };

            $name = trim((string) ($getValue('name', '')));
            if ($name === '') {
                continue;
            }

            $collections = trim((string) ($getValue('collections', '')));
            $collectionsArray = $collections !== '' ? array_map('trim', explode(';', $collections)) : [];

            // Determine variants and images index with fallback
            $variantsVal = '';
            if (($indexMap['variants'] ?? null) !== null) {
                $variantsVal = (string) $getValue('variants', '');
            } elseif (isset($row[13]) && count($row) <= 15) {
                $variantsVal = (string) $row[13];
            }

            $imagesVal = '';
            if (($indexMap['images'] ?? null) !== null) {
                $imagesVal = (string) $getValue('images', '');
            } elseif (isset($row[14]) && count($row) <= 15) {
                $imagesVal = (string) $row[14];
            }

            $rows[] = [
                'name' => $name,
                'description' => trim((string) ($getValue('description', ''))),
                'category' => trim((string) ($getValue('category', 'Jerseys'))),
                'collections' => $collectionsArray,
                'material' => trim((string) ($getValue('material', ''))),
                'gender' => trim((string) ($getValue('gender', ''))),
                'fit' => trim((string) ($getValue('fit', ''))),
                'color' => trim((string) ($getValue('color', ''))),
                'jersey_type' => trim((string) ($getValue('jersey_type', ''))),
                'price' => (float) preg_replace('/[^0-9.]/', '', (string) ($getValue('price', '0'))),
                'stock' => (int) ($getValue('stock', 0)),
                'is_active' => in_array(strtolower(trim((string) ($getValue('is_active', '1')))), ['1', 'yes', 'true', 'active'], true),
                'available_for_preorder' => in_array(strtolower(trim((string) ($getValue('available_for_preorder', '0')))), ['1', 'yes', 'true'], true),
                'add_to_homepage' => in_array(strtolower(trim((string) ($getValue('add_to_homepage', '0')))), ['1', 'yes', 'true'], true),
                'on_sale' => in_array(strtolower(trim((string) ($getValue('on_sale', '0')))), ['1', 'yes', 'true'], true),
                'discounted_price' => $getValue('discounted_price') !== null && trim((string) $getValue('discounted_price')) !== '' ? (float) preg_replace('/[^0-9.]/', '', (string) $getValue('discounted_price')) : null,
                'size_guide' => $getValue('size_guide') !== null && trim((string) $getValue('size_guide')) !== '' ? trim((string) $getValue('size_guide')) : null,
                'position' => (int) ($getValue('position', 0)),
                'variants' => trim($variantsVal),
                'images' => trim($imagesVal),
            ];
        }
        fclose($handle);

        return $rows;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    protected function importSingleRow(array $row): void
    {
        $slug = Str::slug($row['name']) . '-' . Str::random(6);

        $product = $this->products->create([
            'name' => $row['name'],
            'slug' => $slug,
            'uuid' => (string) Str::uuid(),
            'description' => $row['description'] !== '' ? $row['description'] : null,
            'category' => $row['category'],
            'collections' => $row['collections'],
            'material' => $row['material'] !== '' ? $row['material'] : null,
            'gender' => $row['gender'] !== '' ? $row['gender'] : null,
            'fit' => $row['fit'] !== '' ? $row['fit'] : null,
            'color' => $row['color'] !== '' ? $row['color'] : null,
            'jersey_type' => $row['jersey_type'] !== '' ? $row['jersey_type'] : null,
            'price' => $row['price'],
            'stock' => $row['stock'],
            'is_active' => $row['is_active'],
            'available_for_preorder' => $row['available_for_preorder'],
            'add_to_homepage' => $row['add_to_homepage'],
            'on_sale' => $row['on_sale'],
            'discounted_price' => $row['discounted_price'],
            'size_guide' => $row['size_guide'],
            'position' => $row['position'],
        ]);

        if (!empty($row['variants'])) {
            $variantRows = [];
            foreach (array_filter(explode(';', $row['variants'])) as $v) {
                $parts = explode(':', $v, 2);
                $vName = trim((string) ($parts[0] ?? ''));
                if ($vName === '') {
                    continue;
                }
                $variantRows[] = [
                    'name' => $vName,
                    'stock' => (int) ($parts[1] ?? 0),
                ];
            }
            if ($variantRows !== []) {
                $this->variants->createManyForProduct($product, $variantRows);
            }
        }

        if (!empty($row['images'])) {
            $gallery = [];
            $position = 0;
            foreach (array_filter(explode(';', $row['images'])) as $url) {
                $stored = $this->imageStorage->storeFromUrl(trim($url));
                if ($stored === null) {
                    continue;
                }

                if ($position === 0) {
                    $this->products->update($product, ['image_path' => $stored]);
                } else {
                    $gallery[] = ['path' => $stored, 'position' => $position - 1];
                }
                $position++;
            }
            if ($gallery !== []) {
                $this->images->createGalleryRows($product, $gallery);
            }
        }
    }
}
