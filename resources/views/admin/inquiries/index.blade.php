@extends('layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Inquiries', 'url' => route('admin.inquiries.index')]
    ];
@endphp

@section('page-title', 'Contact Inquiries')
@section('page-subtitle', 'Manage and respond to messages submitted by users on the Contact Us form.')

@section('content')
<div class="space-y-6">
    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Submitted</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-slate-50/50 transition-colors group {{ !$inquiry->is_read ? 'bg-blue-50/40' : '' }}">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-md">#{{ $inquiry->id }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $inquiry->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium">
                                {{ $inquiry->email }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">
                                {{ $inquiry->subject ?: '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if(!$inquiry->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200">
                                        Unread
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                                {{ $inquiry->created_at->format('M j, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200 transition-colors gap-1.5">
                                    <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                    View
                                </a>
                                <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium italic">
                                No inquiries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile View -->
    <div class="md:hidden space-y-4" x-data="{ activeId: null }">
        @forelse($inquiries as $inquiry)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden {{ !$inquiry->is_read ? 'border-l-4 border-l-blue-500' : '' }}">
                <div @click="activeId = (activeId === {{ $inquiry->id }} ? null : {{ $inquiry->id }})" class="p-4 cursor-pointer hover:bg-slate-50 transition-colors flex justify-between items-start">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-[10px] font-bold text-slate-400">#{{ $inquiry->id }}</span>
                            @if(!$inquiry->is_read)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-blue-100 text-blue-700">Unread</span>
                            @endif
                        </div>
                        <h4 class="font-bold text-slate-900 text-sm">{{ $inquiry->name }}</h4>
                        <p class="text-xs text-slate-500">{{ $inquiry->subject ?: 'No Subject' }}</p>
                    </div>
                    <i data-feather="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeId === {{ $inquiry->id }} ? 'rotate-180' : ''"></i>
                </div>
                
                <div x-show="activeId === {{ $inquiry->id }}" x-collapse>
                    <div class="px-4 pb-4 pt-2 border-t border-slate-100 bg-slate-50/50 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</p>
                                <p class="text-xs font-bold text-slate-700">{{ $inquiry->email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Submitted</p>
                                <p class="text-xs font-bold text-slate-700">{{ $inquiry->created_at->format('M j, Y H:i') }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Message Preview</p>
                            <p class="text-xs text-slate-600 italic leading-relaxed">{{ Str::limit($inquiry->message, 100) }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="flex-1 py-2 bg-indigo-600 text-white text-center text-xs font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all">
                                View Full Details
                            </a>
                            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry?');" class="inline flex-none">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl border border-slate-200 transition-colors">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-12 text-center bg-white rounded-2xl border border-slate-200">
                <p class="text-slate-500 font-medium">No inquiries found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($inquiries->hasPages())
        <div class="pt-4 flex justify-center">
            {{ $inquiries->links() }}
        </div>
    @endif
</div>
@endsection
