@extends('layouts.public')

@section('title', 'Size Guide - Maxumax')

@section('content')
<main class="min-h-screen bg-[#050505] text-white pt-24 pb-16">
    <!-- Header Section -->
    <section class="max-w-4xl mx-auto px-6 text-center mb-16">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black uppercase tracking-tight mb-6">MADE TO FIT, <span class="text-white/60">BUILT FOR PERFORMANCE</span></h1>
        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto">
            Find your perfect size using our detailed measurement guides.
        </p>
    </section>

    @if($sizeGuides->isNotEmpty())
    <!-- Size Guide Tabs Section using Alpine.js -->
    <section class="max-w-6xl mx-auto px-6 mb-24" x-data="{ activeTab: '{{ $sizeGuides->first()->slug }}' }">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar / Tab Navigation -->
            <div class="w-full md:w-1/3 lg:w-1/4 flex-shrink-0">
                <div class="sticky top-24 bg-white/5 border border-white/10 rounded-xl p-4 overflow-y-auto max-h-[70vh] custom-scrollbar">
                    <nav class="flex flex-col gap-1 space-y-1">
                        @foreach($sizeGuides as $guide)
                            <button 
                                @click="activeTab = '{{ $guide->slug }}'"
                                :class="activeTab === '{{ $guide->slug }}' ? 'bg-white text-black font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white'"
                                class="text-left px-4 py-3 text-sm tracking-wide rounded-lg transition-colors duration-200"
                            >
                                {{ $guide->name }}
                            </button>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Tab Content Area -->
            <div class="w-full md:w-2/3 lg:w-3/4">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 md:p-10 min-h-[500px]">
                    @foreach($sizeGuides as $guide)
                        <div x-show="activeTab === '{{ $guide->slug }}'" 
                             x-transition:enter="transition ease-out duration-300" 
                             x-transition:enter-start="opacity-0 translate-y-4" 
                             x-transition:enter-end="opacity-100 translate-y-0" 
                             style="display: none;">
                            
                            <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight mb-8 border-b border-white/10 pb-4">{{ $guide->name }}</h2>

                            @if($guide->image_path)
                            <div class="mt-4">
                                <img src="{{ asset('storage/' . $guide->image_path) }}" alt="{{ $guide->name }} Chart" class="w-full h-auto rounded-xl border border-white/10 shadow-2xl">
                            </div>
                            @else
                            <div class="mt-4 bg-black border border-white/5 rounded-xl p-8 flex flex-col items-center justify-center text-center">
                                <svg class="w-12 h-12 text-white/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"></path>
                                </svg>
                                <p class="text-white/40 text-sm">Visual size chart representation will be available soon.</p>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="max-w-4xl mx-auto px-6 text-center py-20">
        <p class="text-white/40 italic">Size guides are currently being updated. Please check back later.</p>
    </section>
    @endif

    <!-- Closing & CTA Section -->
    <section class="max-w-3xl mx-auto px-6 text-center border-t border-white/10 pt-16">
        <h3 class="text-2xl font-bold mb-4">Still unsure about sizing?</h3>
        <p class="text-white/70 mb-8 max-w-xl mx-auto">
            Our team is here to help you choose with confidence so every piece fits perfectly. Reach out to us for personalized guidance.
        </p>
        <a href="https://wa.me/60143436496" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center bg-white text-black font-black uppercase tracking-wider px-8 py-4 rounded-full hover:bg-slate-200 transition-all duration-300 hover:scale-105 active:scale-95">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Contact Maxumax
        </a>
    </section>
</main>
@endsection
