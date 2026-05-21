@extends('layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Inquiries', 'url' => route('admin.inquiries.index')],
        ['label' => 'Inquiry #' . $inquiry->id, 'url' => '#']
    ];
@endphp

@section('page-title', 'Inquiry Details')
@section('page-subtitle', 'Read message and contact client.')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Header Panel -->
        <div class="bg-slate-900 text-white p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Inquiry Request</span>
                <h1 class="text-2xl font-bold font-mono tracking-tight mt-1">#{{ $inquiry->id }}</h1>
                <p class="text-xs text-slate-400 mt-0.5">Submitted {{ $inquiry->created_at->format('F j, Y g:i A') }}</p>
            </div>
            
            <div class="flex items-center gap-2">
                @if(!$inquiry->is_read)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-500 text-white shadow-lg shadow-blue-500/25">
                        Unread
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-700">
                        Read
                    </span>
                @endif
            </div>
        </div>

        <!-- Details -->
        <div class="p-6 md:p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sender Name</h3>
                    <p class="text-sm font-bold text-slate-800">{{ $inquiry->name }}</p>
                </div>
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email Address</h3>
                    <a href="mailto:{{ $inquiry->email }}" class="text-sm font-bold text-indigo-600 hover:underline inline-flex items-center gap-1.5">
                        {{ $inquiry->email }}
                        <i data-feather="mail" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Subject</h3>
                <p class="text-sm font-bold text-slate-800 bg-slate-50 px-4 py-3.5 rounded-xl border border-slate-100">
                    {{ $inquiry->subject ?: 'No Subject Provided' }}
                </p>
            </div>

            <div>
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Message</h3>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 text-sm text-slate-600 font-medium leading-relaxed whitespace-pre-wrap">
                    {{ $inquiry->message }}
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 md:px-8 md:py-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('admin.inquiries.index') }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold uppercase tracking-widest transition-colors flex items-center gap-1.5">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                Back to List
            </a>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="mailto:{{ $inquiry->email }}?subject={{ rawurlencode('Re: ' . ($inquiry->subject ?: 'Inquiry')) }}"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 py-2 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-colors gap-2">
                    <i data-feather="send" class="w-3.5 h-3.5"></i>
                    Reply via Email
                </a>

                <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry?');" class="inline flex-none">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-xl transition-colors" title="Delete Inquiry">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
