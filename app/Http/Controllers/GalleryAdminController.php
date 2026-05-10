<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gallery\StoreGalleryRequest;
use App\Http\Requests\Gallery\UpdateGalleryRequest;
use App\Models\Gallery;
use App\Repositories\Gallery\GalleryRepository;
use App\Services\Gallery\GalleryAdminService;
use App\ViewModels\Gallery\GalleryAdminViewModel;

class GalleryAdminController extends Controller
{
    public function __construct(
        protected GalleryRepository $galleryRepository,
        protected GalleryAdminService $galleryAdminService,
    ) {}

    public function index()
    {
        $galleries = $this->galleryRepository->paginateLatest(20);
        page_breadcrumbs(breadcrumbs(...GalleryAdminViewModel::indexBreadcrumbs()));

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        page_breadcrumbs(breadcrumbs(...GalleryAdminViewModel::createBreadcrumbs()));

        return view('admin.galleries.create');
    }

    public function show(Gallery $gallery)
    {
        return redirect()->route('admin.galleries.edit', $gallery);
    }

    public function store(StoreGalleryRequest $request)
    {
        $this->galleryAdminService->storeFromRequest($request);

        return redirect()->route('admin.galleries.index')->with('success', 'Image added successfully');
    }

    public function edit(Gallery $gallery)
    {
        page_breadcrumbs(breadcrumbs(...GalleryAdminViewModel::editBreadcrumbs($gallery)));

        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        $this->galleryAdminService->updateFromRequest($request, $gallery);

        return redirect()->route('admin.galleries.index')->with('success', 'Image updated successfully');
    }

    public function destroy(Gallery $gallery)
    {
        $this->galleryAdminService->deleteWithStoredImage($gallery);

        return redirect()->route('admin.galleries.index')->with('success', 'Image deleted successfully');
    }
}
