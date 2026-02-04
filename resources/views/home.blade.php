@extends('layouts.public')

@section('title', 'Maxumax - Elevated Sports Performance')

@section('content')
    <!-- Hero Section MAXUMAX -->
    <section class="relative min-h-screen flex items-center justify-center bg-black overflow-hidden">

        <!-- Subtle gradient glow -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-black via-slate-900 to-black"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center justify-center text-center px-6">

            <!-- Logo -->
            <img src="{{ asset('assets/img/logo.png') }}" alt="MAXUMAX"
                class="w-[300px] sm:w-[420px] md:w-[540px]
                   mb-10 select-none
                   filter brightness-0 invert" />

            <!-- CTA Button -->
            <a href="{{ route('preorder.landing') }}"
                class="inline-flex items-center justify-center px-10 py-4
                   bg-white text-black font-black text-sm tracking-widest
                   rounded-full uppercase
                   transition-all duration-300
                   hover:bg-slate-200 hover:scale-105
                   active:scale-95">
                Shop Now
            </a>

        </div>
    </section>

    <!-- Stats Bar -->
    <section class="bg-white py-12 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-black text-slate-900">5,000+</div>
                    <div class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">Athletes Served</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-black text-slate-900">100%</div>
                    <div class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">Tech Approved</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-black text-slate-900">24/7</div>
                    <div class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">Support Ready</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-black text-slate-900">4.9/5</div>
                    <div class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">User Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-slate-50 overflow-hidden px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
                <div class="w-full lg:w-1/2 relative space-y-6">
                    <div class="relative z-10 aspect-square rounded-[3rem] overflow-hidden shadow-2xl">
                        <img src="{{ asset('assets/img/about_us_jersey_production.png') }}" alt="Craftsmanship"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -top-12 -left-12 w-48 h-48 bg-blue-600/10 rounded-full blur-[80px]"></div>
                </div>

                <div class="w-full lg:w-1/2">
                    <span class="text-blue-600 font-black uppercase tracking-[0.3em] text-sm mb-6 inline-block">The Maxumax
                        Legacy</span>
                    <h2 class="text-4xl md:text-6xl font-black text-slate-900 mb-8 tracking-tight leading-tight">Where Tech
                        Meets <span class="text-blue-600 italic">Pure Athletics.</span></h2>
                    <p class="text-slate-600 text-lg md:text-xl font-medium leading-relaxed mb-8">
                        Founded on the principle that gear should never limit ambition. Maxumax combines high-tension
                        durability with featherweight comfort to create the ultimate sports layer.
                    </p>
                    <div class="space-y-6 mb-12">
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-blue-600">
                                <i data-feather="cpu"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-lg">Smart Fabric Integration</h4>
                                <p class="text-slate-500 font-medium">Adaptive cooling technology that reacts to your body
                                    heat.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-blue-600">
                                <i data-feather="layers"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-lg">Multi-Stage Testing</h4>
                                <p class="text-slate-500 font-medium">Every drop undergoes 50+ hours of rigorous athletic
                                    testing.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="py-24 bg-white px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight mb-4 uppercase italic">Technical
                    Edge.</h2>
                <div class="w-24 h-2 bg-blue-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div
                    class="p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 hover:shadow-2xl hover:shadow-blue-600/5 transition-all group">
                    <div
                        class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-8 group-hover:scale-110 transition-transform">
                        <i data-feather="wind" style="width:32px;height:32px;"></i>
                    </div>
                    <h3 class="font-black text-2xl text-slate-900 mb-4">Aero-Cool Tech</h3>
                    <p class="text-slate-500 text-lg font-medium leading-relaxed">Engineered mesh panels placed
                        strategically for maximum airflow in critical heat zones.</p>
                </div>

                <div
                    class="p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 hover:shadow-2xl hover:shadow-blue-600/5 transition-all group">
                    <div
                        class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center text-white mb-8 group-hover:scale-110 transition-transform">
                        <i data-feather="crosshair" style="width:32px;height:32px;"></i>
                    </div>
                    <h3 class="font-black text-2xl text-slate-900 mb-4">Precision Fit</h3>
                    <p class="text-slate-500 text-lg font-medium leading-relaxed">Zero-distraction seams and a tailored
                        athletic cut that moves as you move.</p>
                </div>

                <div
                    class="p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 hover:shadow-2xl hover:shadow-blue-600/5 transition-all group">
                    <div
                        class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center text-white mb-8 group-hover:scale-110 transition-transform">
                        <i data-feather="zap" style="width:32px;height:32px;"></i>
                    </div>
                    <h3 class="font-black text-2xl text-slate-900 mb-4">Ultra-Light</h3>
                    <p class="text-slate-500 text-lg font-medium leading-relaxed">Constructed using our lightest performance
                        fabrics, weighing 30% less than standard gear.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Grid -->
    @if (isset($products) && $products->count() > 0)
        <section class="bg-slate-900 py-32 px-6 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="max-w-7xl mx-auto relative z-10">
                <div class="flex flex-col md:flex-row items-end justify-between mb-20 gap-8">
                    <div>
                        <span class="text-blue-400 font-black uppercase tracking-[0.3em] text-sm mb-4 inline-block">Current
                            Drops</span>
                        <h2 class="text-4xl md:text-7xl font-black text-white tracking-tighter uppercase italic">Season
                            Selection.</h2>
                    </div>
                    <a href="{{ route('preorder.landing') }}"
                        class="px-8 py-4 bg-white text-black font-black rounded-xl hover:bg-blue-400 hover:scale-105 transition-all uppercase tracking-widest text-sm">
                        Browse All
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($products as $product)
                        <div
                            class="group bg-white/5 backdrop-blur-md rounded-[2.5rem] border border-white/10 overflow-hidden hover:border-blue-400/50 transition-all duration-500">
                            <div class="aspect-square bg-slate-800 flex items-center justify-center p-10 relative">
                                <span
                                    class="absolute top-6 left-6 bg-blue-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Available</span>
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-contain drop-shadow-2xl group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <i data-feather="image" class="text-slate-700" style="width:48px;height:48px;"></i>
                                @endif
                            </div>
                            <div class="p-8">
                                <h3 class="text-white font-black text-xl mb-6 tracking-tight">{{ $product->name }}</h3>
                                <div class="flex items-center justify-between">
                                    <div class="text-2xl font-black text-white flex items-baseline gap-1">
                                        <span class="text-xs font-bold text-slate-500">{{ $currency }}</span>
                                        {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                    </div>
                                    <a href="{{ route('preorder.create', $product) }}"
                                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-900 group-hover:bg-blue-400 transition-all">
                                        <i data-feather="arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Gallery Section -->
    @if (isset($highlightedGallery) && $highlightedGallery->count() > 0)
        <section class="py-32 bg-white px-6">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <span
                        class="text-blue-600 font-black uppercase tracking-[0.3em] text-sm mb-4 inline-block">Visuals</span>
                    <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase italic">The
                        Lookbook.
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($highlightedGallery as $gallery)
                        <div class="relative aspect-square rounded-[2rem] overflow-hidden group shadow-2xl">
                            <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}"
                                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8">
                                <h3 class="text-white font-black text-2xl mb-2">{{ $gallery->title }}</h3>
                                <p class="text-white/70 font-medium text-sm">{{ $gallery->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-16">
                    <a href="{{ route('gallery.index') }}"
                        class="inline-flex items-center gap-3 text-slate-900 font-black uppercase tracking-widest hover:text-blue-600 transition-colors">
                        View Collective Archive <i data-feather="arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- FAQ -->
    <section class="py-24 bg-slate-50 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Need Support?</h2>
                <p class="text-slate-500 font-medium mt-4">The essentials answered.</p>
            </div>

            <div x-data="{ active: null }" class="space-y-4">
                @php
                    $faqs = [
                        [
                            'q' => 'Shipping Timeline',
                            'a' =>
                                'Our preorder window closes soon. Production takes 14 days, with delivery expected late January 2026.',
                        ],
                        [
                            'q' => 'Customization Limits',
                            'a' =>
                                'No limits. Names and numbers are fully customizable during the checkout phase at no extra cost.',
                        ],
                        [
                            'q' => 'Payment Options',
                            'a' =>
                                'We support Pay on Delivery (COD) for certain regions, Bank Transfers, and secure Online Payments.',
                        ],
                    ];
                @endphp

                @foreach ($faqs as $index => $faq)
                    <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden transition-all"
                        :class="active === {{ $index }} ? 'shadow-2xl ring-2 ring-blue-600' : ''">
                        <button @click="active = (active === {{ $index }} ? null : {{ $index }})"
                            class="w-full flex items-center justify-between p-8 text-left">
                            <span class="font-black text-xl text-slate-900">{{ $faq['q'] }}</span>
                            <i data-feather="plus" class="text-slate-400 transition-transform"
                                :class="active === {{ $index }} ? 'rotate-45 text-blue-600' : ''"></i>
                        </button>
                        <div x-show="active === {{ $index }}" x-collapse x-cloak>
                            <div class="p-8 pt-0 text-slate-500 text-lg font-medium leading-relaxed">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-32 bg-blue-600 px-6 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10"
            style="background-image: linear-gradient(0deg, transparent 24%, rgba(255, 255, 255, .05) 25%, rgba(255, 255, 255, .05) 26%, transparent 27%, transparent 74%, rgba(255, 255, 255, .05) 75%, rgba(255, 255, 255, .05) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(255, 255, 255, .05) 25%, rgba(255, 255, 255, .05) 26%, transparent 27%, transparent 74%, rgba(255, 255, 255, .05) 75%, rgba(255, 255, 255, .05) 76%, transparent 77%, transparent); background-size: 50px 50px;">
        </div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="text-4xl md:text-8xl font-black text-white mb-8 tracking-tighter uppercase italic leading-none">Gear
                Up for <br /> Peak Form.</h2>
            <a href="{{ route('preorder.landing') }}"
                class="inline-block px-12 py-6 bg-white text-blue-600 font-black text-xl rounded-2xl hover:scale-110 active:scale-95 transition-all shadow-2xl uppercase tracking-widest">
                Start Order
            </a>
        </div>
    </section>
@endsection
