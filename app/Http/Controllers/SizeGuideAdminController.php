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
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|mimes:pdf|max:10240', // Max 10MB per PDF
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
                
                $path = $file->store('size-guides/pdfs', 'public');

                SizeGuide::create([
                    'name' => $nameWithoutExtension,
                    'slug' => Str::slug($nameWithoutExtension) . '-' . uniqid(),
                    'image_path' => $path, // Using image_path column to store PDF path
                    'is_active' => true,
                    'sort_order' => SizeGuide::count(),
                ]);
            }
        }

        return redirect()->route('admin.size-guides.index')->with('success', 'Size guides uploaded successfully.');
    }

    public function edit(SizeGuide $sizeGuide)
    {
        return view('admin.size-guides.edit', compact('sizeGuide'));
    }

    public function update(Request $request, SizeGuide $sizeGuide)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [];

        if ($request->hasFile('file')) {
            // Delete old file
            if ($sizeGuide->image_path) {
                Storage::disk('public')->delete($sizeGuide->image_path);
            }
            
            // Store new file
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
            
            $data['image_path'] = $file->store('size-guides/pdfs', 'public');
            $data['name'] = $nameWithoutExtension;
            $data['slug'] = Str::slug($nameWithoutExtension) . '-' . uniqid();
        }

        if (!empty($data)) {
            $sizeGuide->update($data);
        }

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
