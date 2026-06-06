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

    public function storeFromRequest(Request $request): void
    {
        $items = $request->input('items', []);
        $files = $request->file('items', []);

        foreach ($items as $index => $item) {
            if (!isset($files[$index]['image'])) continue;

            $path = $files[$index]['image']->store('gallery', 'public');

            $this->galleries->create([
                'title' => $item['title'],
                'image_path' => $path,
                'is_highlight' => isset($item['is_highlight']) && $item['is_highlight'],
                'description' => $item['description'] ?? null,
            ]);
        }
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
