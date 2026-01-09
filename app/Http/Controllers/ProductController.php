<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(20);
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
            'jersey_type' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'stock' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'available_for_preorder' => 'sometimes|boolean',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',
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
            if (! isset($data['image_path']) && count($gallery) > 0) {
                $data['image_path'] = $gallery[0]['path'];
            }
        }

        $data['slug'] = Str::slug($data['name']).'-'.Str::random(6);
        $data['uuid'] = (string) Str::uuid();
        $data['is_active'] = $request->boolean('is_active');
        $data['available_for_preorder'] = $request->boolean('available_for_preorder');
        $data['stock'] = $request->input('stock', 0);
        $data['sku'] = $request->input('sku');

        $product = Product::create($data);
        if (! empty($gallery)) {
            foreach ($gallery as $g) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $g['path'],
                    'position' => $g['position'],
                ]);
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
            'jersey_type' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'stock' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'available_for_preorder' => 'sometimes|boolean',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',
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
            $data['slug'] = Str::slug($data['name']).'-'.Str::random(6);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted');
    }
}
