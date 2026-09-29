<?php

namespace App\Services\Admin;

use App\Models\Complaint;
use App\Models\Preorder;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardMetricsService
{
    private function getCurrencyRate(string $currency): float
    {
        $rates = [
            'MYR' => 1,
            'BND' => 1.05,
            'IDR' => 5200,
        ];

        return $rates[$currency] ?? 1;
    }

    private function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $baseAmount = $amount / $this->getCurrencyRate($from);

        return $baseAmount * $this->getCurrencyRate($to);
    }

    /**
     * @return array<string, mixed>
     */
    public function buildViewData(Request $request): array
    {
        $currentCurrency = $request->input('currency', session('currency', 'MYR'));
        if ($request->has('currency')) {
            session(['currency' => $currentCurrency]);
        }

        $totalOrders = Preorder::count();

        $revenueOrders = Preorder::whereIn('status', ['paid', 'confirmed', 'shipped', 'delivered', 'completed'])
            ->get(['total_amount', 'currency']);

        $totalRevenue = 0;
        foreach ($revenueOrders as $order) {
            $totalRevenue += $this->convert(
                (float) $order->total_amount,
                $order->currency ?? 'MYR',
                $currentCurrency
            );
        }

        $refundOrders = Preorder::where('status', 'refunded')->get(['total_amount', 'currency']);
        $totalRefunded = 0;
        foreach ($refundOrders as $order) {
            $totalRefunded += $this->convert(
                (float) $order->total_amount,
                $order->currency ?? 'MYR',
                $currentCurrency
            );
        }
        $refundedOrdersCount = $refundOrders->count();

        $activeProducts = Product::where('is_active', true)->count();

        // Growth: 1 query aggregate grouped by year-month (no N+1)
        $now = Carbon::now();
        $monthCounts = Preorder::query()
            ->selectRaw('YEAR(created_at) as yr, MONTH(created_at) as mo, COUNT(*) as cnt')
            ->whereIn(DB::raw('CONCAT(YEAR(created_at), "-", LPAD(MONTH(created_at),2,"0"))'), [
                $now->format('Y-m'),
                $now->copy()->subMonth()->format('Y-m'),
            ])
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn ($r) => $r->yr . '-' . str_pad($r->mo, 2, '0', STR_PAD_LEFT));

        $thisMonthOrders = (int) ($monthCounts[$now->format('Y-m')]->cnt ?? 0);
        $lastMonthOrders = (int) ($monthCounts[$now->copy()->subMonth()->format('Y-m')]->cnt ?? 0);

        $growth = 0;
        if ($lastMonthOrders > 0) {
            $growth = (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100;
        } else {
            $growth = $thisMonthOrders > 0 ? 100 : 0;
        }

        // Chart: 1 query for last 7 days instead of 7 separate queries (N+1 fix)
        $chartStart = Carbon::now()->subDays(6)->startOfDay();
        $chartEnd = Carbon::now()->endOfDay();

        $dailyRevenue = Preorder::query()
            ->whereIn('status', ['paid', 'confirmed', 'shipped', 'delivered', 'completed'])
            ->whereBetween('created_at', [$chartStart, $chartEnd])
            ->get(['total_amount', 'currency', 'created_at'])
            ->groupBy(fn ($o) => Carbon::parse($o->created_at)->format('Y-m-d'));

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $key = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');

            $dayTotal = 0;
            foreach ($dailyRevenue->get($key, collect()) as $order) {
                $dayTotal += $this->convert(
                    (float) $order->total_amount,
                    $order->currency ?? 'MYR',
                    $currentCurrency
                );
            }
            $chartData[] = $dayTotal;
        }

        $recentOrders = Preorder::with('product')->latest()->take(5)->get();

        $pendingRefunds = Preorder::where('refund_status', 'pending')->count();
        $pendingComplaints = Complaint::where('status', 'pending')->count();
        $ordersToPack = Preorder::whereIn('status', ['paid', 'confirmed'])
            ->where(function ($q) {
                $q->whereNull('shipping_status')->orWhere('shipping_status', 'pending');
            })->count();
        $lowStockProducts = Product::where('stock', '<', 10)->take(5)->get();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
        ];

        $currencySymbol = match ($currentCurrency) {
            'MYR' => 'RM',
            'BND' => '$',
            'IDR' => 'Rp',
            default => 'RM'
        };

        return [
            'user' => $request->user(),
            'breadcrumbs' => $breadcrumbs,
            'currentCurrency' => $currentCurrency,
            'currencySymbol' => $currencySymbol,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalRefunded' => $totalRefunded,
            'refundedOrdersCount' => $refundedOrdersCount,
            'activeProducts' => $activeProducts,
            'growth' => $growth,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'recentOrders' => $recentOrders,
            'pendingRefunds' => $pendingRefunds,
            'pendingComplaints' => $pendingComplaints,
            'ordersToPack' => $ordersToPack,
            'lowStockProducts' => $lowStockProducts,
        ];
    }
}
