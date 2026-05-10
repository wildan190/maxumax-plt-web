@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name . '!')

@section('content')
<div class="space-y-8">
    <!-- Top Actions & Currency -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-3 bg-white p-1 rounded-xl shadow-sm border border-slate-200">
            <span class="px-3 py-1 text-xs font-bold text-slate-400 uppercase tracking-widest border-r border-slate-100">Currency</span>
            <form action="{{ route('dashboard') }}" method="GET" id="currencyForm" class="flex">
                <select name="currency" onchange="document.getElementById('currencyForm').submit()"
                    class="bg-transparent border-0 text-sm font-bold text-slate-900 rounded-lg focus:ring-0 cursor-pointer py-1.5 pl-3 pr-8">
                    <option value="MYR" {{ $currentCurrency == 'MYR' ? 'selected' : '' }}>MYR (RM)</option>
                    <option value="BND" {{ $currentCurrency == 'BND' ? 'selected' : '' }}>BND ($)</option>
                    <option value="IDR" {{ $currentCurrency == 'IDR' ? 'selected' : '' }}>IDR (Rp)</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <!-- Total Orders -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i data-feather="shopping-bag" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Orders</span>
            </div>
            <p class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($totalOrders) }}</p>
            <p class="mt-1 text-sm text-slate-500 font-medium">Total orders</p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-feather="dollar-sign" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Revenue</span>
            </div>
            <p class="text-3xl font-black text-slate-900 tracking-tight">
                <span class="text-lg font-bold text-slate-400 mr-1">{{ $currencySymbol }}</span>{{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-sm text-slate-500 font-medium">Total income</p>
        </div>

        <!-- Total Refunded -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-feather="refresh-ccw" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Refunded</span>
            </div>
            <p class="text-3xl font-black text-rose-600 tracking-tight">
                <span class="text-lg font-bold text-rose-300 mr-1">{{ $currencySymbol }}</span>{{ number_format($totalRefunded, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-sm text-slate-500 font-medium">{{ $refundedOrdersCount }} orders refunded</p>
        </div>

        <!-- Growth -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl {{ $growth >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center">
                    <i data-feather="{{ $growth >= 0 ? 'trending-up' : 'trending-down' }}" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Growth</span>
            </div>
            <p class="text-3xl font-black {{ $growth >= 0 ? 'text-emerald-600' : 'text-rose-600' }} tracking-tight">
                {{ $growth > 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
            </p>
            <p class="mt-1 text-sm text-slate-500 font-medium">Vs last month</p>
        </div>

        <!-- Active Products -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-feather="box" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Products</span>
            </div>
            <p class="text-3xl font-black text-slate-900 tracking-tight">{{ $activeProducts }}</p>
            <p class="mt-1 text-sm text-slate-500 font-medium">Active products</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Revenue Chart</h2>
                    <p class="text-sm text-slate-500 font-medium">Activity last 7 days</p>
                </div>
                <div class="hidden sm:block">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                        Live Data
                    </span>
                </div>
            </div>
            <div class="h-[350px] w-full relative">
                <canvas id="revenueChart" 
                    data-labels='{!! json_encode($chartLabels) !!}' 
                    data-values='{!! json_encode($chartData) !!}'
                    data-currency="{{ $currentCurrency }}"></canvas>
            </div>
        </div>

        <!-- Task List -->
        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-slate-200">
            <h2 class="text-xl font-bold text-slate-900 mb-8">Task List</h2>
            
            <div class="space-y-6">
                <!-- Refund Requests -->
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-rose-50 group-hover:text-rose-500 transition-colors">
                            <i data-feather="refresh-cw" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">Refund Requests</p>
                            <p class="text-xs text-slate-500 font-medium">Waiting for approval</p>
                        </div>
                    </div>
                    @if($pendingRefunds > 0)
                        <span class="px-3 py-1 rounded-full bg-rose-500 text-white text-xs font-black shadow-lg shadow-rose-500/20">{{ $pendingRefunds }}</span>
                    @else
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <i data-feather="check" class="w-3 h-3"></i>
                        </div>
                    @endif
                </div>

                <!-- Complaints -->
                <a href="{{ route('admin.complaints.index') }}" class="flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                            <i data-feather="message-square" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">New Complaints</p>
                            <p class="text-xs text-slate-500 font-medium">Recent customer complaints</p>
                        </div>
                    </div>
                    @if($pendingComplaints > 0)
                        <span class="px-3 py-1 rounded-full bg-indigo-500 text-white text-xs font-black shadow-lg shadow-indigo-600/20">{{ $pendingComplaints }}</span>
                    @else
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <i data-feather="check" class="w-3 h-3"></i>
                        </div>
                    @endif
                </a>

                <!-- Packing -->
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-amber-50 group-hover:text-amber-500 transition-colors">
                            <i data-feather="package" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">Needs Packing</p>
                            <p class="text-xs text-slate-500 font-medium">Orders already paid</p>
                        </div>
                    </div>
                    @if($ordersToPack > 0)
                        <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-black shadow-lg shadow-amber-500/20">{{ $ordersToPack }}</span>
                    @else
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <i data-feather="check" class="w-3 h-3"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Low Stock Section -->
            @if($lowStockProducts->count() > 0)
                <div class="mt-10 pt-8 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Low Stock</h3>
                    <div class="space-y-3">
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-700 truncate mr-2">{{ $product->name }}</span>
                                <span class="flex-shrink-0 px-2 py-0.5 rounded-md bg-rose-50 text-rose-600 text-[10px] font-black">{{ $product->stock }} left</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-10 p-4 bg-indigo-600 rounded-2xl text-white shadow-xl shadow-indigo-600/20">
                <div class="flex items-center gap-3 mb-2">
                    <i data-feather="zap" class="w-4 h-4 text-indigo-200"></i>
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-100">Quick Tip</p>
                </div>
                <p class="text-sm font-medium leading-relaxed">Use <span class="font-bold underline">MyParcel Asia</span> to process shipments automatically and faster.</p>
            </div>
        </div>

        <!-- Recent Activity (Orders) -->
        <div class="lg:col-span-3 bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-slate-900">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1">
                    View All <i data-feather="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                        <tr>
                            <th class="px-6 py-4 rounded-l-xl">Customer</th>
                            <th class="px-6 py-4">Product</th>
                            <th class="px-6 py-4">Order #</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 rounded-r-xl">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($recentOrders as $order)
                            <tr class="group hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                            {{ substr($order->name, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-900">{{ $order->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-600">
                                    {{ $order->product ? $order->product->name : 'Product Deleted' }}
                                    @if($order->quantity > 1)
                                        <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-500 ml-1">x{{ $order->quantity }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $order->order_number }}</td>
                                <td class="px-6 py-4 font-black text-slate-900">
                                    <span class="text-[10px] text-slate-400 mr-0.5">{{ $order->currency }}</span>{{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = match ($order->status) {
                                            'paid', 'completed', 'shipped', 'delivered' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'confirmed' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'cancelled', 'rejected', 'refunded' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            default => 'bg-slate-50 text-slate-600 border-slate-100'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusClasses }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400 font-medium">{{ $order->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium italic">No recent orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const data = JSON.parse(canvas.dataset.values || '[]');
        const currency = canvas.dataset.currency || 'MYR';
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: `Revenue (${currency})`,
                    data: data,
                    borderColor: '#4f46e5',
                    borderWidth: 4,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 14, weight: '700' },
                        bodyFont: { size: 13 },
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { weight: '600', size: 11 },
                            color: '#94a3b8',
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                                if (value >= 1000) return (value / 1000).toFixed(0) + 'K';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: '600', size: 11 }, color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>
@endsection
