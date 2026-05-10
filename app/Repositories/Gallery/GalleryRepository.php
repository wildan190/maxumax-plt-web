<?php

namespace App\Repositories\Gallery;

use App\Models\Gallery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GalleryRepository
{
    public function paginateLatest(int $perPage = 20): LengthAwarePaginator
    {
        return Gallery::latest()->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Gallery
    {
        return Gallery::create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Gallery $gallery, array $attributes): bool
    {
        return $gallery->update($attributes);
    }

    public function delete(Gallery $gallery): ?bool
    {
        return $gallery->delete();
    }
}
