@extends('layouts.public')

@section('title', $project->title . ' - Maxumax')

@section('content')
<!-- Hero Section -->
<section class="bg-black py-24 px-6 min-h-[50vh] flex items-center justify-center border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-blue-600/10 to-transparent opacity-50"></div>
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <h3 class="text-blue-400 font-black text-xs uppercase tracking-[0.3em] mb-6">
            {{ $project->category }} Project
        </h3>
        <h1 class="text-white font-black text-4xl md:text-7xl italic uppercase tracking-tighter leading-none mb-6">
            {{ $project->title }}
        </h1>
        @if($project->subhead)
            <p class="text-white/40 text-xl md:text-2xl font-bold uppercase tracking-widest italic mx-auto">
                {{ $project->subhead }}
            </p>
        @endif
    </div>
</section>

<!-- Project Content -->
<section class="bg-[#050505] py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            <div class="space-y-12">
                <div>
                    <h2 class="text-white font-black text-3xl md:text-5xl italic uppercase tracking-tighter leading-none mb-8">
                        {{ $project->headline ?: 'Project Overview' }}
                    </h2>
                    <p class="text-white/60 text-lg md:text-xl leading-relaxed">
                        {{ $project->description }}
                    </p>
                </div>

                @if($project->gallery && count($project->gallery) > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($project->gallery as $imgPath)
                            <div class="aspect-square rounded-2xl overflow-hidden border border-white/5 bg-[#111] group">
                                <img src="{{ Storage::url($imgPath) }}" alt="" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="pt-8">
                    <a href="{{ route('pages.projects') }}" class="inline-flex items-center gap-2 text-white/40 hover:text-white font-bold uppercase tracking-widest text-xs transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Back to All Projects
                    </a>
                </div>
            </div>

            <div class="lg:sticky lg:top-32">
                <div class="aspect-[4/5] bg-gradient-to-br from-[#1a1a1a] to-[#0a0a0a] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
                    @if($project->image_path)
                        <img src="{{ Storage::url($project->image_path) }}" alt="{{ $project->title }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-white/10 font-black italic uppercase">No Asset</span>
                        </div>
                    @endif
                </div>
            </div>
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
