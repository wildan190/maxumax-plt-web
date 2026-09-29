<?php

namespace App\Repositories\Preorder;

use App\Models\Preorder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreorderListingRepository
{
    /** @return Builder<Preorder> */
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

    /** @return Builder<Preorder> */
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

        // N+1 fix: 1 query with groupBy instead of 4 separate count() queries
        // reorder() removes inherited ORDER BY (from base query) to avoid MySQL only_full_group_by error
        $allQuery = clone $query;
        $statusCounts = $allQuery
            ->reorder()
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status');

        $totalCount = $statusCounts->sum();
        $counts = [
            'total'     => (int) $totalCount,
            'pending'   => (int) ($statusCounts->get('pending', 0)),
            'confirmed' => (int) ($statusCounts->get('confirmed', 0)),
            'paid'      => (int) ($statusCounts->get('paid', 0)),
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

        // N+1 fix: 1 query aggregate instead of 3 separate Preorder::count() calls
        $countRows = Preorder::query()
            ->join('products', 'preorders.product_id', '=', 'products.id')
            ->selectRaw('products.available_for_preorder, products.is_active, COUNT(*) as cnt')
            ->groupByRaw('products.available_for_preorder, products.is_active')
            ->get();

        $countAll      = (int) $countRows->sum('cnt');
        $countPreorder = (int) $countRows->where('available_for_preorder', true)->sum('cnt');
        $countOrder    = (int) $countRows->where('available_for_preorder', false)->where('is_active', true)->sum('cnt');

        $counts = [
            'all'      => $countAll,
            'preorder' => $countPreorder,
            'order'    => $countOrder,
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
