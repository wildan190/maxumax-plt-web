<?php

namespace App\Http\Controllers;

use App\Models\SizeGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SizeGuideAdminController extends Controller
{
    public function index()
    {
        $guides = SizeGuide::orderBy('sort_order')->get();
        return view('admin.size-guides.index', compact('guides'));
    }

    public function create()
    {
        return view('admin.size-guides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:webp,png|max:2048',
            'is_active' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('size-guides', 'public');
        }

        SizeGuide::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => SizeGuide::count(),
        ]);

        return redirect()->route('admin.size-guides.index')->with('success', 'Size guide created successfully.');
    }

    public function edit(SizeGuide $sizeGuide)
    {
        return view('admin.size-guides.edit', compact('sizeGuide'));
    }

    public function update(Request $request, SizeGuide $sizeGuide)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:webp,png|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            if ($sizeGuide->image_path) {
                Storage::disk('public')->delete($sizeGuide->image_path);
            }
            $data['image_path'] = $request->file('image')->store('size-guides', 'public');
        }

        $sizeGuide->update($data);

        return redirect()->route('admin.size-guides.index')->with('success', 'Size guide updated successfully.');
    }

    public function destroy(SizeGuide $sizeGuide)
    {
        if ($sizeGuide->image_path) {
            Storage::disk('public')->delete($sizeGuide->image_path);
        }
        $sizeGuide->delete();
        return redirect()->route('admin.size-guides.index')->with('success', 'Size guide deleted.');
    }
}
