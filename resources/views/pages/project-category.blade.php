@extends('layouts.public')

@section('title', $category . ' Projects - Maxumax')

@section('content')
<!-- Hero Section -->
<section class="bg-[#F7F7F5] py-12 px-4 md:px-6 min-h-[30vh] flex items-center justify-center border-b border-[#E8E8E3]">
    <div class="max-w-5xl mx-auto text-center">
        <h1 class="text-[#111111] font-black text-2xl md:text-4xl italic uppercase tracking-tighter leading-none mb-4">
            {{ $category }} <span class="text-[#E8E8E3]">PROJECTS.</span>
        </h1>
        <p class="text-[#666666] text-sm md:text-base max-w-2xl mx-auto font-medium">
            Explore our specialized {{ strtolower($category) }} portfolio, showcasing excellence in athletic performance and design.
        </p>
    </div>
</section>

<!-- Projects Grid -->
<section class="bg-white py-12 px-4 md:px-6">
    <div class="max-w-5xl mx-auto">
        <div class="space-y-12">
            @forelse($projects as $project)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    <div class="{{ $loop->even ? 'lg:order-2' : '' }}">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-[#155EEF] font-black text-xs uppercase tracking-[0.3em] mb-2">Case Study</h3>
                                <h2 class="text-[#111111] font-black text-xl md:text-3xl italic uppercase tracking-tighter leading-none mb-3">
                                    {{ $project->headline ?: $project->title }}
                                </h2>
                                @if($project->subhead)
                                    <p class="text-[#666666] text-sm font-bold uppercase tracking-widest italic mb-3">{{ $project->subhead }}</p>
                                @endif
                                <p class="text-[#666666] text-sm leading-relaxed">
                                    {{ $project->description }}
                                </p>
                            </div>
                            
                            @if($project->gallery && count($project->gallery) > 0)
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach(array_slice($project->gallery, 0, 4) as $imgPath)
                                        <div class="aspect-square rounded-xl overflow-hidden border border-[#E8E8E3] bg-[#F7F7F5]">
                                            <img src="{{ Storage::url($imgPath) }}" alt="" class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="{{ $loop->even ? 'lg:order-1' : '' }}">
                        <div class="aspect-[4/5] bg-gradient-to-br from-[#F7F7F5] to-white rounded-2xl border border-[#E8E8E3] overflow-hidden">
                            @if($project->image_path)
                                <img src="{{ Storage::url($project->image_path) }}" alt="{{ $project->title }}" 
                                     class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-[#E8E8E3] font-black text-lg uppercase tracking-widest italic">More projects coming soon.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Footer CTA -->
<section class="bg-[#111111] py-12 px-4 md:px-6 border-t border-[#E8E8E3]">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-white font-black text-xl md:text-3xl italic uppercase tracking-tighter mb-6">Start your project today.</h2>
        <a href="{{ route('pages.contact-us') }}" class="inline-flex bg-[#155EEF] text-white px-8 py-4 rounded-xl font-black uppercase tracking-[0.2em] hover:bg-[#0d46b3] transition-all text-xs md:text-sm">
            Consult With Our Experts
        </a>
    </div>
</section>
@endsection
