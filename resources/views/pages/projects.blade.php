@extends('layouts.public')

@section('title', 'Projects - Maxumax')

@section('content')
<section class="bg-[#F7F7F5] py-12 px-4 md:px-6 min-h-[30vh] flex items-center justify-center border-b border-[#E8E8E3]">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-2xl md:text-4xl font-black text-[#111111] uppercase tracking-tight mb-4">PROJECTS SHOWCASE</h1>
        <p class="text-[#666666] text-sm md:text-base leading-relaxed max-w-2xl mx-auto">
            Trusted by teams, organizations and event partners across Sabah and beyond.
        </p>
    </div>
</section>

<section class="bg-white py-12 px-4 md:px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($trustedProjects as $item)
            <a href="{{ route('pages.projects.detail', \Illuminate\Support\Str::slug($item->title)) }}" class="group relative aspect-[4/3] rounded-2xl overflow-hidden bg-[#F7F7F5] border border-[#E8E8E3]">
                @if($item->image_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="text-[#E8E8E3] font-black italic uppercase text-xs">No Cover Asset</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-[#111111]/90 via-transparent to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                <div class="absolute bottom-4 left-4 right-4">
                    <p class="text-[#155EEF] font-bold text-xs uppercase tracking-widest mb-1">{{ $item->category }}</p>
                    <h4 class="text-white font-black text-lg leading-tight mb-1">{{ $item->title }}</h4>
                    <p class="text-white/70 text-[10px] line-clamp-2">{{ $item->description }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
