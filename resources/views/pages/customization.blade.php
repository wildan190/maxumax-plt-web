@extends('layouts.public')

@section('title', 'Customization - Maxumax')

@section('content')
<main class="min-h-screen bg-[#050505] text-white pt-24 pb-16">
    <!-- Header Section -->
    <section class="max-w-4xl mx-auto px-6 text-center mb-16">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black uppercase tracking-tight mb-6">UNLIMITED <span class="text-white/60">CUSTOMIZATION</span></h1>
        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto">
            Bring your team's vision to life with our comprehensive apparel and premium finishing options.
        </p>
    </section>

    @include('partials.home.customizable-items')

    <!-- Gallery Section -->
    @if($galleries->count() > 0)
        <section class="py-24 px-6 border-t border-white/10">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <span class="text-blue-500 font-black uppercase tracking-[0.4em] text-[10px] mb-6 inline-block">Visual Archive</span>
                    <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight uppercase italic">Our <span class="text-blue-500">Craftsmanship.</span></h2>
                    <p class="text-white/60 text-lg mt-4 font-medium max-w-2xl mx-auto">Explore our portfolio of precision craftsmanship and high-performance teamwear.</p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
                    @foreach($galleries as $gallery)
                        <div class="group relative bg-[#111111] border border-white/5 rounded-3xl overflow-hidden transition-all duration-500 hover:border-blue-500/30">
                            <div class="relative aspect-square overflow-hidden bg-black/40 flex items-center justify-center p-4">
                                <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}"
                                    class="w-full h-full object-contain transition-transform duration-1000 group-hover:scale-110">
                                <div class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                            </div>
                            <div class="p-4 border-t border-white/5 text-center">
                                <h3 class="font-black text-white text-[10px] md:text-xs uppercase italic tracking-tighter">
                                    {{ $gallery->title }}
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-16">
                    <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-2 text-white/40 hover:text-white font-bold uppercase tracking-widest text-xs transition-colors">
                        View Full Gallery
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- FAQ Section -->
    <section class="py-24 px-6 border-t border-white/10">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-white/40 font-bold uppercase tracking-widest text-sm mb-2 inline-block">Support</span>
                <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight">Common Questions</h2>
                <p class="text-white/60 text-lg mt-4 font-medium">Get quick answers to the questions most commonly asked by our clients.</p>
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
                    <div class="border border-white/10 rounded-2xl overflow-hidden transition-all duration-300"
                        :class="active === {{ $index }} ? 'bg-white/5 ring-1 ring-white/20' : ''">
                        <button @click="active = (active === {{ $index }} ? null : {{ $index }})"
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-white/5 transition-colors">
                            <span class="font-bold text-lg text-white pr-8">{{ $faq['q'] }}</span>
                            <i data-feather="chevron-down" class="text-white/40 transition-transform duration-300"
                                :class="active === {{ $index }} ? 'rotate-180 text-white' : ''"></i>
                        </button>
                        <div x-show="active === {{ $index }}" x-collapse x-cloak>
                            <div class="p-6 pt-0 text-white/60 leading-relaxed font-medium">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center p-8 bg-white/5 rounded-[2rem] border border-white/10">
                <p class="text-white/80 font-bold uppercase tracking-widest text-sm mb-6">STILL HAVE QUESTIONS? VISIT OUR COMPLETE FAQ PAGE OR CONTACT OUR TEAM FOR ASSISTANCE.</p>
                <a href="{{ route('pages.faq') }}" class="inline-flex items-center justify-center bg-white text-black font-black uppercase tracking-wider px-8 py-4 rounded-full hover:bg-slate-200 transition-all duration-300 hover:scale-105 active:scale-95 text-sm">
                    VIEW FULL FAQs
                </a>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="max-w-3xl mx-auto px-6 text-center border-t border-white/10 pt-16 mt-16">
        <h3 class="text-2xl font-bold mb-4">Ready to Build Your Gear?</h3>
        <p class="text-white/70 mb-8 max-w-xl mx-auto">
            Our team will assist with quotation, mockup, material selection, and production planning.
        </p>
        <a href="https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20am%20interested%20to%20make%20custom%20teamwear." target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center bg-white text-black font-black uppercase tracking-wider px-8 py-4 rounded-full hover:bg-slate-200 transition-all duration-300 hover:scale-105 active:scale-95">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Contact Maxumax
        </a>
    </section>
</main>
@endsection
