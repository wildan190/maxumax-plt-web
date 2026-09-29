@extends('layouts.app')

@section('page-title', 'Preorder Management')
@section('page-subtitle', 'Manage and monitor all customer preorder requests.')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <form method="GET" action="{{ route('admin.preorders.index') }}" class="relative w-full md:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-feather="search" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
            </div>
            <input type="text" name="search" placeholder="Search by name, email, or phone..." value="{{ request('search') }}"
                class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
        </form>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.preorders.index', ['status' => 'pending']) }}" 
                class="px-4 py-2 text-sm font-bold rounded-lg {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                Pending
            </a>
            <a href="{{ route('admin.preorders.index', ['status' => 'confirmed']) }}" 
                class="px-4 py-2 text-sm font-bold rounded-lg {{ request('status') === 'confirmed' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                Confirmed
            </a>
            <a href="{{ route('admin.preorders.index') }}" 
                class="px-4 py-2 text-sm font-bold rounded-lg {{ !request('status') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                All
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Orders</p>
            <p class="text-2xl font-black text-slate-900">{{ number_format($counts['total']) }}</p>
        </div>
        <div class="bg-amber-50 p-5 rounded-2xl border border-amber-100 shadow-sm">
            <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-1">Pending</p>
            <p class="text-2xl font-black text-amber-600">{{ number_format($counts['pending']) }}</p>
        </div>
        <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-100 shadow-sm">
            <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">Confirmed</p>
            <p class="text-2xl font-black text-indigo-600">{{ number_format($counts['confirmed']) }}</p>
        </div>
        <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 shadow-sm">
            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1">Paid</p>
            <p class="text-2xl font-black text-emerald-600">{{ number_format($counts['paid']) }}</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Jersey & Size</th>
                        <th class="px-6 py-4">Qty</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($preorders as $preorder)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-md">#{{ $preorder->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900">{{ $preorder->name }}</span>
                                    <span class="text-xs text-slate-500">{{ $preorder->phone }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-700">{{ $preorder->jersey_type }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Size: {{ $preorder->size }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $preorder->quantity }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-slate-400 mr-0.5">{{ $preorder->currency ?? 'MYR' }}</span>
                                <span class="font-black text-slate-900">{{ number_format($preorder->total_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.preorders.updateStatus', $preorder) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    @php
                                        $statusClass = match($preorder->status) {
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200 focus:border-amber-400',
                                            'confirmed' => 'bg-indigo-50 text-indigo-700 border-indigo-200 focus:border-indigo-400',
                                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200 focus:border-emerald-400',
                                            'pickup' => 'bg-sky-50 text-sky-700 border-sky-200 focus:border-sky-400',
                                            'delivered' => 'bg-teal-50 text-teal-700 border-teal-200 focus:border-teal-400',
                                            'cancelled', 'refunded' => 'bg-rose-50 text-rose-700 border-rose-200 focus:border-rose-400',
                                            default => 'bg-slate-50 text-slate-700 border-slate-200 focus:border-slate-400'
                                        };
                                    @endphp
                                    <select name="status" onchange="if(confirm('Ubah status preorder ini ke ' + this.value.toUpperCase() + '?')) { this.form.submit(); } else { this.value = '{{ $preorder->status }}'; }"
                                        class="text-[11px] font-black uppercase tracking-wider py-1 px-2.5 rounded-lg border cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all {{ $statusClass }}">
                                        <option value="pending" {{ $preorder->status === 'pending' ? 'selected' : '' }}>PENDING</option>
                                        <option value="confirmed" {{ $preorder->status === 'confirmed' ? 'selected' : '' }}>CONFIRMED</option>
                                        <option value="paid" {{ $preorder->status === 'paid' ? 'selected' : '' }}>PAID</option>
                                        <option value="pickup" {{ $preorder->status === 'pickup' ? 'selected' : '' }}>PICKUP</option>
                                        <option value="delivered" {{ $preorder->status === 'delivered' ? 'selected' : '' }}>DELIVERED</option>
                                        <option value="cancelled" {{ $preorder->status === 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
                                        <option value="refunded" {{ $preorder->status === 'refunded' ? 'selected' : '' }}>REFUNDED</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($preorder->status === 'pending')
                                        <form method="POST" action="{{ route('admin.preorders.confirm', $preorder) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-indigo-600 hover:text-indigo-700 bg-indigo-50/60 hover:bg-indigo-100 rounded-lg transition-colors" title="Confirm">
                                                <i data-feather="check-circle" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @elseif($preorder->status === 'confirmed')
                                        <form method="POST" action="{{ route('admin.preorders.markPaid', $preorder) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-emerald-600 hover:text-emerald-700 bg-emerald-50/60 hover:bg-emerald-100 rounded-lg transition-colors" title="Mark as Paid">
                                                <i data-feather="dollar-sign" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('admin.preorders.show', $preorder) }}" class="p-2 text-blue-600 hover:text-blue-700 bg-blue-50/60 hover:bg-blue-100 rounded-lg transition-colors" title="View Details">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.preorders.destroy', $preorder) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-500 hover:text-rose-600 bg-rose-50/60 hover:bg-rose-100 rounded-lg transition-colors" title="Delete">
                                            <i data-feather="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 font-medium italic">
                                No preorder data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($preorders->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $preorders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
