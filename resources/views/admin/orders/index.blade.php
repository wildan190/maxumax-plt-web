@extends('layouts.app')

@section('page-title', 'Order Management')
@section('page-subtitle', 'Manage and monitor all customer product orders.')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="relative w-full md:w-80 group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-feather="search" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input type="text" name="search" placeholder="Search order #, name, email..." value="{{ request('search') }}"
                    class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
            </form>
            <a href="{{ route('admin.orders.print', request()->query()) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10 gap-2">
                <i data-feather="printer" class="w-4 h-4"></i>
                Print View
            </a>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto">
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
                class="flex-shrink-0 px-4 py-2 text-sm font-bold rounded-lg {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                Pending
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" 
                class="flex-shrink-0 px-4 py-2 text-sm font-bold rounded-lg {{ request('status') === 'confirmed' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                Confirmed
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" 
                class="flex-shrink-0 px-4 py-2 text-sm font-bold rounded-lg {{ request('status') === 'paid' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                Paid
            </a>
            <a href="{{ route('admin.orders.index') }}" 
                class="flex-shrink-0 px-4 py-2 text-sm font-bold rounded-lg {{ !request('status') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                All
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 md:p-5 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total</p>
            <p class="text-xl md:text-2xl font-black text-slate-900">{{ number_format($counts['total']) }}</p>
        </div>
        <div class="bg-amber-50 p-4 md:p-5 rounded-2xl border border-amber-100 shadow-sm">
            <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-1">Pending</p>
            <p class="text-xl md:text-2xl font-black text-amber-600">{{ number_format($counts['pending']) }}</p>
        </div>
        <div class="bg-indigo-50 p-4 md:p-5 rounded-2xl border border-indigo-100 shadow-sm">
            <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">Confirmed</p>
            <p class="text-xl md:text-2xl font-black text-indigo-600">{{ number_format($counts['confirmed']) }}</p>
        </div>
        <div class="bg-emerald-50 p-4 md:p-5 rounded-2xl border border-emerald-100 shadow-sm">
            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1">Paid</p>
            <p class="text-xl md:text-2xl font-black text-emerald-600">{{ number_format($counts['paid']) }}</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Order #</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Qty</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900">{{ $order->name }}</span>
                                    <span class="text-xs text-slate-500">{{ $order->phone }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-700 truncate max-w-[150px]">{{ optional($order->product)->name ?? $order->jersey_type }}</span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">Size: {{ $order->size }}</span>
                                        @if($order->long_sleeve)
                                            <span class="text-[9px] font-black bg-blue-50 text-blue-600 px-1 rounded">LS</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $order->quantity }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-slate-400 mr-0.5">{{ $order->currency ?? 'MYR' }}</span>
                                <span class="font-black text-slate-900">{{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusBadge = match($order->status) {
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'confirmed' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                        'paid', 'completed', 'shipped', 'delivered' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'cancelled', 'rejected', 'refunded' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusBadge }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                            <i data-feather="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 font-medium italic">
                                No order data found.
                            </td>
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
@endsection
