<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Products', 'url' => route('admin.products.index')]
        ));

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Products', 'url' => route('admin.products.index')],
            ['label' => 'Create', 'url' => route('admin.products.create')]
        ));

        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'collection' => 'nullable|string|max:100',
            'jersey_type' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'stock' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'available_for_preorder' => 'sometimes|boolean',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',
            'variants.*.name' => 'nullable|string|max:100',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            if (is_array($images) && count($images) > 4) {
                return back()->withErrors(['images' => 'Max 4 images allowed'])->withInput();
            }
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $gallery = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                if ($file->isValid()) {
                    $gallery[] = [
                        'path' => $file->store('products', 'public'),
                        'position' => $idx,
                    ];
                }
            }
            if (!isset($data['image_path']) && count($gallery) > 0) {
                $data['image_path'] = $gallery[0]['path'];
            }
        }

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        $data['uuid'] = (string) Str::uuid();
        $data['is_active'] = $request->boolean('is_active');
        $data['available_for_preorder'] = $request->boolean('available_for_preorder');
        $data['stock'] = $request->input('stock', 0);
        $data['sku'] = $request->input('sku');

        $product = Product::create($data);
        if (!empty($gallery)) {
            foreach ($gallery as $g) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $g['path'],
                    'position' => $g['position'],
                ]);
            }
        }

        // Create variants if provided
        if ($request->has('variants')) {
            foreach ($request->input('variants', []) as $variantData) {
                if (!empty($variantData['name'])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $variantData['name'],
                        'stock' => $variantData['stock'] ?? 0,
                        'sku' => $variantData['sku'] ?? null,
                        'is_available' => true,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created');
    }

    public function edit(Product $product)
    {
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Products', 'url' => route('admin.products.index')],
            ['label' => 'Edit', 'url' => route('admin.products.edit', $product)]
        ));

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'collection' => 'nullable|string|max:100',
            'jersey_type' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'stock' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'available_for_preorder' => 'sometimes|boolean',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.name' => 'nullable|string|max:100',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        if ($request->hasFile('images')) {
            $existingCount = (int) $product->images()->count();
            $images = $request->file('images');
            if (is_array($images) && ($existingCount + count($images)) > 4) {
                return back()->withErrors(['images' => 'Max 4 images allowed total'])->withInput();
            }
            foreach ($request->file('images') as $idx => $file) {
                if ($file->isValid()) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $file->store('products', 'public'),
                        'position' => $existingCount + $idx,
                    ]);
                }
            }
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['available_for_preorder'] = $request->boolean('available_for_preorder');
        $data['stock'] = $request->input('stock', 0);
        $data['sku'] = $request->input('sku');

        // update slug if name changed
        if ($product->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        }

        $product->update($data);

        // Sync variants if provided
        if ($request->has('variants')) {
            $variantIds = [];
            foreach ($request->input('variants', []) as $variantData) {
                if (!empty($variantData['name'])) {
                    if (isset($variantData['id']) && $variantData['id']) {
                        // Update existing variant
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant && $variant->product_id == $product->id) {
                            $variant->update([
                                'name' => $variantData['name'],
                                'stock' => $variantData['stock'] ?? 0,
                                'sku' => $variantData['sku'] ?? null,
                            ]);
                            $variantIds[] = $variant->id;
                        }
                    } else {
                        // Create new variant
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $variantData['name'],
                            'stock' => $variantData['stock'] ?? 0,
                            'sku' => $variantData['sku'] ?? null,
                            'is_available' => true,
                        ]);
                        $variantIds[] = $variant->id;
                    }
                }
            }
            // Delete variants that are not in the request
            $product->variants()->whereNotIn('id', $variantIds)->delete();
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted');
    }

    /**
     * Download product import template (CSV with headers + dummy data).
     */
    public function downloadTemplate(): StreamedResponse
    {
        $filename = 'product_import_template_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return new StreamedResponse(function () {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($out, [
                'name',
                'description',
                'jersey_type',
                'price',
                'sku',
                'stock',
                'is_active',
                'available_for_preorder',
                'variants',
                'images',
            ]);
            // Dummy data rows
            $dummy = [
                ['Player Home Jersey', 'Premium home jersey for league', 'Player Home', '199.00', 'JER-HOME-01', '50', '1', '1', 'S:10:SKU-S;M:20:SKU-M;L:15:SKU-L', 'https://placehold.co/600x400;https://placehold.co/600x400'],
                ['Player Away Jersey', 'Away kit jersey', 'Player Away', '199.00', 'JER-AWAY-01', '30', '1', '1', 'S:5:;M:10:;L:10:', 'https://placehold.co/600x400'],
                ['GK Home', 'Goalkeeper home jersey', 'GK Home', '219.00', 'JER-GK-01', '15', '1', '0', 'M:5:;L:10:', ''],
            ];
            foreach ($dummy as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, 200, $headers);
    }

    /**
     * Export products to CSV.
     */
    public function export(): StreamedResponse
    {
        $filename = 'products_export_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return new StreamedResponse(function () {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($out, [
                'name',
                'description',
                'jersey_type',
                'price',
                'sku',
                'stock',
                'is_active',
                'available_for_preorder',
                'variants',
                'images',
            ]);

            Product::with(['variants', 'images'])->chunk(100, function ($products) use ($out) {
                foreach ($products as $p) {
                    $variants = $p->variants->map(function ($v) {
                        return "{$v->name}:{$v->stock}:{$v->sku}";
                    })->implode(';');

                    $images = $p->images->map(function ($img) {
                        return asset('storage/' . $img->path);
                    })->implode(';');

                    fputcsv($out, [
                        $p->name,
                        $p->description,
                        $p->jersey_type,
                        $p->price,
                        $p->sku,
                        $p->stock,
                        $p->is_active ? '1' : '0',
                        $p->available_for_preorder ? '1' : '0',
                        $variants,
                        $images,
                    ]);
                }
            });
            fclose($out);
        }, 200, $headers);
    }

    /**
     * Import products from CSV (Excel can save as CSV UTF-8).
     * Columns: name, description, jersey_type, price, sku, stock, is_active, available_for_preorder, variants
     * Variants format: "Name:stock:sku;Name2:stock2:sku2" (sku optional)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $rows = [];
        $handle = fopen($path, 'r');
        if (!$handle) {
            return redirect()->route('admin.products.index')->with('error', 'Could not read file.');
        }
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
            rewind($handle);
        }
        $header = fgetcsv($handle);
        if (!$header || strtolower(trim($header[0] ?? '')) !== 'name') {
            fclose($handle);
            return redirect()->route('admin.products.index')->with('error', 'Invalid template. First column must be "name". Use the downloaded template.');
        }
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 4) continue;
            $name = trim($row[0] ?? '');
            if ($name === '') continue;
            $rows[] = [
                'name' => $name,
                'description' => trim($row[1] ?? ''),
                'jersey_type' => trim($row[2] ?? 'General'),
                'price' => (float) preg_replace('/[^0-9.]/', '', $row[3] ?? '0'),
                'sku' => trim($row[4] ?? ''),
                'stock' => (int) ($row[5] ?? 0),
                'is_active' => in_array(strtolower(trim($row[6] ?? '1')), ['1', 'yes', 'true', 'active'], true),
                'available_for_preorder' => in_array(strtolower(trim($row[7] ?? '0')), ['1', 'yes', 'true'], true),
                'variants' => trim($row[8] ?? ''),
                'images' => trim($row[9] ?? ''),
            ];
        }
        fclose($handle);

        if (empty($rows)) {
            return redirect()->route('admin.products.index')->with('error', 'No valid rows to import.');
        }

        $created = 0;
        $errors = [];
        foreach ($rows as $idx => $row) {
            try {
                $slug = Str::slug($row['name']) . '-' . Str::random(6);
                $product = Product::create([
                    'name' => $row['name'],
                    'slug' => $slug,
                    'uuid' => (string) Str::uuid(),
                    'description' => $row['description'] ?: null,
                    'jersey_type' => $row['jersey_type'] ?: 'General',
                    'price' => $row['price'],
                    'sku' => $row['sku'] ?: null,
                    'stock' => $row['stock'],
                    'is_active' => $row['is_active'],
                    'available_for_preorder' => $row['available_for_preorder'],
                ]);
                if (!empty($row['variants'])) {
                    foreach (array_filter(explode(';', $row['variants'])) as $v) {
                        $parts = explode(':', $v, 3);
                        $vName = trim($parts[0] ?? '');
                        if ($vName === '') continue;
                        $vStock = (int) ($parts[1] ?? 0);
                        $vSku = isset($parts[2]) ? trim($parts[2]) : null;
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $vName,
                            'stock' => $vStock,
                            'sku' => $vSku ?: null,
                            'is_available' => true,
                        ]);
                    }
                }

                // Handle images
                if (!empty($row['images'])) {
                    $imageUrls = array_filter(explode(';', $row['images']));
                    foreach ($imageUrls as $idx => $url) {
                        try {
                            $url = trim($url);
                            if (filter_var($url, FILTER_VALIDATE_URL)) {
                                $response = Http::get($url);
                                if ($response->successful()) {
                                    $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                                    $imageName = 'products/' . Str::random(40) . '.' . $extension;
                                    Storage::disk('public')->put($imageName, $response->body());

                                    ProductImage::create([
                                        'product_id' => $product->id,
                                        'path' => $imageName,
                                        'position' => $idx,
                                    ]);

                                    // Set as main image if it's the first one
                                    if ($idx === 0) {
                                        $product->update(['image_path' => $imageName]);
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            // Continue with other images if one fails
                        }
                    }
                }

                $created++;
            } catch (\Throwable $e) {
                $errors[] = 'Row ' . ($idx + 2) . ' (' . $row['name'] . '): ' . $e->getMessage();
            }
        }

        $msg = $created . ' product(s) imported.';
        if (!empty($errors)) {
            $msg .= ' Errors: ' . implode('; ', array_slice($errors, 0, 5));
            if (count($errors) > 5) $msg .= ' (+' . (count($errors) - 5) . ' more)';
        }
        return redirect()->route('admin.products.index')->with(
            $created > 0 ? 'success' : 'error',
            $msg
        );
    }
}
