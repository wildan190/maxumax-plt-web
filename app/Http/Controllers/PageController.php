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
        $trustedProjects = \App\Models\LandingProjectItem::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($row) {
                $service = app(\App\Services\Landing\LandingPageHomeContentService::class);
                $defaults = \App\Services\Landing\LandingPageDefaults::trustedProjects();
                
                // We don't have an index here easily, but we can find by title or just use first default as fallback
                // A better way is to use the service logic but the service is built for homepage (take 4).
                // Let's just manually resolve the URL for now.
                $path = $row->image_path;
                $img = null;
                if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                    $disk = \Illuminate\Support\Facades\Storage::disk('public');
                    $img = $disk->url($path);
                }

                return [
                    'category' => $row->category,
                    'title' => $row->title,
                    'description' => $row->description,
                    'img' => $img ?? asset('assets/img/banner1.jpeg'), // Fallback to a generic default
                ];
            });

        return view('pages.projects', compact('trustedProjects'));
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
