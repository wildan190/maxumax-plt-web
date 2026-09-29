@extends('layouts.app')

@section('page-title', 'Order Management')
@section('page-subtitle', 'Manage and monitor all customer product orders.')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="space-y-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" id="filterForm" class="flex flex-col gap-3">
            <!-- Row 1: Search + Buttons -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <!-- Search -->
                    <div class="relative w-full sm:w-80 group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-feather="search" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input type="text" name="search" placeholder="Search order #, name, email..." value="{{ request('search') }}"
                            class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>
                    <!-- Date From -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-feather="calendar" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            title="From date"
                            class="block pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-slate-700 font-medium">
                    </div>
                    <!-- Date To -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-feather="calendar" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            title="To date"
                            class="block pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-slate-700 font-medium">
                    </div>
                    <!-- Apply -->
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">
                        <i data-feather="filter" class="w-4 h-4"></i>
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'date_from', 'date_to']))
                        <a href="{{ route('admin.orders.index', array_filter(['status' => request('status')])) }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all">
                            <i data-feather="x" class="w-4 h-4"></i>
                            Clear
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'date_from', 'date_to', 'status']))
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-indigo-100">
                            <i data-feather="filter" class="w-3 h-3"></i>
                            Filtered
                        </span>
                    @endif
                    <a href="{{ route('admin.orders.print', request()->query()) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10 gap-2">
                        <i data-feather="printer" class="w-4 h-4"></i>
                        Print View
                    </a>
                    <a href="{{ route('admin.orders.export') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 gap-2">
                        <i data-feather="download" class="w-4 h-4"></i>
                        CSV
                    </a>
                </div>
            </div>

            <!-- Hidden status (preserved from tab clicks) -->
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
        </form>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('admin.orders.index', array_filter(['search' => request('search'), 'date_from' => request('date_from'), 'date_to' => request('date_to'), 'status' => 'pending'])) }}"
                class="flex-shrink-0 px-4 py-2 text-sm font-bold rounded-lg {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                Pending
                @if(isset($counts['pending']) && $counts['pending'] > 0)<span class="ml-1 opacity-70 text-xs">({{ $counts['pending'] }})</span>@endif
            </a>
            <a href="{{ route('admin.orders.index', array_filter(['search' => request('search'), 'date_from' => request('date_from'), 'date_to' => request('date_to'), 'status' => 'confirmed'])) }}"
                class="flex-shrink-0 px-4 py-2 text-sm font-bold rounded-lg {{ request('status') === 'confirmed' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                Confirmed
                @if(isset($counts['confirmed']) && $counts['confirmed'] > 0)<span class="ml-1 opacity-70 text-xs">({{ $counts['confirmed'] }})</span>@endif
            </a>
            <a href="{{ route('admin.orders.index', array_filter(['search' => request('search'), 'date_from' => request('date_from'), 'date_to' => request('date_to'), 'status' => 'paid'])) }}"
                class="flex-shrink-0 px-4 py-2 text-sm font-bold rounded-lg {{ request('status') === 'paid' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                Paid
                @if(isset($counts['paid']) && $counts['paid'] > 0)<span class="ml-1 opacity-70 text-xs">({{ $counts['paid'] }})</span>@endif
            </a>
            <a href="{{ route('admin.orders.index', array_filter(['search' => request('search'), 'date_from' => request('date_from'), 'date_to' => request('date_to')])) }}"
                class="flex-shrink-0 px-4 py-2 text-sm font-bold rounded-lg {{ !request('status') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }} transition-all">
                All
                @if(isset($counts['total']))<span class="ml-1 opacity-70 text-xs">({{ $counts['total'] }})</span>@endif
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

        <!-- Bulk Action Bar (hidden by default, shown when rows selected) -->
        <div id="bulkActionBar" class="hidden items-center justify-between px-6 py-3 bg-indigo-50 border-b border-indigo-100">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-indigo-700"><span id="selectedCount">0</span> orders selected</span>
                <span class="w-px h-4 bg-indigo-200"></span>
                <button onclick="bulkSelectAll()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Select All</button>
                <button onclick="bulkClearAll()" class="text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors">Clear</button>
            </div>
            <div class="flex items-center gap-2">
                <select id="bulkStatusSelect" class="bg-white border border-indigo-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all">
                    <option value="">Change Status To...</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="paid">Paid</option>
                    <option value="pickup">Pickup</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="refunded">Refunded</option>
                </select>
                <button onclick="bulkUpdateStatus()" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition-all shadow-sm shadow-indigo-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Apply
                </button>
                <button onclick="bulkDelete()" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-white border border-rose-200 text-rose-600 text-xs font-bold rounded-lg hover:bg-rose-50 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4h6v2"></path></svg>
                    Delete Selected
                </button>
            </div>
        </div>

        <!-- Hidden bulk forms -->
        <form id="bulkStatusForm" method="POST" action="{{ route('admin.orders.bulkUpdateStatus') }}" class="hidden">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" id="bulkStatusValue">
            <div id="bulkStatusIds"></div>
        </form>
        <form id="bulkDeleteForm" method="POST" action="{{ route('admin.orders.bulkDestroy') }}" class="hidden">
            @csrf
            @method('DELETE')
            <div id="bulkDeleteIds"></div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)"
                                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </th>
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
                        <tr class="hover:bg-slate-50/50 transition-colors group bulk-row" data-id="{{ $order->id }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}"
                                    class="row-checkbox w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                    onchange="onRowCheckboxChange()">
                            </td>
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
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    @php
                                        $statusClass = match($order->status) {
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200 focus:border-amber-400',
                                            'confirmed' => 'bg-indigo-50 text-indigo-700 border-indigo-200 focus:border-indigo-400',
                                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200 focus:border-emerald-400',
                                            'pickup' => 'bg-sky-50 text-sky-700 border-sky-200 focus:border-sky-400',
                                            'delivered' => 'bg-teal-50 text-teal-700 border-teal-200 focus:border-teal-400',
                                            'cancelled', 'refunded' => 'bg-rose-50 text-rose-700 border-rose-200 focus:border-rose-400',
                                            default => 'bg-slate-50 text-slate-700 border-slate-200 focus:border-slate-400'
                                        };
                                    @endphp
                                    <select name="status" onchange="if(confirm('Ubah status order ini ke ' + this.value.toUpperCase() + '?')) { this.form.submit(); } else { this.value = '{{ $order->status }}'; }"
                                        class="text-[11px] font-black uppercase tracking-wider py-1 px-2.5 rounded-lg border cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all {{ $statusClass }}">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>PENDING</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>CONFIRMED</option>
                                        <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>PAID</option>
                                        <option value="pickup" {{ $order->status === 'pickup' ? 'selected' : '' }}>PICKUP</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>DELIVERED</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
                                        <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>REFUNDED</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="p-2 text-indigo-600 hover:text-indigo-700 bg-indigo-50/60 hover:bg-indigo-100 rounded-lg transition-colors" title="View Details">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
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
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500 font-medium italic">
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

<script>
    // ============ BULK ACTION LOGIC ============
    function getSelectedIds() {
        return [...document.querySelectorAll('.row-checkbox:checked')].map(cb => cb.value);
    }

    function onRowCheckboxChange() {
        const total = document.querySelectorAll('.row-checkbox').length;
        const checked = getSelectedIds().length;
        document.getElementById('selectAllCheckbox').checked = (checked === total && total > 0);
        document.getElementById('selectAllCheckbox').indeterminate = (checked > 0 && checked < total);
        updateBulkBar();
    }

    function toggleSelectAll(master) {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = master.checked);
        updateBulkBar();
    }

    function bulkSelectAll() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = true);
        document.getElementById('selectAllCheckbox').checked = true;
        updateBulkBar();
    }

    function bulkClearAll() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectAllCheckbox').checked = false;
        document.getElementById('selectAllCheckbox').indeterminate = false;
        updateBulkBar();
    }

    function updateBulkBar() {
        const ids = getSelectedIds();
        const bar = document.getElementById('bulkActionBar');
        document.getElementById('selectedCount').textContent = ids.length;
        if (ids.length > 0) {
            bar.classList.remove('hidden');
            bar.classList.add('flex');
        } else {
            bar.classList.add('hidden');
            bar.classList.remove('flex');
        }
    }

    function bulkUpdateStatus() {
        const ids = getSelectedIds();
        const status = document.getElementById('bulkStatusSelect').value;
        if (!status) {
            Swal.fire({ icon: 'warning', title: 'Select a status', text: 'Please choose a target status first.', confirmButtonColor: '#4f46e5' });
            return;
        }
        if (ids.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No orders selected', text: 'Please select at least one order.', confirmButtonColor: '#4f46e5' });
            return;
        }
        Swal.fire({
            title: `Update ${ids.length} order(s)?`,
            text: `Status will be changed to: ${status.toUpperCase()}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, update all!'
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById('bulkStatusForm');
                document.getElementById('bulkStatusValue').value = status;
                const container = document.getElementById('bulkStatusIds');
                container.innerHTML = ids.map(id => `<input type="hidden" name="order_ids[]" value="${id}">`).join('');
                form.submit();
            }
        });
    }

    function bulkDelete() {
        const ids = getSelectedIds();
        if (ids.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No orders selected', text: 'Please select at least one order.', confirmButtonColor: '#ef4444' });
            return;
        }
        Swal.fire({
            title: `Delete ${ids.length} order(s)?`,
            text: 'This action cannot be undone!',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, delete all!'
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById('bulkDeleteForm');
                const container = document.getElementById('bulkDeleteIds');
                container.innerHTML = ids.map(id => `<input type="hidden" name="order_ids[]" value="${id}">`).join('');
                form.submit();
            }
        });
    }
</script>
@endsection
