<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ImportProductsRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Services\Product\ProductCsvService;
use App\Services\Product\ProductService;
use App\ViewModels\Product\ProductAdminViewModel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductService $productService,
        protected ProductCsvService $productCsvService,
    ) {}

    public function index()
    {
        $search = request('search');
        $productList = $this->productRepository->paginateForAdmin(10, $search);
        page_breadcrumbs(breadcrumbs(...ProductAdminViewModel::indexBreadcrumbTrail()));

        return view('admin.products.index', ['products' => $productList, 'search' => $search]);
    }

    public function create()
    {
        page_breadcrumbs(breadcrumbs(...ProductAdminViewModel::createBreadcrumbTrail()));

        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->create($request);

        return redirect()->route('admin.products.index')->with('success', 'Product created');
    }

    public function edit(Product $product)
    {
        page_breadcrumbs(breadcrumbs(...ProductAdminViewModel::editBreadcrumbTrail($product)));

        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->update($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated');
    }

    public function toggleHomepage(\Illuminate\Http\Request $request, Product $product)
    {
        $product->update([
            'add_to_homepage' => $request->boolean('add_to_homepage')
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted');
    }

    public function downloadTemplate(): StreamedResponse
    {
        return $this->productCsvService->streamTemplate();
    }

    public function export(): StreamedResponse
    {
        return $this->productCsvService->streamExport();
    }

    public function import(ImportProductsRequest $request)
    {
        $result = $this->productCsvService->importFromUploadedFile($request->file('file'));

        $msg = $result['created'] . ' product(s) imported.';
        if ($result['errors'] !== []) {
            $msg .= ' Errors: ' . implode('; ', array_slice($result['errors'], 0, 5));
            if (count($result['errors']) > 5) {
                $msg .= ' (+' . (count($result['errors']) - 5) . ' more)';
            }
        }

        return redirect()->route('admin.products.index')->with(
            $result['created'] > 0 ? 'success' : 'error',
            $msg
        );
    }

    public function reorder()
    {
        $products = Product::orderBy('position', 'asc')->get();
        page_breadcrumbs(breadcrumbs(...ProductAdminViewModel::indexBreadcrumbTrail()));

        return view('admin.products.reorder', compact('products'));
    }

    public function updateOrder(\Illuminate\Http\Request $request)
    {
        $order = $request->input('order');
        if (is_array($order)) {
            foreach ($order as $index => $id) {
                Product::where('id', $id)->update(['position' => $index]);
            }
        }

        return response()->json(['success' => true]);
    }
}
