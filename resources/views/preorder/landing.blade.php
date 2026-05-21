@extends('layouts.public')

@section('title', 'Pre-order Jersey - Maxumax')

@section('content')
    <!-- Hero Section -->
    <section
        class="relative min-h-[80vh] flex items-center justify-center overflow-hidden bg-slate-900 border-b border-white/10">
        <!-- Animated Background Gradient -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-black opacity-90"></div>
            <div
                class="absolute -top-[30%] -right-[10%] w-[60%] h-[60%] rounded-full bg-indigo-500/10 blur-[120px] animate-pulse">
            </div>
            <div class="absolute -bottom-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] animate-pulse"
                style="animation-delay: 2s;"></div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 text-center" x-data="{ loaded: false }"
            x-init="setTimeout(() => loaded = true, 100)">
            <div x-show="loaded" x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                <span
                    class="inline-block px-4 py-1.5 mb-6 text-xs font-bold tracking-widest text-indigo-400 uppercase bg-indigo-400/10 border border-indigo-400/20 rounded-full">
                    Premium Sports Apparel
                </span>
                <h1 class="text-white font-black text-5xl md:text-7xl lg:text-8xl mb-6 tracking-tight leading-[1.1]">
                    Elevate Your <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-blue-400">Game
                        Performance.</span>
                </h1>
                <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed font-medium">
                    Pre-order your limited edition Maxumax jersey now. Expertly crafted for champions, delivered with
                    passion.
                </p>

            </div>
        </div>
    </section>

    <!-- Banner Slider -->
    <section class="bg-white" x-data="{ 
                activeSlide: 0, 
                slides: [
                    { img: '{{ asset('assets/img/banner1.jpeg') }}', alt: 'Maxumax Banner 1' },
                    { img: '{{ asset('assets/img/banner2.jpeg') }}', alt: 'Maxumax Banner 2' }
                ],
                next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
                init() { setInterval(() => this.next(), 6000) }
            }">
        <div class="w-full">
            <div class="slider-container overflow-hidden">
                <div class="slider-wrapper" :style="`transform: translateX(-${activeSlide * 100}%)`">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div class="slider-slide relative aspect-[21/9] md:aspect-[25/7] bg-slate-100">
                            <img :src="slide.img" :alt="slide.alt" class="w-full h-full object-cover" />
                        </div>
                    </template>
                </div>

                <!-- Slider Dots -->
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-3">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button class="slider-dot transition-all duration-300 ease-out"
                            :class="{ 'bg-black w-10 h-1.5 rounded-full': activeSlide === index, 'bg-slate-300 w-1.5 h-1.5 rounded-full': activeSlide !== index }"
                            @click="activeSlide = index"></button>
                    </template>
                </div>
            </div>
        </div>
    </section>





    <!-- Gallery Section (MOVED DOWN) -->
    @if(isset($highlightedGallery) && $highlightedGallery->count() > 0)
        <section class="bg-white py-24 px-4 overflow-hidden border-b border-slate-100">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                    <div>
                        <span class="text-indigo-600 font-bold uppercase tracking-widest text-sm mb-2 inline-block">Visual
                            Highlights</span>
                        <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Our Collection Gallery</h2>
                    </div>
                    <a href="{{ route('gallery.index') }}"
                        class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-black transition-all group">
                        View Full Gallery
                        <i data-feather="arrow-right" class="group-hover:translate-x-1 transition-transform"
                            style="width:18px;height:18px;"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($highlightedGallery as $index => $gallery)
                        <div
                            class="relative aspect-[4/5] rounded-[2rem] overflow-hidden group shadow-2xl shadow-slate-200/60 {{ $index === 0 ? 'lg:col-span-1 lg:row-span-1' : '' }}">
                            <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}"
                                class="w-full h-full object-contain bg-slate-50 transition-transform duration-1000 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8">
                                <span
                                    class="text-indigo-400 font-bold text-xs uppercase tracking-[0.2em] mb-2 translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-100">Featured
                                    Edit</span>
                                <h3
                                    class="text-white font-black text-2xl translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-200">
                                    {{ $gallery->title }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif



    <!-- FAQ Section -->
    <section class="py-24 bg-white px-4 border-t border-slate-100">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-indigo-600 font-bold uppercase tracking-widest text-sm mb-2 inline-block">Support</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Common Questions</h2>
                <p class="text-slate-500 text-lg mt-4 font-medium">Everything you need to know about the Maxumax preorder
                    experience.</p>
            </div>

            <div x-data="{ active: null }" class="space-y-4">
                @php
                    $faqs = [
                        ['q' => 'When will I receive my pre-order?', 'a' => 'Our preorder collection is expected to be delivered by late January 2026. We will notify you via WhatsApp or Email once your order is ready.'],
                        ['q' => 'How do I pay for my order?', 'a' => 'We offer various payment methods including PAY ON DELIVERY (COD) for certain regions, and secure Online Payments via Stripe.'],
                        ['q' => 'Can I customize the name and number?', 'a' => 'Yes! All Maxumax jerseys include full customization of names and numbers at no extra cost.'],
                        ['q' => 'What is the return policy?', 'a' => 'Since jerseys are customized, we cannot accept returns for change of mind. However, manufacturing defects will be replaced immediately.']
                    ];
                @endphp

                @foreach($faqs as $index => $faq)
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                        :class="active === {{ $index }} ? 'shadow-xl shadow-slate-200/60 ring-1 ring-slate-900' : ''">
                        <button @click="active = (active === {{ $index }} ? null : {{ $index }})"
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 transition-colors">
                            <span class="font-bold text-lg text-slate-900 pr-8">{{ $faq['q'] }}</span>
                            <i data-feather="chevron-down" class="text-slate-400 transition-transform duration-300"
                                :class="active === {{ $index }} ? 'rotate-180 text-slate-900' : ''"></i>
                        </button>
                        <div x-show="active === {{ $index }}" x-collapse x-cloak>
                            <div class="p-6 pt-0 text-slate-600 leading-relaxed font-medium">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Final Custom Teamwear CTA Section -->
    <section class="py-24 bg-[#051024] px-4 relative">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight uppercase">NEED CUSTOM TEAMWEAR?</h2>
            <p class="text-blue-100 text-lg md:text-xl font-medium mb-10 max-w-3xl mx-auto leading-relaxed">Send us your design idea, quantity, and deadline. Our team will assist with quotation, mockup, material selection, and production planning.</p>
            <a href="https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20am%20interested%20to%20make%20custom%20teamwear." 
                target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center px-10 py-4 bg-[#1DB954] text-white font-black text-lg rounded-full hover:bg-[#1ed760] transition-all hover:scale-105 active:scale-95 shadow-xl shadow-[#1DB954]/20 uppercase tracking-wide">
                <i data-feather="message-circle" class="mr-3" style="width:24px;height:24px;"></i>
                WHATSAPP MAXUMAX
            </a>
        </div>
    </section>
@endsection