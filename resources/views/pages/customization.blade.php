@extends('layouts.public')

@section('title', 'Customization - Maxumax')

@section('content')
<main class="min-h-screen bg-white text-[#111111] pt-20 pb-16">
    <!-- Header Section -->
    <section style="max-width: 1280px; margin: 0 auto;" class="px-6 text-center mb-16">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black uppercase tracking-tight mb-6">Build Your <span class="text-[#155EEF]">Teamwear</span></h1>
        <p class="text-lg md:text-xl text-[#666666] max-w-2xl mx-auto">
            Bring your team's vision to life with our comprehensive apparel and premium finishing options.
        </p>
    </section>

    @include('partials.home.customizable-items')

    <!-- Gallery Section -->
    @if($galleries->count() > 0)
        <section class="py-24 px-6 border-t border-[#E8E8E3] bg-[#F7F7F5]">
            <div style="max-width: 1280px; margin: 0 auto;">
                <div class="text-center mb-16">
                    <span class="text-[#155EEF] font-black uppercase tracking-[0.4em] text-[10px] mb-6 inline-block">Gallery</span>
                    <h2 class="text-4xl md:text-5xl font-black text-[#111111] tracking-tight uppercase italic">Our <span class="text-[#155EEF]">Craftsmanship.</span></h2>
                    <p class="text-[#666666] text-lg mt-4 font-medium max-w-2xl mx-auto">Explore our portfolio of precision craftsmanship and high-performance teamwear.</p>
                </div>

                <div class="columns-2 lg:columns-3 xl:columns-4 gap-4 md:gap-8 space-y-4 md:space-y-8">
                    @foreach($galleries as $gallery)
                        <div class="break-inside-avoid group relative bg-white border border-[#E8E8E3] rounded-lg overflow-hidden transition-all duration-500 hover:border-[#155EEF] hover:shadow-lg">
                            <div class="relative overflow-hidden bg-[#F7F7F5] flex items-center justify-center p-4">
                                <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}"
                                    class="w-full h-auto object-contain transition-transform duration-1000 group-hover:scale-110">
                                <div class="absolute inset-0 bg-[#155EEF]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                            </div>
                            <div class="p-4 border-t border-[#E8E8E3] text-center">
                                <h3 class="font-black text-[#111111] text-[10px] md:text-xs uppercase tracking-widest">
                                    {{ $gallery->title }}
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-16">
                    <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-2 text-[#155EEF] hover:text-blue-700 font-bold uppercase tracking-widest text-xs transition-colors">
                        View Full Gallery
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- FAQ Section -->
    <section class="py-24 px-6 border-t border-[#E8E8E3] bg-white">
        <div style="max-width: 1280px; margin: 0 auto;">
            <div class="text-center mb-16">
                <span class="text-[#155EEF] font-black uppercase tracking-widest text-xs mb-2 inline-block">Support</span>
                <h2 class="text-4xl md:text-5xl font-black text-[#111111] tracking-tight">Common Questions</h2>
                <p class="text-[#666666] text-lg mt-4 font-medium">Get quick answers to the questions most commonly asked by our clients.</p>
            </div>

            <div x-data="{ active: null }" class="space-y-4 mb-16">
                @php
                    $faqs = [
                        ['q' => 'What products can be customized?', 'a' => 'We offer a wide range of customizable apparel including jerseys, polo, cotton tee, tracksuits, shorts, caps, socks and merchandises.'],
                        ['q' => 'What is the minimum order quantity?', 'a' => 'Our minimum order quantity for full sublimation teamwear is 8 pieces. But, it may differ depending on the product type and customization requirements.'],
                        ['q' => 'How long is the production period?', 'a' => 'For full sublimation teamwear, production period 8-14 working days after final design confirmation and payment confirmation. For fully-customized teamwear, please refer to our staff as production period may differ.'],
                        ['q' => 'Do you provide design services?', 'a' => 'Yes, we have our own design team that can assist you based on your ideas, team colours, logos and specific requirements.'],
                        ['q' => 'Do you offer bulk discounts?', 'a' => 'Yes. Our discounts are based on the quantity ordered – the more you order, the higher the discounts given.'],
                        ['q' => 'Can you handle urgent orders?', 'a' => 'Yes, we can accommodate urgent orders depending on our current production schedule and the quantity required. Contact our team with your deadline and order details, and we can advise on the fastest available timeline.']
                    ];
                @endphp

                @foreach($faqs as $index => $faq)
                    <div class="border border-[#E8E8E3] rounded-lg overflow-hidden transition-all duration-300 bg-[#F7F7F5]"
                        :class="active === {{ $index }} ? 'border-[#155EEF] ring-1 ring-[#155EEF]/20' : ''">
                        <button @click="active = (active === {{ $index }} ? null : {{ $index }})"
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-white transition-colors">
                            <span class="font-bold text-lg text-[#111111] pr-8">{{ $faq['q'] }}</span>
                            <i data-feather="chevron-down" class="text-[#E8E8E3] transition-transform duration-300"
                                :class="active === {{ $index }} ? 'rotate-180 text-[#155EEF]' : ''"></i>
                        </button>
                        <div x-show="active === {{ $index }}" x-collapse x-cloak>
                            <div class="p-6 pt-0 text-[#666666] leading-relaxed font-medium bg-white">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center p-8 bg-[#155EEF] rounded-lg border border-[#155EEF]">
                <p class="text-white font-bold uppercase tracking-widest text-sm mb-6">Still have questions? Contact our team for assistance.</p>
                <a href="https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20have%20questions%20about%20customization." class="inline-flex items-center justify-center bg-white text-[#155EEF] font-black uppercase tracking-wider px-8 py-4 rounded-lg hover:bg-[#F7F7F5] transition-all duration-300 hover:scale-105 active:scale-95 text-sm">
                    Contact Us via WhatsApp
                </a>
            </div>
        </div>
    </section>

</main>
@endsection
