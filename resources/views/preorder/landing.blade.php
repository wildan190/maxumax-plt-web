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

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                    <a href="#products"
                        class="w-full sm:w-auto px-8 py-4 bg-white text-black font-extrabold rounded-xl hover:bg-slate-100 transition-all hover:scale-105 active:scale-95 shadow-xl shadow-white/5">
                        Start Pre-order Now
                    </a>
                    <a href="#about"
                        class="w-full sm:w-auto px-8 py-4 bg-white/5 backdrop-blur border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition-all">
                        Learn More
                    </a>
                </div>

                <div class="flex flex-wrap justify-center gap-x-8 gap-y-4 pt-8 border-t border-white/10">
                    <div class="flex items-center gap-2 text-slate-400 font-semibold text-sm">
                        <i data-feather="truck" class="text-indigo-400" style="width:18px;height:18px;"></i>
                        Pay on Delivery (Brunei)
                    </div>
                    <div class="flex items-center gap-2 text-slate-400 font-semibold text-sm">
                        <i data-feather="shield" class="text-indigo-400" style="width:18px;height:18px;"></i>
                        Premium Fabric Quality
                    </div>
                    <div class="flex items-center gap-2 text-slate-400 font-semibold text-sm">
                        <i data-feather="edit-3" class="text-indigo-400" style="width:18px;height:18px;"></i>
                        Full Customization
                    </div>
                </div>
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

    <!-- Why Choose Us Section -->
    <section class="py-24 bg-white px-4 border-b border-slate-100">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-indigo-600 font-bold uppercase tracking-wider text-sm">The Maxumax Advantage</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mt-2 mb-4 tracking-tight">Built for Champions.
                </h2>
                <div class="w-20 h-1.5 bg-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <div
                    class="group p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-600/20 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-indigo-600 rounded-xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
                        <i data-feather="wind"></i>
                    </div>
                    <h3 class="font-black text-xl text-slate-900 mb-3">Ultra Breathable</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">Advanced moisture-wicking technology keeps you dry
                        and cool during intense performances.</p>
                </div>

                <div
                    class="group p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-600/20 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
                        <i data-feather="activity"></i>
                    </div>
                    <h3 class="font-black text-xl text-slate-900 mb-3">Pro Fit Cut</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">Anatomically designed fit to enhance your range of
                        motion and reduce wind resistance.</p>
                </div>

                <div
                    class="group p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-600/20 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-indigo-500 rounded-xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
                        <i data-feather="feather"></i>
                    </div>
                    <h3 class="font-black text-xl text-slate-900 mb-3">Featherweight</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">Lightweight high-performance fabrics that won't
                        weigh you down when the stakes are high.</p>
                </div>

                <div
                    class="group p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-600/20 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-black rounded-xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
                        <i data-feather="maximize"></i>
                    </div>
                    <h3 class="font-black text-xl text-slate-900 mb-3">Durability Plus</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">Reinforced stitching ensures your jersey stands
                        the test of time, season after season.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Showcase Section (MOVED UP) -->
    <section id="products" class="bg-slate-900 py-24 px-4 relative overflow-hidden" x-data="{ 
                activeCategory: '',
                applyFilter(cat) {
                    this.activeCategory = cat;
                    const grid = document.getElementById('grid-preorder');
                    if (!grid) return;
                    const val = cat.toLowerCase();
                    grid.querySelectorAll('[data-category]').forEach(card => {
                        const cardCat = (card.getAttribute('data-category') || '').toLowerCase();
                        if (!val || cardCat === val) {
                           card.style.display = '';
                           card.style.opacity = '1';
                        } else {
                           card.style.display = 'none';
                           card.style.opacity = '0';
                        }
                    });
                }
            }">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/5 blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-500/5 blur-[120px]"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16">
                <span class="text-indigo-400 font-bold uppercase tracking-widest text-sm mb-2 inline-block">Limited
                    Drops</span>
                <h2 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-4">Choose Your Spirit.</h2>
                <p class="text-slate-400 text-lg max-w-2xl mx-auto font-medium">Explore our premium selection for the
                    upcoming drop. Strictly limited quantities.</p>
            </div>

            @php
                $preorderProducts = isset($products) ? $products->filter(fn($p) => $p->available_for_preorder) : collect();
                $categories = isset($products) ? $products->pluck('jersey_type')->filter()->unique()->sort()->values() : collect();
            @endphp

            <div class="mb-12">
                <div class="flex flex-wrap justify-center gap-3">
                    <button @click="applyFilter('')"
                        class="px-6 py-2.5 rounded-full font-bold text-sm transition-all duration-300"
                        :class="activeCategory === '' ? 'bg-white text-black shadow-lg shadow-white/10' : 'bg-white/5 text-slate-400 hover:bg-white/10 border border-white/10'">
                        All Editions
                    </button>
                    @foreach($categories as $cat)
                        <button @click="applyFilter('{{ $cat }}')"
                            class="px-6 py-2.5 rounded-full font-bold text-sm transition-all duration-300"
                            :class="activeCategory === '{{ $cat }}' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-white/5 text-slate-400 hover:bg-white/10 border border-white/10'">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div id="grid-preorder" class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @if($preorderProducts->count())
                    @foreach($preorderProducts as $product)
                        <a href="{{ route('preorder.create', $product) }}"
                            class="group relative bg-white/5 backdrop-blur-sm rounded-[2rem] border border-white/10 overflow-hidden hover:border-white/30 transition-all duration-500 hover:-translate-y-2 block"
                            data-category="{{ strtolower($product->jersey_type ?? '') }}">
                            <div
                                class="relative aspect-[4/4] bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center p-8">
                                <span
                                    class="absolute top-6 left-6 inline-flex items-center rounded-full bg-indigo-600 text-white text-[10px] uppercase tracking-widest font-black px-3 py-1 shadow-lg shadow-indigo-600/20">Pre-order
                                    Only</span>
                                @if($product->image_path)
                                    <img class="w-full h-full object-contain drop-shadow-[0_20px_30px_rgba(0,0,0,0.5)] transition-transform duration-700 group-hover:scale-110 group-hover:rotate-3"
                                        src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" />
                                @else
                                    <div class="text-slate-700 scale-[2]"><i data-feather="image"></i></div>
                                @endif
                            </div>
                            <div class="p-8">
                                <h3 class="text-xl font-black text-white mb-2 tracking-tight">{{ $product->name }}</h3>
                                <div class="flex items-center gap-3 mb-6">
                                    @if($product->jersey_type)
                                        <span
                                            class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 px-3 py-1 rounded-full border border-indigo-400/20">{{ $product->jersey_type }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between pt-6 border-t border-white/5">
                                    <div class="text-2xl font-black text-white flex items-baseline gap-1">
                                        <span class="text-xs font-bold text-slate-500">{{ $currency }}</span>
                                        {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                    </div>
                                    <div
                                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-black group-hover:bg-indigo-400 group-hover:scale-110 transition-all shadow-xl">
                                        <i data-feather="arrow-up-right" style="width:20px;height:20px;"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-24 bg-white/5 rounded-[3rem] border border-dashed border-white/10">
                        <div class="text-6xl mb-6 grayscale opacity-30">👕</div>
                        <h4 class="text-xl font-bold text-white mb-2">No Active Drops</h4>
                        <p class="text-slate-500">Stay tuned for our next seasonal collection launch.</p>
                    </div>
                @endif
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

    <!-- About Us Section -->
    <section id="about" class="py-24 bg-slate-50 border-b border-slate-100 overflow-hidden px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
                <div class="w-full lg:w-1/2 relative">
                    <div class="relative z-10 aspect-square rounded-[2rem] overflow-hidden shadow-2xl">
                        <img src="{{ asset('assets/img/about_us_jersey_production.png') }}" alt="Jersey Craftsmanship"
                            class="w-full h-full object-cover grayscale-[0.2] hover:grayscale-0 transition-all duration-700">
                    </div>
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-600/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-60 h-60 bg-blue-600/10 rounded-full blur-3xl"></div>
                </div>

                <div class="w-full lg:w-1/2">
                    <span class="text-indigo-600 font-bold uppercase tracking-widest text-sm mb-4 inline-block">Our
                        Story</span>
                    <h2
                        class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 mb-8 tracking-tight leading-tight">
                        Mastering the Art of <span class="text-indigo-600">Jersey Tech.</span></h2>
                    <p class="text-slate-600 text-lg md:text-xl font-medium leading-relaxed mb-6">
                        Maxumax was born from a simple obsession: to create the perfect sports jersey that balances
                        high-performance technology with stunning streetwear aesthetics.
                    </p>
                    <p class="text-slate-500 text-base leading-relaxed mb-10">
                        Based in Malaysia, we serve athletes across the region who demand more from their gear. Every
                        stitch, every print, and every fabric selection is meticulously tested to ensure you feel as good as
                        you look on and off the field.
                    </p>
                    <div class="grid grid-cols-2 gap-8 mb-10">
                        <div>
                            <div class="text-4xl font-black text-slate-900 mb-1">5K+</div>
                            <div class="text-slate-500 font-bold text-sm tracking-uppercase">Happy Athletes</div>
                        </div>
                        <div>
                            <div class="text-4xl font-black text-slate-900 mb-1">100%</div>
                            <div class="text-slate-500 font-bold text-sm tracking-uppercase">Premium Quality</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <!-- Final CTA Section -->
    <section class="py-32 bg-slate-900 px-4 relative">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-7xl font-black text-white mb-8 tracking-tighter italic uppercase">Don't Get Left
                <span class="text-indigo-500">Behind.</span></h2>
            <p class="text-slate-400 text-xl font-medium mb-12 max-w-2xl mx-auto">Join thousands of athletes who trust
                Maxumax for their performance needs. The next drop is waiting for you.</p>
            <a href="#products"
                class="inline-block px-12 py-6 bg-white text-black font-black text-xl rounded-2xl hover:bg-slate-100 transition-all hover:scale-110 active:scale-95 shadow-2xl shadow-white/5 uppercase tracking-widest">
                Browse Collection
            </a>
        </div>
    </section>
@endsection