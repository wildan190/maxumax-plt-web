@extends('layouts.public')

@section('title', $project->title . ' - Maxumax')

@section('content')
<!-- Hero Section -->
<section class="bg-[#F7F7F5] py-16 px-6 min-h-[30vh] flex items-center justify-center border-b border-[#E8E8E3]">
    <div class="max-w-5xl mx-auto text-center">
        <h3 class="text-[#155EEF] font-black text-xs uppercase tracking-[0.3em] mb-4">
            {{ $project->category }} Project
        </h3>
        <h1 class="text-[#111111] font-black text-2xl md:text-4xl italic uppercase tracking-tighter leading-none mb-4">
            {{ $project->title }}
        </h1>
        @if($project->subhead)
            <p class="text-[#666666] text-sm md:text-lg font-bold uppercase tracking-widest italic mx-auto">
                {{ $project->subhead }}
            </p>
        @endif
    </div>
</section>

<!-- Project Content -->
<section class="bg-white py-12 px-4 md:px-6">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
            <div class="lg:order-2 lg:sticky lg:top-24">
                <div class="aspect-[4/5] bg-gradient-to-br from-[#F7F7F5] to-white rounded-2xl border border-[#E8E8E3] overflow-hidden">
                    @if($project->image_path)
                        <img src="{{ Storage::url($project->image_path) }}" alt="{{ $project->title }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-[#E8E8E3] font-black italic uppercase text-xs">No Asset</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h2 class="text-[#111111] font-black text-xl md:text-3xl italic uppercase tracking-tighter leading-none mb-4">
                        {{ $project->headline ?: 'Project Overview' }}
                    </h2>
                    <p class="text-[#666666] text-sm md:text-base leading-relaxed">
                        {{ $project->description }}
                    </p>
                </div>

                @if($project->gallery && count($project->gallery) > 0)
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($project->gallery as $imgPath)
                            <div class="aspect-square rounded-xl overflow-hidden border border-[#E8E8E3] bg-[#F7F7F5]">
                                <img src="{{ Storage::url($imgPath) }}" alt="" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="pt-4">
                    <a href="{{ route('pages.projects') }}" class="inline-flex items-center gap-2 text-[#666666] hover:text-[#155EEF] font-bold uppercase tracking-widest text-xs transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Back to All Projects
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer CTA -->
<section class="bg-[#111111] py-16 px-4 md:px-6 border-t border-[#E8E8E3]">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-white font-black text-xl md:text-3xl italic uppercase tracking-tighter mb-6">Start your project today.</h2>
        <a href="{{ route('pages.contact-us') }}" class="inline-flex bg-[#155EEF] text-white px-8 py-4 rounded-xl font-black uppercase tracking-[0.2em] hover:bg-[#0d46b3] transition-all text-xs md:text-sm">
            Consult With Our Experts
        </a>
    </div>
</section>
@endsection
