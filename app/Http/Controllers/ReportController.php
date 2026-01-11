<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
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

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $currentCurrency = $request->input('currency', session('currency', 'MYR'));
        if ($request->has('currency')) {
            session(['currency' => $currentCurrency]);
        }

        // Query for successful orders
        $query = Preorder::query()
            ->whereIn('status', ['paid', 'confirmed', 'shipped', 'delivered', 'completed'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Fetch data for manual processing
        $allOrders = $query->get(['total_amount', 'currency', 'quantity', 'created_at', 'product_id']);

        // Refund Data
        $refundQuery = Preorder::query()
            ->where('status', 'refunded')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);
            
        $refundOrders = $refundQuery->get(['total_amount', 'currency']);
        $totalRefunded = 0;
        foreach ($refundOrders as $order) {
            $totalRefunded += $this->convert(
                $order->total_amount, 
                $order->currency ?? 'MYR', 
                $currentCurrency
            );
        }
        $refundedOrdersCount = $refundOrders->count();

        // Summary Stats
        $totalRevenue = 0;
        $totalItems = 0;
        
        foreach ($allOrders as $order) {
            $totalRevenue += $this->convert(
                $order->total_amount, 
                $order->currency ?? 'MYR', 
                $currentCurrency
            );
            $totalItems += $order->quantity;
        }
        
        $totalOrders = $allOrders->count();

        // Daily Sales Chart Data
        $dailyData = [];
        
        foreach ($allOrders as $order) {
            $date = $order->created_at->format('Y-m-d');
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = 0;
            }
            $dailyData[$date] += $this->convert(
                $order->total_amount, 
                $order->currency ?? 'MYR', 
                $currentCurrency
            );
        }
        
        ksort($dailyData);
        
        $chartLabels = [];
        $chartData = [];
        
        foreach ($dailyData as $date => $amount) {
            $chartLabels[] = Carbon::parse($date)->format('d M');
            $chartData[] = $amount;
        }

        // Product Performance (Top selling products in this period)
        // We need to re-query with product relationship or load it
        $productStats = [];
        
        // Eager load products for the list we already fetched? 
        // Or simpler: Group by product_id in PHP.
        // We need product details, so we can fetch products separately or use relations.
        // Let's use the query again with eager loading for processing top products
        
        // Optimization: We already have product_ids.
        foreach ($allOrders as $order) {
            $pid = $order->product_id;
            if (!$pid) continue; // Skip if no product id
            
            if (!isset($productStats[$pid])) {
                $productStats[$pid] = [
                    'product_id' => $pid,
                    'total_qty' => 0,
                    'total_sales' => 0
                ];
            }
            
            $productStats[$pid]['total_qty'] += $order->quantity;
            $productStats[$pid]['total_sales'] += $this->convert(
                $order->total_amount, 
                $order->currency ?? 'MYR', 
                $currentCurrency
            );
        }
        
        // Sort by sales desc
        usort($productStats, function($a, $b) {
            return $b['total_sales'] <=> $a['total_sales'];
        });
        
        $topStats = array_slice($productStats, 0, 5);
        
        // Hydrate product models
        $productIds = array_column($topStats, 'product_id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        $topProducts = collect($topStats)->map(function($stat) use ($products) {
            $stat['product'] = $products[$stat['product_id']] ?? null;
            return (object) $stat;
        });

        // Detailed Transaction List (Keep pagination)
        $transactions = Preorder::query()
            ->whereIn('status', ['paid', 'confirmed', 'shipped', 'delivered', 'completed'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with('product')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Breadcrumbs
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Sales Report', 'url' => route('admin.reports.index')]
        ));
        
        $currencySymbol = match($currentCurrency) {
            'MYR' => 'RM',
            'BND' => '$',
            'IDR' => 'Rp',
            default => 'RM'
        };

        return view('admin.reports.index', compact(
            'startDate', 
            'endDate', 
            'totalRevenue', 
            'totalOrders', 
            'totalItems',
            'chartLabels',
            'chartData',
            'topProducts',
            'transactions',
            'currentCurrency',
            'currencySymbol'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $fileName = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Order Number', 'Date', 'Customer Name', 'Product', 'Quantity', 'Amount', 'Status');

        $callback = function() use($startDate, $endDate, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            Preorder::query()
                ->whereIn('status', ['paid', 'confirmed', 'shipped', 'delivered', 'completed'])
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->with('product')
                ->chunk(100, function($orders) use($file) {
                    foreach ($orders as $order) {
                        $row['Order Number']  = $order->order_number;
                        $row['Date']    = $order->created_at->format('Y-m-d H:i');
                        $row['Customer Name']    = $order->name;
                        $row['Product']  = $order->product ? $order->product->name : ($order->jersey_type ?? 'N/A');
                        $row['Quantity']  = $order->quantity;
                        $row['Amount']  = $order->total_amount;
                        $row['Status']  = ucfirst($order->status);

                        fputcsv($file, array($row['Order Number'], $row['Date'], $row['Customer Name'], $row['Product'], $row['Quantity'], $row['Amount'], $row['Status']));
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
