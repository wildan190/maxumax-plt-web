<?php

namespace App\Services\Page;

use App\Repositories\Gallery\GalleryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PublicGalleryQueryService
{
    public function __construct(
        protected GalleryRepository $galleryRepository,
    ) {}

    public function paginatePublicGallery(int $perPage = 20): LengthAwarePaginator
    {
        return $this->galleryRepository->paginateLatest($perPage);
    }
}
