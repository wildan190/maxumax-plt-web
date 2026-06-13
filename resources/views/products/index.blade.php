@extends('layouts.public')

@php
    $selectedSport = request('sport');
    $selectedCategory = request('category');

    $headline = "Season Inventory";
    $subhead = "Precision-engineered athletic wear, available for immediate dispatch. Join the elite.";

    $sportMap = [
        'football' => [
            'headline' => 'FOOTBALL SERIES',
            'subhead' => 'Official apparel for football teams and associations'
        ],
        'football series' => [
            'headline' => 'FOOTBALL SERIES',
            'subhead' => 'Official apparel for football teams and associations'
        ],
        'golf' => [
            'headline' => 'GOLF SERIES',
            'subhead' => 'Premium performance apparel for golfers on and off the course'
        ],
        'golf series' => [
            'headline' => 'GOLF SERIES',
            'subhead' => 'Premium performance apparel for golfers on and off the course'
        ],
        'fishing' => [
            'headline' => 'FISHING SERIES',
            'subhead' => 'Technical apparel designed for comfort and protection on every expedition'
        ],
        'fishing series' => [
            'headline' => 'FISHING SERIES',
            'subhead' => 'Technical apparel designed for comfort and protection on every expedition'
        ],
        'basketball' => [
            'headline' => 'BASKETBALL SERIES',
            'subhead' => 'High-performance apparel for basketball training and competition'
        ],
        'basketball series' => [
            'headline' => 'BASKETBALL SERIES',
            'subhead' => 'High-performance apparel for basketball training and competition'
        ],
        'outdoor' => [
            'headline' => 'OUTDOOR SERIES',
            'subhead' => 'Breatheable performance wear for outdoor pursuits'
        ],
        'outdoor series' => [
            'headline' => 'OUTDOOR SERIES',
            'subhead' => 'Breatheable performance wear for outdoor pursuits'
        ],
        'run & training' => [
            'headline' => 'RUN & TRAINING SERIES',
            'subhead' => 'Performance apparel designed for running and intensive training'
        ],
        'run & training series' => [
            'headline' => 'RUN & TRAINING SERIES',
            'subhead' => 'Performance apparel designed for running and intensive training'
        ],
        'lifestyle' => [
            'headline' => 'CASUAL/LIFESTYLE',
            'subhead' => 'Modern essentials for everyday comfort and style'
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
        ],
        'sale' => [
            'headline' => 'EXCLUSIVE DEALS',
            'subhead' => 'Premium performance gear at special prices. Limited stock available.'
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
    <section class="relative min-h-[40vh] flex items-center justify-center overflow-hidden bg-white border-b border-[#E8E8E3]">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-white via-[#F7F7F5] to-white opacity-100"></div>
            <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-[#155EEF]/5 blur-[120px]"></div>
        </div>

        <div class="relative z-10 px-6 text-center" style="max-width: 1280px; margin: 0 auto;">
            @if(request('shop_by') === 'sport')
                <h1 class="text-[#111111] font-black text-5xl md:text-7xl mb-6 tracking-tighter uppercase italic leading-none">
                    Shop by <span class="text-[#666666]">Sport.</span>
                </h1>
                <p class="text-[#666666] text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                    Select your discipline to find specialized performance gear.
                </p>
            @elseif(request('shop_by') === 'product')
                <h1 class="text-[#111111] font-black text-5xl md:text-7xl mb-6 tracking-tighter uppercase italic leading-none">
                    Shop by <span class="text-[#666666]">Product.</span>
                </h1>
                <p class="text-[#666666] text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                    Browse our full range of technical apparel by category.
                </p>
            @elseif($headline === 'Season Inventory')
                <h1 class="text-[#111111] font-black text-5xl md:text-7xl mb-6 tracking-tighter uppercase italic leading-none">
                    Season <span class="text-[#666666]">Inventory.</span>
                </h1>
                <p class="text-[#666666] text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                    {{ $subhead }}
                </p>
            @else
                <h1 class="text-[#111111] font-black text-5xl md:text-7xl mb-6 tracking-tighter uppercase italic leading-none">
                    {{ $headline }}
                </h1>
                <p class="text-[#666666] text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                    {{ $subhead }}
                </p>
            @endif
        </div>
    </section>

    <!-- Product Showcase Section -->
    <section class="bg-white py-24 px-6 relative" x-data="{ 
        filterCategory: '{{ request('category', '') }}', 
        filterSport: '{{ request('sport', '') }}',
        filterMaterial: '{{ request('material', '') }}',
        filterGender: '{{ request('gender', '') }}',
        filterFit: '{{ request('fit', '') }}'
    }">
        <div style="max-width: 1280px; margin: 0 auto;">
            
            @if(request('shop_by') === 'sport' && !request('sport'))
                <!-- Shop by Sport Selection Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-24">
                    @foreach ($shopBySportItems as $sportItem)
                        <a href="{{ $sportItem['href'] }}" class="group relative aspect-[16/9] rounded-2xl overflow-hidden bg-[#F7F7F5] border border-[#E8E8E3] block">
                            <img src="{{ $sportItem['img'] }}" alt="{{ $sportItem['label'] }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h3 class="text-[#111111] font-black text-xl md:text-2xl uppercase tracking-widest leading-tight group-hover:text-[#155EEF] transition-colors">{{ $sportItem['label'] }}</h3>
                                <div class="mt-2 inline-flex items-center gap-2 text-xs font-bold text-[#666666] uppercase tracking-widest group-hover:text-[#111111] transition-colors">
                                    Explore <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @elseif(request('shop_by') === 'product' && !request('category'))
                <!-- Shop by Product Selection Grid -->
                <div class="flex flex-wrap justify-center gap-4 mb-24">
                    @foreach(['Jerseys', 'Polos', 'Shirts', 'Outerwear', 'Tracksuits', 'Pants', 'Base Layer', 'Cotton', 'Socks', 'Sleeve Socks', 'Caps', 'Accessories'] as $cat)
                        <a href="{{ route('products.index', ['category' => $cat]) }}" class="px-8 py-4 bg-white hover:bg-[#155EEF] hover:text-white border border-[#E8E8E3] text-[#111111] rounded-full font-black text-sm uppercase tracking-widest transition-all duration-300 hover:scale-105 shadow-xl">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Filter Bar -->
            <div class="flex flex-wrap gap-4 justify-center items-center mb-16 relative z-20">
                
                <!-- Category Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-white text-[#111111] border border-[#E8E8E3] rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] hover:border-[#155EEF] group shadow-xl">
                        <span class="truncate pr-2" x-text="filterCategory ? filterCategory : 'Category'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-[#666666] group-hover:text-[#155EEF] transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-white border border-[#E8E8E3] rounded-xl overflow-hidden shadow-2xl divide-y divide-[#E8E8E3] max-h-60 overflow-y-auto">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['category' => ''])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('category') == '' ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                            All Categories
                        </a>
                        @foreach(['Jerseys', 'Polos', 'Shirts', 'Outerwear', 'Tracksuits', 'Pants', 'Base Layer', 'Cotton', 'Socks', 'Sleeve Socks', 'Caps', 'Accessories'] as $cat)
                            <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $cat])) }}" 
                                class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('category') == $cat ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Sport Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-white text-[#111111] border border-[#E8E8E3] rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] hover:border-[#155EEF] group shadow-xl">
                        <span class="truncate pr-2" x-text="filterSport ? filterSport : 'Sport'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-[#666666] group-hover:text-[#155EEF] transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-white border border-[#E8E8E3] rounded-xl overflow-hidden shadow-2xl divide-y divide-[#E8E8E3] max-h-60 overflow-y-auto">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sport' => ''])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('sport') == '' ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                            All Sports
                        </a>
                        @foreach(['Football Series', 'Golf Series', 'Fishing Series', 'Basketball Series', 'Outdoor Series', 'Run & Training Series', 'Casual / Lifestyle'] as $sport)
                            <a href="{{ route('products.index', array_merge(request()->all(), ['sport' => $sport])) }}" 
                                class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('sport') == $sport ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                                {{ $sport }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Material Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-white text-[#111111] border border-[#E8E8E3] rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] hover:border-[#155EEF] group shadow-xl">
                        <span class="truncate pr-2" x-text="filterMaterial ? filterMaterial : 'Material'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-[#666666] group-hover:text-[#155EEF] transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-white border border-[#E8E8E3] rounded-xl overflow-hidden shadow-2xl divide-y divide-[#E8E8E3]">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['material' => ''])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('material') == '' ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                            All Materials
                        </a>
                        @foreach(['Polyester', 'Cotton', 'Dry-fit', 'Compression'] as $mat)
                            <a href="{{ route('products.index', array_merge(request()->all(), ['material' => $mat])) }}" 
                                class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('material') == $mat ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                                {{ $mat }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Gender Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-white text-[#111111] border border-[#E8E8E3] rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] hover:border-[#155EEF] group shadow-xl">
                        <span class="truncate pr-2" x-text="filterGender ? filterGender : 'Gender'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-[#666666] group-hover:text-[#155EEF] transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-white border border-[#E8E8E3] rounded-xl overflow-hidden shadow-2xl divide-y divide-[#E8E8E3]">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['gender' => ''])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('gender') == '' ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                            All Genders
                        </a>
                        @foreach(['Men', 'Women', 'Unisex'] as $gen)
                            <a href="{{ route('products.index', array_merge(request()->all(), ['gender' => $gen])) }}" 
                                class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('gender') == $gen ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                                {{ $gen }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="relative w-full md:w-48" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-white text-[#111111] border border-[#E8E8E3] rounded-xl px-4 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] hover:border-[#155EEF] group shadow-xl">
                        <span class="truncate pr-2" x-text="'Price Range'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-[#666666] group-hover:text-[#155EEF] transition-transform" :class="{'rotate-180': open}"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak
                        class="absolute z-50 w-full mt-2 bg-white border border-[#E8E8E3] rounded-xl overflow-hidden shadow-2xl divide-y divide-[#E8E8E3]">
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_low'])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('sort') == 'price_low' ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                            Lowest Price
                        </a>
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_high'])) }}" 
                            class="block w-full text-left px-5 py-3 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-[#F7F7F5] {{ request('sort') == 'price_high' ? 'text-[#155EEF] bg-[#F7F7F5]' : 'text-[#666666]' }}">
                            Highest Price
                        </a>
                    </div>
                </div>

                <!-- Clear Filters -->
                @if(request()->anyFilled(['category', 'sport', 'material', 'gender', 'fit', 'filter']))
                    <a href="{{ route('products.index') }}" 
                        class="text-[#666666] hover:text-[#111111] font-black text-[10px] uppercase tracking-widest transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        Clear All
                    </a>
                @endif
            </div>

            <div id="products-grid" class="grid gap-3 md:gap-8 grid-cols-2 lg:grid-cols-4">
                @if(isset($products) && $products->count())
                    @foreach($products as $product)
                        <div class="flex flex-col bg-white rounded-2xl overflow-hidden border border-[#E8E8E3] hover:border-[#155EEF] transition-all duration-300 group relative cursor-pointer" onclick="window.location='{{ route('product.show', $product) }}'">
                            <!-- Product Image -->
                            <div class="aspect-square md:aspect-[4/5] relative flex items-center justify-center p-3 md:p-8 bg-gradient-to-b from-[#F7F7F5] to-white">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                        class="max-w-[85%] max-h-[85%] md:max-w-full md:max-h-full object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.08)] group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="text-[#E8E8E3]"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:24px;height:24px" class="md:w-[64px] md:h-[64px]"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></div>
                                @endif
                                <span class="absolute top-3 left-3 md:top-6 md:left-6 bg-emerald-500/10 text-emerald-600 border border-emerald-200 text-[7px] md:text-[9px] font-black px-1.5 md:px-3 py-0.5 md:py-1 rounded-full uppercase tracking-widest shadow-lg">Ready Stock</span>
                                @if($product->on_sale && $product->discounted_price !== null)
                                    <span class="absolute top-3 right-3 md:top-6 md:right-6 bg-red-500 text-white text-[7px] md:text-[9px] font-black px-1.5 md:px-3 py-0.5 md:py-1 rounded-full uppercase tracking-widest shadow-lg animate-pulse">SALE</span>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-3 md:p-6 flex flex-col bg-white flex-grow">
                                <h3 class="text-[#111111] font-black text-[9px] md:text-sm uppercase tracking-widest text-center mb-2 md:mb-4 leading-tight min-h-[1.5rem] md:min-h-[2.5rem] flex items-center justify-center">{{ $product->name }}</h3>
                                
                                <!-- Badges -->
                                <div class="flex flex-wrap justify-center gap-1 mb-3 md:mb-8">
                                    @if($product->category)
                                        <span class="px-1.5 md:px-4 py-0.5 md:py-1.5 rounded-full border border-[#E8E8E3] text-[7px] md:text-[9px] font-black text-[#666666] uppercase tracking-widest">
                                            {{ $product->category }}
                                        </span>
                                     @endif
                                </div>

                                <!-- Price and Action -->
                                <div class="mt-auto pt-6 border-t border-[#E8E8E3] flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-[8px] md:text-[10px] font-black text-[#666666] uppercase tracking-widest mb-0.5 md:mb-1">{{ $currency }}</span>
                                        @if($product->on_sale && $product->discounted_price !== null)
                                            <div class="flex flex-col gap-0.5">
                                                <span class="text-[10px] md:text-xs font-bold text-[#999999] line-through">
                                                    {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                                </span>
                                                <span class="text-sm md:text-xl font-black text-red-600 leading-none">
                                                    {{ number_format($product->discounted_price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-sm md:text-xl font-black text-[#111111] leading-none">
                                                 {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="w-7 h-7 md:w-10 md:h-10 bg-[#155EEF] rounded-full flex items-center justify-center text-white hover:bg-[#0D4BC3] transition-all hover:scale-110 active:scale-95 shadow-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 md:w-[18px] md:h-[18px]"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-32 bg-[#F7F7F5] rounded-3xl border border-dashed border-[#E8E8E3]">
                        <h4 class="text-xl font-black text-[#111111] mb-2 uppercase tracking-widest">Inventory Empty</h4>
                        <p class="text-[#666666] font-medium">We're currently restocking our season drops. Check back soon.</p>
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