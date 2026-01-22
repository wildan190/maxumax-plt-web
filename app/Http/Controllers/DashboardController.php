<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Preorder;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get currency configuration.
     */
    private function getCurrencyRate(string $currency): float
    {
        $rates = [
            'MYR' => 1,
            'BND' => 1.05,
            'IDR' => 5200,
        ];

        return $rates[$currency] ?? 1;
    }

    /**
     * Convert amount between currencies.
     */
    private function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $baseAmount = $amount / $this->getCurrencyRate($from);
        return $baseAmount * $this->getCurrencyRate($to);
    }

    /**
     * Show the dashboard.
     */
    public function __invoke(Request $request)
    {
        $currentCurrency = $request->input('currency', session('currency', 'MYR'));
        // Persist currency if changed via request
        if ($request->has('currency')) {
            session(['currency' => $currentCurrency]);
        }

        // 1. Total Stats
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

        // Refund Stats
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

        // Growth (Orders this month vs last month)
        $thisMonthOrders = Preorder::whereMonth('created_at', Carbon::now()->month)->count();
        $lastMonthOrders = Preorder::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $growth = 0;
        if ($lastMonthOrders > 0) {
            $growth = (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100;
        } else {
            $growth = $thisMonthOrders > 0 ? 100 : 0;
        }

        // 2. Chart Data (Last 7 days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d M');

            $dayOrders = Preorder::whereDate('created_at', $date->format('Y-m-d'))
                ->whereIn('status', ['paid', 'confirmed', 'shipped', 'delivered', 'completed'])
                ->get(['total_amount', 'currency']);

            $dayTotal = 0;
            foreach ($dayOrders as $order) {
                $dayTotal += $this->convert(
                    (float) $order->total_amount,
                    $order->currency ?? 'MYR',
                    $currentCurrency
                );
            }
            $chartData[] = $dayTotal;
        }

        // 3. Recent Activity (Recent Orders)
        $recentOrders = Preorder::with('product')->latest()->take(5)->get();

        // 4. Task List / Attention Needed
        $pendingRefunds = Preorder::where('refund_status', 'pending')->count();
        $pendingComplaints = \App\Models\Complaint::where('status', 'pending')->count();
        $ordersToPack = Preorder::whereIn('status', ['paid', 'confirmed'])
            ->where(function ($q) {
                $q->whereNull('shipping_status')->orWhere('shipping_status', 'pending');
            })->count();
        $lowStockProducts = Product::where('stock', '<', 10)->take(5)->get();

        // Optional: Add breadcrumbs if needed
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
        ];

        $currencySymbol = match ($currentCurrency) {
            'MYR' => 'RM',
            'BND' => '$',
            'IDR' => 'Rp',
            default => 'RM'
        };

        return view('admin.dashboard', [
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
        ]);
    }
}
