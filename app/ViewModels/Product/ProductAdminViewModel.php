<?php

namespace App\ViewModels\Product;

use App\Models\Product;

class ProductAdminViewModel
{
    /**
     * @return array<int, array{label: string, url: string}>
     */
    public static function indexBreadcrumbTrail(): array
    {
        return [
            ['label' => 'Products', 'url' => route('admin.products.index')],
        ];
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    public static function createBreadcrumbTrail(): array
    {
        return [
            ['label' => 'Products', 'url' => route('admin.products.index')],
            ['label' => 'Create', 'url' => route('admin.products.create')],
        ];
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    public static function editBreadcrumbTrail(Product $product): array
    {
        return [
            ['label' => 'Products', 'url' => route('admin.products.index')],
            ['label' => 'Edit', 'url' => route('admin.products.edit', $product)],
        ];
    }
}
