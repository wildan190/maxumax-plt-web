<!-- Breadcrumb Partial -->
<nav class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar" aria-label="Breadcrumb">
    <a href="{{ route('dashboard') }}" class="flex-shrink-0 p-2 bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all shadow-sm">
        <i data-feather="home" class="w-4 h-4"></i>
    </a>

    @foreach ($breadcrumbs as $breadcrumb)
        <div class="flex items-center gap-2 flex-shrink-0">
            <i data-feather="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
            
            @if (!$loop->last)
                <a href="{{ $breadcrumb['url'] }}" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-500 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all shadow-sm">
                    {{ $breadcrumb['label'] }}
                </a>
            @else
                <span class="px-3 py-1.5 bg-slate-900 border border-slate-900 rounded-lg text-xs font-bold text-white shadow-lg shadow-slate-900/20">
                    {{ $breadcrumb['label'] }}
                </span>
            @endif
        </div>
    @endforeach
</nav>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
