@extends('layouts.public')

@section('title', 'Visual Archive - Maxumax')

@section('content')
    <div class="bg-black min-h-screen pt-32 pb-40 px-6 overflow-hidden relative">
        <!-- Cinematic Background -->
        <div
            class="absolute top-0 left-0 w-full h-[60vh] bg-gradient-to-b from-blue-600/10 via-transparent to-transparent pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Header Section -->
            <div class="text-center mb-20 animate-fade-in">
                <span class="text-blue-500 font-black uppercase tracking-[0.4em] text-[10px] mb-6 inline-block">Visual
                    Archive</span>
                <h1 class="text-5xl md:text-8xl font-black text-white tracking-tighter uppercase italic leading-[0.8] mb-8">
                    Our <span class="text-blue-500">Collection.</span>
                </h1>
                <p class="text-white/40 max-w-2xl mx-auto font-black uppercase tracking-widest text-[10px] leading-relaxed">
                    A clinical showcase of precision craftsmanship and athletic excellence. Every pixel documented for the
                    record.
                </p>
            </div>

            @if($galleries->count() > 0)
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-8">
                    @foreach($galleries as $gallery)
                        <div
                            class="group relative bg-[#111111] border border-white/5 rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:border-blue-500/30 hover:shadow-[0_0_50px_rgba(37,99,235,0.1)]">
                            <!-- Image Container -->
                            <div class="relative aspect-square md:aspect-[4/5] overflow-hidden bg-black/40 flex items-center justify-center p-3 md:p-8">
                                <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}"
                                    class="w-full h-full object-contain transition-transform duration-1000 group-hover:scale-110">

                                <!-- Hover Overlay Info -->
                                <div
                                    class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
                                </div>
                            </div>

                            <!-- Static Info Section -->
                            <div class="p-3 md:p-10 border-t border-white/5">
                                <div class="flex items-center justify-between mb-1.5 md:mb-4">
                                    <h3 class="font-black text-white text-[10px] md:text-xl uppercase italic tracking-tighter">
                                        {{ $gallery->title }}</h3>
                                    <div
                                        class="w-5 h-5 md:w-8 md:h-8 rounded-full border border-white/10 flex items-center justify-center group-hover:bg-blue-500 group-hover:border-blue-500 transition-all">
                                        <i data-feather="maximize-2"
                                            class="text-white/20 group-hover:text-black w-2 h-2 md:w-3 md:h-3 transition-colors"></i>
                                    </div>
                                </div>
                                <p class="text-white/30 text-[7px] md:text-[10px] font-black uppercase tracking-widest leading-relaxed">
                                    {{ Str::limit($gallery->description, 50) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-20">
                    {{ $galleries->links() }}
                </div>
            @else
                <div class="py-40 text-center border-2 border-dashed border-white/5 rounded-[3rem] animate-pulse">
                    <i data-feather="image" class="mx-auto text-white/5 mb-8" style="width:64px;height:64px"></i>
                    <h2 class="text-white/20 font-black uppercase tracking-[0.5em] text-sm italic">Transmission Null. No records
                        found.</h2>
                </div>
            @endif
        </div>

        <!-- Decorative Footer ID -->
        <div class="mt-40 text-center">
            <p class="text-[9px] text-white/10 font-black uppercase tracking-[0.5em]">MAXUMAX VISUAL DATALINK ESTABLISHED //
                {{ date('Y') }}
            </p>
        </div>
    </div>
@endsection