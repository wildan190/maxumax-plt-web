@extends('layouts.public')

@section('title', $category . ' Projects - Maxumax')

@section('content')
<!-- Hero Section -->
<section class="bg-black py-24 px-6 min-h-[50vh] flex items-center justify-center border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-blue-600/10 to-transparent opacity-50"></div>
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <h1 class="text-white font-black text-5xl md:text-8xl italic uppercase tracking-tighter leading-none mb-6">
            {{ $category }} <span class="text-white/20">PROJECTS.</span>
        </h1>
        <p class="text-white/60 text-lg md:text-xl max-w-2xl mx-auto font-medium">
            Explore our specialized {{ strtolower($category) }} portfolio, showcasing excellence in athletic performance and design.
        </p>
    </div>
</section>

<!-- Projects Grid -->
<section class="bg-[#050505] py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="space-y-32">
            @forelse($projects as $project)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="{{ $loop->even ? 'lg:order-2' : '' }}">
                        <div class="space-y-8">
                            <div>
                                <h3 class="text-blue-400 font-black text-xs uppercase tracking-[0.3em] mb-4">Case Study</h3>
                                <h2 class="text-white font-black text-4xl md:text-6xl italic uppercase tracking-tighter leading-none mb-6">
                                    {{ $project->headline ?: $project->title }}
                                </h2>
                                @if($project->subhead)
                                    <p class="text-white/40 text-xl font-bold uppercase tracking-widest italic mb-6">{{ $project->subhead }}</p>
                                @endif
                                <p class="text-white/60 text-lg leading-relaxed">
                                    {{ $project->description }}
                                </p>
                            </div>
                            
                            @if($project->gallery && count($project->gallery) > 0)
                                <div class="grid grid-cols-2 gap-4 mt-8">
                                    @foreach(array_slice($project->gallery, 0, 4) as $imgPath)
                                        <div class="aspect-square rounded-2xl overflow-hidden border border-white/5 bg-[#111]">
                                            <img src="{{ Storage::url($imgPath) }}" alt="" class="w-full h-full object-cover hover:scale-110 transition-transform duration-700">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="{{ $loop->even ? 'lg:order-1' : '' }}">
                        <div class="aspect-[4/5] bg-gradient-to-br from-[#1a1a1a] to-[#0a0a0a] rounded-[2.5rem] border border-white/5 overflow-hidden group shadow-2xl">
                            @if($project->image_path)
                                <img src="{{ Storage::url($project->image_path) }}" alt="{{ $project->title }}" 
                                     class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700">
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20">
                    <p class="text-white/20 font-black text-2xl uppercase tracking-widest italic">More projects coming soon.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Footer CTA -->
<section class="bg-black py-32 px-6 border-t border-white/5">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-white font-black text-4xl md:text-6xl italic uppercase tracking-tighter mb-8">Start your project today.</h2>
        <a href="{{ route('pages.contact-us') }}" class="inline-flex bg-white text-black px-10 py-5 rounded-2xl font-black uppercase tracking-[0.2em] hover:bg-zinc-200 transition-all">
            Consult With Our Experts
        </a>
    </div>
</section>
@endsection
