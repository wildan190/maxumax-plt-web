@extends('layouts.app')

@section('page-title', 'Order History')
@section('page-subtitle', 'Comprehensive log of all transactions and order status updates.')

@section('content')
<div x-data="{ activeMobileId: null }" class="space-y-6">
    {{-- Header & Search --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="w-full lg:w-auto">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0">
                @foreach(['all' => 'All', 'preorder' => 'Preorder', 'order' => 'Order'] as $key => $label)
                    <a href="{{ route('admin.orders.history', ['type' => $key]) }}" 
                        class="px-4 py-2 text-sm font-bold rounded-lg whitespace-nowrap transition-all {{ ($type ?? 'all') === $key ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        {{ $label }} <span class="ml-1 opacity-60 text-xs">{{ $counts[$key] ?? 0 }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <form method="GET" action="{{ route('admin.orders.history') }}" class="relative w-full lg:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-feather="search" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
            </div>
            <input type="text" name="search" placeholder="Search name, email, phone..." value="{{ request('search') }}"
                class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
        </form>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Order #</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Qty</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Last Update</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $o)
                        @php
                            $ptype = $o->product && $o->product->available_for_preorder ? 'Preorder' : 'Order';
                            $last = $o->histories->sortByDesc('created_at')->first();
                            
                            $statusConfig = match($o->status) {
                                'paid' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'label' => 'Paid'],
                                'confirmed' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'label' => 'Confirmed'],
                                'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'label' => 'Pending'],
                                default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'label' => ucfirst($o->status)]
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">{{ $o->order_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900">{{ $o->name }}</span>
                                    <span class="text-xs text-slate-500">{{ $o->email ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $ptype === 'Preorder' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $ptype }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col max-w-[180px]">
                                    <span class="font-medium text-slate-700 truncate">{{ $o->product->name ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $o->jersey_type ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $o->quantity }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $last ? $last->created_at->format('M d, Y H:i') : $o->updated_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $o) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200 transition-colors gap-1.5">
                                    <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500 font-medium italic">
                                No history records found.
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

    {{-- Mobile List View --}}
    <div class="md:hidden space-y-4">
        @forelse($orders as $o)
            @php
                $ptype = $o->product && $o->product->available_for_preorder ? 'Preorder' : 'Order';
                $last = $o->histories->sortByDesc('created_at')->first();
                $statusConfig = match($o->status) {
                    'paid' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Paid'],
                    'confirmed' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Confirmed'],
                    'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Pending'],
                    default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => ucfirst($o->status)]
                };
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div @click="activeMobileId = (activeMobileId === {{ $o->id }} ? null : {{ $o->id }})" class="p-4 flex justify-between items-start cursor-pointer hover:bg-slate-50 transition-colors">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-bold text-indigo-600">#{{ $o->order_number }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase {{ $ptype === 'Preorder' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $ptype }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-900">{{ $o->name }}</h4>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                {{ $statusConfig['label'] }}
                            </span>
                        </div>
                    </div>
                    <i data-feather="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeMobileId === {{ $o->id }} ? 'rotate-180' : ''"></i>
                </div>
                
                <div x-show="activeMobileId === {{ $o->id }}" x-collapse>
                    <div class="px-4 pb-4 pt-2 border-t border-slate-100 space-y-3 bg-slate-50/50">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Contact</p>
                                <p class="text-sm text-slate-700">{{ $o->email ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $o->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Quantity</p>
                                <p class="text-sm font-bold text-slate-900">{{ $o->quantity }} pcs</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Product</p>
                            <p class="text-sm font-medium text-slate-800">{{ $o->product->name ?? '-' }}</p>
                            <p class="text-xs text-slate-500">{{ $o->jersey_type ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Last Update</p>
                            <p class="text-xs text-slate-600">{{ $last ? $last->created_at->format('M d, Y H:i') : $o->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                        <a href="{{ route('admin.orders.show', $o) }}" class="block w-full py-2.5 bg-indigo-600 text-white text-center text-sm font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-600/10">
                            View Full Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-12 text-center bg-white rounded-2xl border border-slate-200">
                <p class="text-slate-500 font-medium">No history found.</p>
            </div>
        @endforelse

        @if($orders->hasPages())
            <div class="py-4 flex justify-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
