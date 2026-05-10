@extends('layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Complaints', 'url' => route('admin.complaints.index')]
    ];
@endphp

@section('page-title', 'Complaints Management')
@section('page-subtitle', 'Track and resolve customer refund or replacement requests.')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Filter Status</label>
                <select name="status" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    <option value="">All Statuses</option>
                    @foreach(['pending', 'approved', 'rejected', 'completed', 'expired'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Filter Type</label>
                <select name="type" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    <option value="">All Types</option>
                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                    <option value="replacement" {{ request('type') == 'replacement' ? 'selected' : '' }}>Replacement</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2.5 bg-slate-900 text-white text-sm font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10 flex items-center justify-center gap-2">
                    <i data-feather="filter" class="w-4 h-4"></i>
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Order Reference</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Submitted</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($complaints as $complaint)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-md">#{{ $complaint->id }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-indigo-600 font-bold">
                                {{ $complaint->preorder->order_number }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                                    {{ $complaint->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusBadge = match($complaint->status) {
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        'completed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusBadge }}">
                                    {{ $complaint->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                                {{ $complaint->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.complaints.show', $complaint) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200 transition-colors gap-1.5">
                                    <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                    Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium italic">
                                No complaints found matching filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile View -->
    <div class="md:hidden space-y-4" x-data="{ activeId: null }">
        @forelse($complaints as $complaint)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div @click="activeId = (activeId === {{ $complaint->id }} ? null : {{ $complaint->id }})" class="p-4 cursor-pointer hover:bg-slate-50 transition-colors flex justify-between items-start">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-[10px] font-bold text-slate-400">#{{ $complaint->id }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-slate-100 text-slate-600">{{ $complaint->type }}</span>
                        </div>
                        <h4 class="font-bold text-slate-900 font-mono text-sm">{{ $complaint->preorder->order_number }}</h4>
                        @php
                            $statusBadge = match($complaint->status) {
                                'pending' => 'bg-amber-100 text-amber-700',
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'rejected' => 'bg-rose-100 text-rose-700',
                                'completed' => 'bg-blue-100 text-blue-700',
                                default => 'bg-slate-100 text-slate-700'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $statusBadge }}">
                            {{ $complaint->status }}
                        </span>
                    </div>
                    <i data-feather="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeId === {{ $complaint->id }} ? 'rotate-180' : ''"></i>
                </div>
                
                <div x-show="activeId === {{ $complaint->id }}" x-collapse>
                    <div class="px-4 pb-4 pt-2 border-t border-slate-100 bg-slate-50/50 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Submitted</p>
                                <p class="text-xs font-bold text-slate-700">{{ $complaint->created_at->format('M j, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($complaint->reason)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Reason Preview</p>
                                <p class="text-xs text-slate-600 italic leading-relaxed">{{ Str::limit($complaint->reason, 100) }}</p>
                            </div>
                        @endif
                        <a href="{{ route('admin.complaints.show', $complaint) }}" class="block w-full py-2.5 bg-indigo-600 text-white text-center text-xs font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/10">
                            View Full Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-12 text-center bg-white rounded-2xl border border-slate-200">
                <p class="text-slate-500 font-medium">No complaints found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($complaints->hasPages())
        <div class="pt-4 flex justify-center">
            {{ $complaints->links() }}
        </div>
    @endif
</div>
@endsection
