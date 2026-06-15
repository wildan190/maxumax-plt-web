<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function paginateForAdmin(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $query = Product::orderBy('created_at', 'desc');
        
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        return $query->paginate($perPage)->appends(['search' => $search]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Product
    {
        return Product::create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Product $product, array $attributes): bool
    {
        return $product->update($attributes);
    }

    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }

    /**
     * Chunk products with variants and gallery for CSV export.
     *
     * @param  callable(\Illuminate\Support\Collection<int, Product>): void  $callback
     */
    public function chunkWithRelationsForExport(int $chunkSize, callable $callback): void
    {
        Product::with(['variants', 'images'])
            ->chunk($chunkSize, function ($products) use ($callback) {
                $callback($products);
            });
    }
}
