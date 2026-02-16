@extends('layouts.public')

@section('title', 'Maxumax - Elevated Sports Performance')

@section('content')
    <!-- Hero Slider -->
    <section class="bg-black" x-data="{ 
            activeSlide: 0, 
            slides: [
                { img: '{{ asset('assets/img/banner1.jpeg') }}', alt: 'Maxumax Hero 1' },
                { img: '{{ asset('assets/img/banner2.jpeg') }}', alt: 'Maxumax Hero 2' }
            ],
            next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
            prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
            init() { setInterval(() => this.next(), 6000) }
        }">
        <div class="relative w-full overflow-hidden">
            <!-- Slider Wrapper -->
            <div class="flex transition-transform duration-1000 ease-out" :style="`transform: translateX(-${activeSlide * 100}%)`">
                <template x-for="(slide, index) in slides" :key="index">
                    <div class="w-full flex-shrink-0 relative aspect-[21/9] md:aspect-[25/9]">
                        <img :src="slide.img" :alt="slide.alt" class="w-full h-full object-cover">
                    </div>
                </template>
            </div>

            <!-- Slider Dots -->
            <div class="absolute bottom-6 md:bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-3 z-20">
                <template x-for="(slide, index) in slides" :key="index">
                    <button class="h-2 rounded-full transition-all duration-500 ease-out"
                        :class="activeSlide === index ? 'bg-white w-8' : 'bg-white/30 w-2 hover:bg-white/50'"
                        @click="activeSlide = index"></button>
                </template>
            </div>
        </div>
    </section>

    <!-- NEW ARRIVALS Section -->
    <section class="bg-black py-24 px-6 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-[0.3em]">New Arrivals</h2>
            </div>

            @php
                $preorderProducts = $products;
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                @foreach ($preorderProducts as $product)
                    <div class="flex flex-col bg-[#111111] rounded-2xl overflow-hidden border border-white/5 hover:border-white/10 transition-all duration-300 group">
                        <!-- Product Image -->
                        <div class="aspect-square md:aspect-[4/5] relative flex items-center justify-center p-3 md:p-8 bg-gradient-to-b from-[#1a1a1a] to-[#111111]">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                    class="max-w-[85%] max-h-[85%] md:max-w-full md:max-h-full object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.8)] group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="text-white/10"><i data-feather="image" style="width:24px;height:24px" class="md:w-[64px] md:h-[64px]"></i></div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-3 md:p-6 flex flex-col bg-[#1a1a1a] flex-grow">
                            <h3 class="text-white font-black text-[9px] md:text-sm uppercase tracking-widest text-center mb-2 md:mb-4 leading-tight min-h-[1.5rem] md:min-h-[2.5rem] flex items-center justify-center">{{ $product->name }}</h3>
                            
                            <!-- Badges -->
                            <div class="flex flex-wrap justify-center gap-1 mb-3 md:mb-8">
                                @if($product->jersey_type)
                                    <span class="px-1.5 md:px-4 py-0.5 md:py-1.5 rounded-full border border-white/10 text-[7px] md:text-[9px] font-black text-white uppercase tracking-widest">
                                        {{ $product->jersey_type }}
                                    </span>
                                @endif
                                <span class="px-1.5 md:px-4 py-0.5 md:py-1.5 rounded-full border border-yellow-500/30 text-[7px] md:text-[9px] font-black text-yellow-500 uppercase tracking-widest">
                                    Preorder
                                </span>
                            </div>

                            <!-- Price and Action -->
                            <div class="mt-auto pt-6 border-t border-white/5 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[8px] md:text-[10px] font-black text-white/40 uppercase tracking-widest mb-0.5 md:mb-1">{{ $currency }}</span>
                                    <span class="text-sm md:text-xl font-black text-white leading-none">
                                        {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                    </span>
                                </div>
                                <a href="{{ route('preorder.create', $product) }}" 
                                   class="w-7 h-7 md:w-10 md:h-10 bg-white rounded-full flex items-center justify-center text-black hover:bg-slate-200 transition-all hover:scale-110 active:scale-95 shadow-xl">
                                    <i data-feather="arrow-up-right" class="w-3.5 h-3.5 md:w-[18px] md:h-[18px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($preorderProducts->isEmpty())
                <div class="text-center py-32">
                    <p class="text-white/30 font-black uppercase tracking-[0.2em]">No new arrivals at the moment.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Collaboration Section -->
    <section class="relative h-[600px] md:h-[800px] flex items-center justify-center overflow-hidden">
        <!-- Background Banner -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/img/banner1.jpeg') }}" alt="Collaboration Background" class="w-full h-full object-cover">
            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center text-center px-6">
            <!-- FA Logo -->
            <div class="mb-12 transform hover:scale-105 transition-transform duration-700">
                <img src="{{ asset('assets/img/FA-logo.png') }}" alt="Brunei FA Logo" class="w-48 md:w-64 lg:w-80 drop-shadow-[0_0_50px_rgba(255,255,255,0.2)]">
            </div>

            <!-- Branding -->
            <div class="flex items-center gap-6 md:gap-10 mb-10">
                <!-- Maxumax Logo -->
                <img src="{{ asset('assets/img/logo.png') }}" alt="Maxumax" class="h-6 md:h-10 invert brightness-0">
                
                <!-- Separator -->
                <div class="h-10 md:h-16 w-1 bg-white"></div>

                <!-- Maxumax Store Text -->
                <div class="flex flex-col items-start leading-none">
                    <span class="text-white font-black text-xl md:text-3xl tracking-tighter uppercase italic">Maxumax</span>
                    <span class="text-white font-black text-xl md:text-3xl tracking-tighter uppercase italic">Store.</span>
                </div>
            </div>

            <!-- Official Badge -->
            <div class="bg-black/80 border border-white/20 px-8 py-3 rounded-full backdrop-blur-md">
                <p class="text-white font-bold text-[10px] md:text-xs uppercase tracking-[0.2em]">
                    Official Brunei Darussalam Futsal Jersey | Limited Edition
                </p>
            </div>
        </div>
    </section>

    <!-- Stats/About/FAQ - Refined for Dark UI -->
    <section class="bg-black py-24 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12">
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">5,000+</div>
                    <div class="text-white/40 font-black text-[10px] uppercase tracking-[0.2em]">Athletes Served</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">100%</div>
                    <div class="text-white/40 font-black text-[10px] uppercase tracking-[0.2em]">Tech Approved</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">24/7</div>
                    <div class="text-white/40 font-black text-[10px] uppercase tracking-[0.2em]">Support Ready</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">4.9/5</div>
                    <div class="text-white/40 font-black text-[10px] uppercase tracking-[0.2em]">User Rating</div>
                </div>
            </div>
        </div>
    </section>
@endsection
