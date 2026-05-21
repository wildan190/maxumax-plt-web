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

    public function sizeGuide()
    {
        return view('pages.size-guide');
    }

    public function customization()
    {
        return view('pages.customization');
    }

    public function projects()
    {
        return view('pages.projects');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function contactUs()
    {
        return view('pages.contact-us');
    }

    public function submitContactUs(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\Inquiry::create($validated);

        return back()->with('success', 'Thank you for your message!');
    }
}
