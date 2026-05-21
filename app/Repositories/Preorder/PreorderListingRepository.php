<?php

namespace App\Repositories\Preorder;

use App\Models\Preorder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PreorderListingRepository
{
    public function retailOrdersQuery(): Builder
    {
        return Preorder::query()
            ->with(['product', 'variant'])
            ->whereHas('product', function ($q) {
                $q->where('available_for_preorder', false)
                    ->where('is_active', true);
            })
            ->orderByDesc('created_at');
    }

    public function preorderOnlyQuery(): Builder
    {
        return Preorder::query()
            ->with(['product', 'variant'])
            ->whereHas('product', function ($q) {
                $q->where('available_for_preorder', true);
            })
            ->orderByDesc('created_at');
    }

    public function applyFilters(Builder $query, Request $request, bool $searchIncludesOrderNumber): Builder
    {
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search, $searchIncludesOrderNumber) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
                if ($searchIncludesOrderNumber) {
                    $q->orWhere('order_number', 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        return $query;
    }

    /**
     * @return array{records: LengthAwarePaginator, counts: array{total: int, pending: int, confirmed: int, paid: int}}
     */
    public function paginateWithStatusCounts(Builder $baseQuery, Request $request, bool $searchIncludesOrderNumber, int $perPage = 10): array
    {
        $query = clone $baseQuery;
        $this->applyFilters($query, $request, $searchIncludesOrderNumber);

        $allQuery = clone $query;
        $counts = [
            'total' => $allQuery->count(),
            'pending' => $allQuery->clone()->where('status', 'pending')->count(),
            'confirmed' => $allQuery->clone()->where('status', 'confirmed')->count(),
            'paid' => $allQuery->clone()->where('status', 'paid')->count(),
        ];

        $records = $query->paginate($perPage)->withQueryString();

        return compact('records', 'counts');
    }

    /**
     * @return array{orders: \Illuminate\Database\Eloquent\Collection<int, Preorder>, type: string, counts: array{all: int, preorder: int, order: int}}
     */
    public function paginateOrderHistory(Request $request, int $perPage = 10): array
    {
        $type = $request->query('type', 'all');
        $query = Preorder::query()->with(['product', 'histories'])->orderByDesc('created_at');

        if ($type === 'preorder') {
            $query->whereHas('product', function ($q) {
                $q->where('available_for_preorder', true);
            });
        } elseif ($type === 'order') {
            $query->whereHas('product', function ($q) {
                $q->where('available_for_preorder', false)->where('is_active', true);
            });
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate($perPage)->withQueryString();

        $counts = [
            'all' => Preorder::count(),
            'preorder' => Preorder::whereHas('product', fn ($q) => $q->where('available_for_preorder', true))->count(),
            'order' => Preorder::whereHas('product', fn ($q) => $q->where('available_for_preorder', false)->where('is_active', true))->count(),
        ];

        return compact('orders', 'type', 'counts');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Preorder>
     */
    public function retailPrintList(Request $request)
    {
        $query = $this->applyFilters($this->retailOrdersQuery(), $request, true);

        return $query->with(['product', 'variant'])->get();
    }
}
