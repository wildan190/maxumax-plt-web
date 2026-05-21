@extends('layouts.public')

@php
    $selectedSport = request('sport');
    $selectedCategory = request('category');

    $headline = "Season Inventory";
    $subhead = "Precision-engineered athletic wear, available for immediate dispatch. Join the elite.";

    $sportMap = [
        'football series' => [
            'headline' => 'FOOTBALL SERIES',
            'subhead' => 'Official apparel for football teams and associations'
        ],
        'golf series' => [
            'headline' => 'GOLF SERIES',
            'subhead' => 'Premium performance apparel for golfers on and off the course'
        ],
        'fishing series' => [
            'headline' => 'FISHING SERIES',
            'subhead' => 'Technical apparel designed for comfort and protection on every expedition'
        ],
        'basketball series' => [
            'headline' => 'BASKETBALL SERIES',
            'subhead' => 'High-performance apparel for basketball training and competition'
        ],
        'outdoor series' => [
            'headline' => 'OUTDOOR SERIES',
            'subhead' => 'Breatheable performance wear for outdoor pursuits'
        ],
        'run & training series' => [
            'headline' => 'RUN & TRAINING SERIES',
            'subhead' => 'Performance apparel designed for running and intensive training'
        ],
        'casual / lifestyle' => [
            'headline' => 'CASUAL/LIFESTYLE',
            'subhead' => 'Modern essentials for everyday comfort and style'
        ],
        'casual/lifestyle' => [
            'headline' => 'CASUAL/LIFESTYLE',
            'subhead' => 'Modern essentials for everyday comfort and style'
        ],
    ];

    $categoryMap = [
        'jerseys' => [
            'headline' => 'JERSEYS',
            'subhead' => 'Lightweight performance apparel engineered for training and competition'
        ],
        'polos' => [
            'headline' => 'POLOS',
            'subhead' => 'Smart and versatile apparel for professional and casual wear'
        ],
        'shirts' => [
            'headline' => 'SHIRTS',
            'subhead' => 'Functional button-up apparel designed for comfort and everyday use'
        ],
        'outerwear' => [
            'headline' => 'WINDBREAKERS',
            'subhead' => 'Lightweight outerwear that provides protection against wind and light rain'
        ],
        'windbreakers' => [
            'headline' => 'WINDBREAKERS',
            'subhead' => 'Lightweight outerwear that provides protection against wind and light rain'
        ],
        'tracksuits' => [
            'headline' => 'TRACKSUITS',
            'subhead' => 'Coordinated performance wear for training, travel and team presentation'
        ],
        'pants' => [
            'headline' => 'PANTS',
            'subhead' => 'Comfortable and functional bottoms designed for training and everyday wear'
        ],
        'base layer' => [
            'headline' => 'BASE LAYER/INNER',
            'subhead' => 'Performance innerwear designed for comfort'
        ],
        'base layer/inner' => [
            'headline' => 'BASE LAYER/INNER',
            'subhead' => 'Performance innerwear designed for comfort'
        ],
        'base layer / inner' => [
            'headline' => 'BASE LAYER/INNER',
            'subhead' => 'Performance innerwear designed for comfort'
        ],
        'cotton' => [
            'headline' => 'COTTON',
            'subhead' => 'Soft and breatheable everyday apparel for casual comfort'
        ],
        'socks' => [
            'headline' => 'SOCKS',
            'subhead' => 'Performance socks designed for cushioning and all-day comfort'
        ],
        'sleeve socks' => [
            'headline' => 'SLEEVE SOCKS',
            'subhead' => 'Compression-style sleeve that provide support and a professional match-day look'
        ],
        'caps' => [
            'headline' => 'CAPS',
            'subhead' => 'Practical headwear offering comfort and protection'
        ],
        'accessories' => [
            'headline' => 'ACCESSORIES',
            'subhead' => 'Official merchandise and accessories for everyday use'
        ]
    ];

    if ($selectedSport && isset($sportMap[strtolower(trim($selectedSport))])) {
        $mapped = $sportMap[strtolower(trim($selectedSport))];
        $headline = $mapped['headline'];
        $subhead = $mapped['subhead'];
    } elseif ($selectedCategory && isset($categoryMap[strtolower(trim($selectedCategory))])) {
        $mapped = $categoryMap[strtolower(trim($selectedCategory))];
        $headline = $mapped['headline'];
        $subhead = $mapped['subhead'];
    }
@endphp

@section('title', $headline . ' - Maxumax')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[40vh] flex items-center justify-center overflow-hidden bg-black border-b border-white/5">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-black via-zinc-900 to-black opacity-95"></div>
            <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-white/5 blur-[120px]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
            @if($headline === 'Season Inventory')
                <h1 class="text-white font-black text-5xl md:text-7xl mb-6 tracking-tighter uppercase italic leading-none">
                    Season <span class="text-white/40">Inventory.</span>
                </h1>
            @else
                <h1 class="text-white font-black text-5xl md:text-7xl mb-6 tracking-tighter uppercase italic leading-none">
                    {{ $headline }}
                </h1>
            @endif
            <p class="text-white/40 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                {{ $subhead }}
            </p>
        </div>
    </section>

    <!-- Product Showcase Section -->
    <section class="bg-black py-24 px-6 relative" x-data="{ 
        filterCategory: '{{ request('category', '') }}', 
        filterSport: '{{ request('sport', '') }}',
        filterMaterial: '{{ request('material', '') }}',
        filterGender: '{{ request('gender', '') }}',
        filterFit: '{{ request('fit', '') }}'
    }">
        <div class="max-w-7xl mx-auto">
            <!-- Filter Bar -->
            <div class="flex flex-wrap gap-4 justify-center items-center mb-16 relative z-20">
                
                <!-- Category Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-[#111111] text-white border border-white/20 rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/5 group shadow-xl">
                        <span class="truncate pr-2" x-text="filterCategory ? filterCategory : 'Category'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-white/50 group-hover:text-white transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden shadow-2xl divide-y divide-white/5 max-h-60 overflow-y-auto">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['category' => ''])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('category') == '' ? 'text-white bg-white/5' : 'text-white/40' }}">
                            All Categories
                        </a>
                        @foreach(['Jerseys', 'Polos', 'Shirts', 'Outerwear', 'Tracksuits', 'Pants', 'Base Layer', 'Cotton', 'Socks', 'Sleeve Socks', 'Caps', 'Accessories'] as $cat)
                            <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $cat])) }}" 
                                class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('category') == $cat ? 'text-white bg-white/5' : 'text-white/40' }}">
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Sport Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-[#111111] text-white border border-white/20 rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/5 group shadow-xl">
                        <span class="truncate pr-2" x-text="filterSport ? filterSport : 'Sport'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-white/50 group-hover:text-white transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden shadow-2xl divide-y divide-white/5 max-h-60 overflow-y-auto">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sport' => ''])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('sport') == '' ? 'text-white bg-white/5' : 'text-white/40' }}">
                            All Sports
                        </a>
                        @foreach(['Football Series', 'Golf Series', 'Fishing Series', 'Basketball Series', 'Outdoor Series', 'Run & Training Series', 'Casual / Lifestyle'] as $sport)
                            <a href="{{ route('products.index', array_merge(request()->all(), ['sport' => $sport])) }}" 
                                class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('sport') == $sport ? 'text-white bg-white/5' : 'text-white/40' }}">
                                {{ $sport }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Material Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-[#111111] text-white border border-white/20 rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/5 group shadow-xl">
                        <span class="truncate pr-2" x-text="filterMaterial ? filterMaterial : 'Material'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-white/50 group-hover:text-white transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden shadow-2xl divide-y divide-white/5">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['material' => ''])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('material') == '' ? 'text-white bg-white/5' : 'text-white/40' }}">
                            All Materials
                        </a>
                        @foreach(['Polyester', 'Cotton', 'Dry-fit', 'Compression'] as $mat)
                            <a href="{{ route('products.index', array_merge(request()->all(), ['material' => $mat])) }}" 
                                class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('material') == $mat ? 'text-white bg-white/5' : 'text-white/40' }}">
                                {{ $mat }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Gender Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-[#111111] text-white border border-white/20 rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/5 group shadow-xl">
                        <span class="truncate pr-2" x-text="filterGender ? filterGender : 'Gender'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-white/50 group-hover:text-white transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden shadow-2xl divide-y divide-white/5">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['gender' => ''])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('gender') == '' ? 'text-white bg-white/5' : 'text-white/40' }}">
                            All Genders
                        </a>
                        @foreach(['Men', 'Women', 'Unisex'] as $gen)
                            <a href="{{ route('products.index', array_merge(request()->all(), ['gender' => $gen])) }}" 
                                class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('gender') == $gen ? 'text-white bg-white/5' : 'text-white/40' }}">
                                {{ $gen }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-[#111111] text-white border border-white/20 rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/5 group shadow-xl">
                        <span class="truncate pr-2" x-text="'Price Range'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-white/50 group-hover:text-white transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden shadow-2xl divide-y divide-white/5">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_low'])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('sort') == 'price_low' ? 'text-white bg-white/5' : 'text-white/40' }}">
                            Lowest Price
                        </a>
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_high'])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10 {{ request('sort') == 'price_high' ? 'text-white bg-white/5' : 'text-white/40' }}">
                            Highest Price
                        </a>
                    </div>
                </div>

                <!-- Clear Filters -->
                @if(request()->anyFilled(['category', 'sport', 'material', 'gender', 'fit', 'filter']))
                    <a href="{{ route('products.index') }}" 
                        class="text-white/40 hover:text-white font-black text-[10px] uppercase tracking-widest transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        Clear All
                    </a>
                @endif
            </div>

            <div id="products-grid" class="grid gap-3 md:gap-8 grid-cols-2 lg:grid-cols-4">
                @if(isset($products) && $products->count())
                    @foreach($products as $product)
                        <div class="flex flex-col bg-[#111111] rounded-2xl overflow-hidden border border-white/5 hover:border-white/10 transition-all duration-300 group relative">
                            <!-- Product Image -->
                            <div class="aspect-square md:aspect-[4/5] relative flex items-center justify-center p-3 md:p-8 bg-gradient-to-b from-[#1a1a1a] to-[#111111]">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                        class="max-w-[85%] max-h-[85%] md:max-w-full md:max-h-full object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.8)] group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="text-white/10"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:24px;height:24px" class="md:w-[64px] md:h-[64px]"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></div>
                                @endif
                                <span class="absolute top-3 left-3 md:top-6 md:left-6 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[7px] md:text-[9px] font-black px-1.5 md:px-3 py-0.5 md:py-1 rounded-full uppercase tracking-widest shadow-lg">Ready Stock</span>
                                @if($product->on_sale && $product->discounted_price !== null)
                                    <span class="absolute top-3 right-3 md:top-6 md:right-6 bg-rose-500 text-white text-[7px] md:text-[9px] font-black px-1.5 md:px-3 py-0.5 md:py-1 rounded-full uppercase tracking-widest shadow-lg animate-pulse">SALE</span>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-3 md:p-6 flex flex-col bg-[#1a1a1a] flex-grow">
                                <h3 class="text-white font-black text-[9px] md:text-sm uppercase tracking-widest text-center mb-2 md:mb-4 leading-tight min-h-[1.5rem] md:min-h-[2.5rem] flex items-center justify-center">{{ $product->name }}</h3>
                                
                                <!-- Badges -->
                                <div class="flex flex-wrap justify-center gap-1 mb-3 md:mb-8">
                                    @if($product->category)
                                        <span class="px-1.5 md:px-4 py-0.5 md:py-1.5 rounded-full border border-white/10 text-[7px] md:text-[9px] font-black text-white uppercase tracking-widest">
                                            {{ $product->category }}
                                        </span>
                                     @endif
                                </div>

                                <!-- Price and Action -->
                                <div class="mt-auto pt-6 border-t border-white/5 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-[8px] md:text-[10px] font-black text-white/40 uppercase tracking-widest mb-0.5 md:mb-1">{{ $currency }}</span>
                                        @if($product->on_sale && $product->discounted_price !== null)
                                            <div class="flex flex-col gap-0.5">
                                                <span class="text-[10px] md:text-xs font-bold text-white/30 line-through">
                                                    {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                                </span>
                                                <span class="text-sm md:text-xl font-black text-rose-500 leading-none">
                                                    {{ number_format($product->discounted_price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-sm md:text-xl font-black text-white leading-none">
                                                 {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('product.show', $product) }}" 
                                       class="w-7 h-7 md:w-10 md:h-10 bg-white rounded-full flex items-center justify-center text-black hover:bg-slate-200 transition-all hover:scale-110 active:scale-95 shadow-xl after:absolute after:inset-0 after:z-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 md:w-[18px] md:h-[18px]"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-32 bg-white/5 rounded-3xl border border-dashed border-white/10">
                        <h4 class="text-xl font-black text-white mb-2 uppercase tracking-widest">Inventory Empty</h4>
                        <p class="text-white/40 font-medium">We're currently restocking our season drops. Check back soon.</p>
                    </div>
                @endif
            </div>

            @if(isset($products) && $products->hasPages())
                <div class="mt-16 border-t border-white/5 pt-12 flex justify-center">
                    <div class="inline-flex items-center gap-1">
                        {{-- Custom Simple Tailwind Pagination --}}
                        @if(!$products->onFirstPage())
                            <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white/60 hover:bg-white/10 hover:text-white transition-all border border-white/5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </a>
                        @endif

                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page == $products->currentPage())
                                <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-black font-black text-xs shadow-lg shadow-white/10">
                                    {{ $page }}
                                </span>
                            @elseif ($page == 1 || $page == $products->lastPage() || ($page >= $products->currentPage() - 1 && $page <= $products->currentPage() + 1))
                                <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white/60 hover:bg-white/10 hover:text-white transition-all border border-white/5 font-black text-xs">
                                    {{ $page }}
                                </a>
                            @elseif ($page == 2 || $page == $products->lastPage() - 1)
                                <span class="w-10 h-10 flex items-center justify-center text-white/20">...</span>
                            @endif
                        @endforeach

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white/60 hover:bg-white/10 hover:text-white transition-all border border-white/5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection