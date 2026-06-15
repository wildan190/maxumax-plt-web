@extends('layouts.public')

@section('title', 'Visual Archive - Maxumax')

@section('content')
    <section class="bg-white py-12 md:py-20 px-6 border-b border-[#E8E8E3]">
        <div style="max-width: 1280px; margin: 0 auto; text-center;">
            <span class="text-[#155EEF] font-black uppercase tracking-[0.3em] text-[10px] mb-4 inline-block">Visual Archive</span>
            <h1 class="text-2xl md:text-4xl font-black text-[#111111] tracking-tighter uppercase italic leading-tight mb-4">Our Collection.</h1>
            <p class="text-[#666666] text-sm md:text-base font-medium max-w-2xl mx-auto">A clinical showcase of precision craftsmanship and athletic excellence.</p>
        </div>
    </section>

    <section class="bg-white py-12 px-6">
        <div style="max-width: 1280px; margin: 0 auto;">
            @if($galleries->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
                    @foreach($galleries as $gallery)
                        <div class="group bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl overflow-hidden transition-all duration-300 hover:border-[#155EEF] hover:shadow-md">
                            <div class="aspect-square overflow-hidden">
                                <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                            <div class="p-3">
                                <h3 class="font-black text-[#111111] text-[10px] md:text-xs uppercase italic tracking-tighter mb-1">{{ $gallery->title }}</h3>
                                <p class="text-[#666666] text-[8px] md:text-[10px] font-bold uppercase tracking-widest leading-tight line-clamp-2">{{ Str::limit($gallery->description, 60) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($galleries->hasPages())
                    <div class="mt-12 border-t border-[#E8E8E3] pt-8 flex justify-center">
                        <div class="inline-flex items-center gap-1">
                            @if(!$galleries->onFirstPage())
                                <a href="{{ $galleries->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#F7F7F5] text-[#666666] hover:bg-[#155EEF] hover:text-white transition-all border border-[#E8E8E3]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </a>
                            @endif

                            @foreach ($galleries->getUrlRange(1, $galleries->lastPage()) as $page => $url)
                                @if ($page == $galleries->currentPage())
                                    <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#155EEF] text-white font-black text-xs shadow-md">
                                        {{ $page }}
                                    </span>
                                @elseif ($page == 1 || $page == $galleries->lastPage() || ($page >= $galleries->currentPage() - 1 && $page <= $galleries->currentPage() + 1))
                                    <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#F7F7F5] text-[#666666] hover:bg-[#155EEF] hover:text-white transition-all border border-[#E8E8E3] font-black text-xs">
                                        {{ $page }}
                                    </a>
                                @elseif ($page == 2 || $page == $galleries->lastPage() - 1)
                                    <span class="w-10 h-10 flex items-center justify-center text-[#999999]">...</span>
                                @endif
                            @endforeach

                            @if($galleries->hasMorePages())
                                <a href="{{ $galleries->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#F7F7F5] text-[#666666] hover:bg-[#155EEF] hover:text-white transition-all border border-[#E8E8E3]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-20 border-2 border-dashed border-[#E8E8E3] rounded-2xl bg-[#F7F7F5]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-12 h-12 mx-auto text-[#E8E8E3] mb-4">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    <h2 class="text-[#999999] font-black uppercase tracking-[0.3em] text-sm italic">Visual Archive Empty</h2>
                </div>
            @endif
        </div>
    </section>
@endsection
