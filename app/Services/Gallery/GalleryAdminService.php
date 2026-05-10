<?php

namespace App\Services\Gallery;

use App\Models\Gallery;
use App\Repositories\Gallery\GalleryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryAdminService
{
    public function __construct(
        protected GalleryRepository $galleries,
    ) {}

    public function storeFromRequest(Request $request): Gallery
    {
        $path = $request->file('image')->store('gallery', 'public');

        return $this->galleries->create([
            'title' => $request->title,
            'image_path' => $path,
            'is_highlight' => $request->boolean('is_highlight'),
            'description' => $request->description,
        ]);
    }

    public function updateFromRequest(Request $request, Gallery $gallery): void
    {
        $data = [
            'title' => $request->title,
            'is_highlight' => $request->boolean('is_highlight'),
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $this->galleries->update($gallery, $data);
    }

    public function deleteWithStoredImage(Gallery $gallery): void
    {
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $this->galleries->delete($gallery);
    }
}
