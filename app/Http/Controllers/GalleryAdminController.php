<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryAdminController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->paginate(20);
        
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Gallery Management', 'url' => route('admin.galleries.index')]
        ));

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Gallery Management', 'url' => route('admin.galleries.index')],
            ['label' => 'Add New Image', 'url' => route('admin.galleries.create')]
        ));

        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:2048', // 2MB max
            'is_highlight' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        Gallery::create([
            'title' => $request->title,
            'image_path' => $path,
            'is_highlight' => $request->boolean('is_highlight'),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Image added successfully');
    }

    public function edit(Gallery $gallery)
    {
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Gallery Management', 'url' => route('admin.galleries.index')],
            ['label' => 'Edit Image', 'url' => route('admin.galleries.edit', $gallery)]
        ));

        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_highlight' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);

        $data = [
            'title' => $request->title,
            'is_highlight' => $request->boolean('is_highlight'),
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Image updated successfully');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Image deleted successfully');
    }
}
