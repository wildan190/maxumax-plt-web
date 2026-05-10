<?php

namespace App\Http\Controllers;

use App\Services\Page\PublicGalleryQueryService;

class PageController extends Controller
{
    public function gallery(PublicGalleryQueryService $publicGallery)
    {
        $galleries = $publicGallery->paginatePublicGallery(20);

        return view('gallery.index', compact('galleries'));
    }

    public function policies()
    {
        return view('pages.policies');
    }
}
