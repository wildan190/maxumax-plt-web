@extends('layouts.app')

@section('page-title', 'Customer Profile')
@section('page-subtitle', 'Purchase history and order summary for this customer.')

@section('content')
<div class="space-y-6">
    <!-- Back -->
    <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors gap-2">
        <i data-feather="arrow-left" class="w-4 h-4"></i>
        Back to Customers
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Info Card -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-8 text-center border-b border-slate-100 bg-gradient-to-br from-indigo-50 to-slate-50">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-black mx-auto mb-4 shadow-lg shadow-indigo-500/25">
                        {{ strtoupper(substr($customer['name'], 0, 1)) }}
                    </div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $customer['name'] }}</h2>
                    @if($customer['email'])
                        <p class="text-sm text-indigo-600 font-medium mt-1">{{ $customer['email'] }}</p>
                    @endif
                    <p class="text-sm text-slate-500 mt-1">{{ $customer['phone'] }}</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0">
                            <i data-feather="map-pin" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Address</p>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $customer['address'] ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0">
                            <i data-feather="clock" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">First Order</p>
                            <p class="text-sm font-bold text-slate-700">{{ $customer['first_order_at']?->format('d M Y') ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0">
                            <i data-feather="refresh-cw" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Last Order</p>
                            <p class="text-sm font-bold text-slate-700">{{ $customer['last_order_at']?->format('d M Y') ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Orders</p>
                    <p class="text-2xl font-black text-slate-900">{{ $customer['total_orders'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Total placed</p>
                </div>
                <div class="bg-emerald-50 rounded-2xl border border-emerald-100 shadow-sm p-5">
                    <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1">Spend</p>
                    <p class="text-2xl font-black text-emerald-700">{{ number_format($customer['total_spend'], 0, ',', '.') }}</p>
                    <p class="text-xs text-emerald-600 mt-0.5">{{ $customer['currency'] ?? 'MYR' }}</p>
                </div>
                <div class="bg-indigo-50 rounded-2xl border border-indigo-100 shadow-sm p-5">
                    <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">Avg Order</p>
                    <p class="text-2xl font-black text-indigo-700">{{ $customer['total_orders'] > 0 ? number_format($customer['total_spend'] / $customer['total_orders'], 0, ',', '.') : '0' }}</p>
                    <p class="text-xs text-indigo-600 mt-0.5">Per order</p>
                </div>
                <div class="bg-amber-50 rounded-2xl border border-amber-100 shadow-sm p-5">
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-1">Pending</p>
                    <p class="text-2xl font-black text-amber-700">{{ $customer['pending_orders'] }}</p>
                    <p class="text-xs text-amber-600 mt-0.5">Awaiting</p>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Order History</h3>
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">{{ $orders->total() }} orders</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Order #</th>
                                <th class="px-6 py-4">Product</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($orders as $order)
                                @php
                                    $badge = match($order->status) {
                                        'pending'   => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'confirmed' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                        'paid'      => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'shipped'   => 'bg-sky-100 text-sky-700 border-sky-200',
                                        'delivered' => 'bg-teal-100 text-teal-700 border-teal-200',
                                        'cancelled', 'refunded' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default     => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-slate-700 truncate max-w-[130px] block">{{ optional($order->product)->name ?? $order->jersey_type }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">Qty: {{ $order->quantity }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-bold text-slate-400 mr-0.5">{{ $order->currency ?? 'MYR' }}</span>
                                        <span class="font-black text-slate-900">{{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badge }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 font-medium whitespace-nowrap">
                                        {{ $order->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="p-2 text-indigo-600 hover:text-indigo-700 bg-indigo-50/60 hover:bg-indigo-100 rounded-lg transition-colors inline-flex items-center" title="View Order">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium italic">No orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
