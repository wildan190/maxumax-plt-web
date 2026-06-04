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
        $sizeGuides = \App\Models\SizeGuide::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.size-guide', compact('sizeGuides'));
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
            ->get();

        return view('pages.projects', compact('trustedProjects'));
    }

    public function projectDetail($slug)
    {
        // Try to find by ID first
        if (is_numeric($slug)) {
            $project = \App\Models\LandingProjectItem::find($slug);
            if ($project) {
                return view('pages.project-detail', compact('project'));
            }
        }

        // Try to find by slugified title
        $project = \App\Models\LandingProjectItem::all()->first(function ($p) use ($slug) {
            return \Illuminate\Support\Str::slug($p->title) === $slug;
        });

        if ($project) {
            return view('pages.project-detail', compact('project'));
        }

        // Fallback to category if it matches
        return $this->projectCategory($slug);
    }

    public function projectCategory($category)
    {
        // Support both lowercase and title case
        $validCategories = ['Futsal', 'Football', 'Corporate'];
        $found = false;
        foreach ($validCategories as $c) {
            if (strtolower($c) === strtolower($category)) {
                $category = $c;
                $found = true;
                break;
            }
        }

        if (!$found) {
            abort(404);
        }

        $projects = \App\Models\LandingProjectItem::where('category', $category)
            ->orderBy('sort_order')
            ->get();

        return view('pages.project-category', compact('projects', 'category'));
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
