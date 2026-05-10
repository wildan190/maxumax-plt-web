<?php

namespace App\ViewModels\Gallery;

use App\Models\Gallery;

class GalleryAdminViewModel
{
    /**
     * @return array<int, array{label: string, url: string}>
     */
    public static function indexBreadcrumbs(): array
    {
        return [
            ['label' => 'Gallery Management', 'url' => route('admin.galleries.index')],
        ];
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    public static function createBreadcrumbs(): array
    {
        return [
            ['label' => 'Gallery Management', 'url' => route('admin.galleries.index')],
            ['label' => 'Add New Image', 'url' => route('admin.galleries.create')],
        ];
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    public static function editBreadcrumbs(Gallery $gallery): array
    {
        return [
            ['label' => 'Gallery Management', 'url' => route('admin.galleries.index')],
            ['label' => 'Edit Image', 'url' => route('admin.galleries.edit', $gallery)],
        ];
    }
}
