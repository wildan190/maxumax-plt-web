@extends('layouts.app')

@section('page-title', 'Customers')
@section('page-subtitle', 'Overview of all customers based on order history.')

@section('content')
<div class="space-y-6">
    <!-- Search -->
    <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <div class="relative w-full sm:w-80 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-feather="search" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
            </div>
            <input type="text" name="search" placeholder="Search name, email, phone..." value="{{ request('search') }}"
                class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">
                <i data-feather="search" class="w-4 h-4"></i>
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all">
                    <i data-feather="x" class="w-4 h-4"></i>
                    Clear
                </a>
            @endif
        </div>
    </form>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Customers</p>
            <p class="text-2xl font-black text-slate-900">{{ number_format($totalCustomers) }}</p>
        </div>
        <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 shadow-sm">
            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1">Repeat Buyers</p>
            <p class="text-2xl font-black text-emerald-700">{{ number_format($repeatCustomers) }}</p>
        </div>
        <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-100 shadow-sm">
            <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">New (30 days)</p>
            <p class="text-2xl font-black text-indigo-700">{{ number_format($newCustomers) }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4 text-center">Orders</th>
                        <th class="px-6 py-4 text-right">Total Spend</th>
                        <th class="px-6 py-4">Last Order</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-black flex-shrink-0">
                                        {{ strtoupper(substr($c->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $c->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $c->email ?? $c->phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $c->order_count > 1 ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }} text-xs font-black">{{ $c->order_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-black text-slate-900">{{ number_format($c->total_spend, 2) }}</span>
                                <span class="text-[10px] text-slate-400 ml-0.5">{{ $c->currency ?? 'MYR' }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                                {{ \Carbon\Carbon::parse($c->last_order)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.customers.show', ['email' => $c->email, 'phone' => $c->phone]) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-lg hover:bg-indigo-100 transition-colors">
                                    <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                    View Profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium italic">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
