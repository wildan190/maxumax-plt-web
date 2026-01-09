<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:5120'], // max 5MB per image
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            // Limit to max 2 images
            $images = array_slice($images, 0, 2);
            
            foreach ($images as $image) {
                $path = $image->store('feedback', 'public');
                $imagePaths[] = $path;
            }
        }

        if (!empty($imagePaths)) {
            $data['images'] = $imagePaths;
        }

        Feedback::create($data);

        return back()->with('success', 'Terima kasih atas feedback Anda!');
    }
}
