@extends('layouts.public')

@section('title', 'Gallery - Maxumax')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-24">
        <div class="text-center mb-20">
            <span class="text-blue-600 font-black uppercase tracking-[0.3em] text-sm mb-4 inline-block">Visual
                Archive</span>
            <h1 class="text-4xl md:text-7xl font-black text-slate-900 mb-6 tracking-tighter uppercase italic">Our
                Collection.</h1>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">A showcase of precision craftsmanship and
                athletic excellence.</p>
        </div>

        @if($galleries->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                @foreach($galleries as $gallery)
                    <div
                        class="group bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 hover:shadow-2xl hover:shadow-blue-600/5 transition-all duration-500">
                        <div class="relative aspect-[4/5] overflow-hidden bg-slate-50 flex items-center justify-center p-6">
                            <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}"
                                class="w-full h-full object-contain transition-transform duration-1000 group-hover:scale-105">

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end p-10">
                                <div>
                                    <h3 class="text-white font-black text-2xl mb-2">{{ $gallery->title }}</h3>
                                    @if($gallery->description)
                                        <p class="text-white/70 text-sm font-medium leading-relaxed">{{ $gallery->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="p-8 border-t border-slate-50">
                            <h3 class="font-black text-slate-900 text-xl">{{ $gallery->title }}</h3>
                            <p class="text-slate-500 text-sm font-medium mt-1">{{ Str::limit($gallery->description, 60) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $galleries->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-gray-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 text-lg">No images available in the gallery yet.</p>
            </div>
        @endif
    </div>
@endsection