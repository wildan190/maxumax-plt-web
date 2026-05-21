@extends('layouts.public')

@section('title', 'Projects - Maxumax')

@section('content')
<section class="bg-black py-24 px-6 min-h-[40vh] flex items-center justify-center border-b border-white/5">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tight mb-6">PROJECTS SHOWCASE</h1>
        <p class="text-slate-400 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto">
            Trusted by teams, organizations and event partners across Sabah and beyond.
        </p>
    </div>
</section>

<section class="bg-[#050505] py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($trustedProjects as $item)
            <div class="group relative aspect-[4/3] rounded-2xl overflow-hidden bg-[#111] border border-white/5">
                <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 opacity-60 group-hover:opacity-100">
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent"></div>
                <div class="absolute bottom-8 left-8 right-8">
                    <p class="text-blue-400 font-bold text-xs uppercase tracking-widest mb-2">{{ $item['category'] }}</p>
                    <h4 class="text-white font-black text-xl mb-3 leading-tight">{{ $item['title'] }}</h4>
                    <p class="text-slate-300 text-sm font-medium leading-relaxed line-clamp-3">{{ $item['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
