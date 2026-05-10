<?php

namespace App\Services\Product;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageStorageService
{
    public function storeUploaded(UploadedFile $file, string $directory = 'products', string $disk = 'public'): string
    {
        return $file->store($directory, $disk);
    }

    /**
     * Download a remote image into public disk. Returns storage path or null on failure.
     */
    public function storeFromUrl(string $url, string $directory = 'products', string $disk = 'public'): ?string
    {
        $url = trim($url);
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $response = Http::get($url);
            if (!$response->successful()) {
                return null;
            }
            $extension = pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $imageName = $directory . '/' . Str::random(40) . '.' . $extension;
            Storage::disk($disk)->put($imageName, $response->body());

            return $imageName;
        } catch (\Throwable) {
            return null;
        }
    }
}
