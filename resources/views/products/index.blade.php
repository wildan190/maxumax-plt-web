@extends('layouts.public')

@section('title', 'Season Inventory - Maxumax')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[40vh] flex items-center justify-center overflow-hidden bg-black border-b border-white/5">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-black via-zinc-900 to-black opacity-95"></div>
            <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-white/5 blur-[120px]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
            <span class="inline-block px-5 py-2 mb-6 text-[10px] font-black tracking-[0.3em] text-white/40 uppercase bg-white/5 border border-white/10 rounded-full">
                Ready Stock Drops
            </span>
            <h1 class="text-white font-black text-5xl md:text-7xl mb-6 tracking-tighter uppercase italic leading-none">
                Season <span class="text-white/40">Inventory.</span>
            </h1>
            <p class="text-white/40 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                Precision-engineered athletic wear, available for immediate dispatch. Join the elite.
            </p>
        </div>
    </section>

    <!-- Product Showcase Section -->
    <section class="bg-black py-24 px-6 relative" x-data="{ filterCategory: '', filterCollection: '' }">
        <div class="max-w-7xl mx-auto">
            @php
                $categories = isset($products) ? $products->pluck('category')->filter()->unique()->sort()->values() : collect();
                $collections = isset($products) ? $products->pluck('collection')->filter()->unique()->sort()->values() : collect();
            @endphp

            <div class="flex flex-col md:flex-row gap-4 justify-center items-center mb-16 max-w-3xl mx-auto relative z-20">
                <!-- Custom Category Dropdown -->
                <div class="relative w-full md:w-64" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-[#111111] text-white border border-white/20 rounded-xl px-4 md:px-5 py-3.5 font-black text-[10px] md:text-xs uppercase tracking-widest transition-all hover:bg-white/5 hover:border-white/30 group shadow-xl">
                        <span class="truncate pr-2" x-text="filterCategory ? filterCategory : 'All Categories'"></span>
                        <svg class="w-3.5 h-3.5 text-white/50 flex-shrink-0 group-hover:text-white transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms x-cloak
                        class="absolute z-50 w-full mt-2 bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden shadow-2xl divide-y divide-white/5">
                        <button @click="filterCategory = ''; filterCollection = ''; open = false" 
                            class="w-full text-left px-5 py-3.5 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10"
                            :class="filterCategory === '' ? 'text-white bg-white/5' : 'text-white/40'">
                            All Categories
                        </button>
                        @foreach($categories as $cat)
                            <button @click="filterCategory = '{{ $cat }}'; filterCollection = ''; open = false" 
                                class="w-full text-left px-5 py-3.5 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10"
                                :class="filterCategory === '{{ $cat }}' ? 'text-white bg-white/5' : 'text-white/40'">
                                {{ $cat }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Custom Collection Dropdown -->
                <div class="relative w-full md:w-64" x-data="{ open: false }" x-show="filterCategory !== ''" x-transition x-cloak>
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-between w-full bg-[#111111] text-white border border-white/20 rounded-xl px-4 md:px-5 py-3.5 font-black text-[10px] md:text-xs uppercase tracking-widest transition-all hover:bg-white/5 hover:border-white/30 group shadow-xl">
                        <span class="truncate pr-2" x-text="filterCollection ? filterCollection : 'All Collections'"></span>
                        <svg class="w-3.5 h-3.5 text-white/50 flex-shrink-0 group-hover:text-white transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms x-cloak
                        class="absolute z-50 w-full mt-2 bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden shadow-2xl divide-y divide-white/5 max-h-60 overflow-y-auto">
                        <button @click="filterCollection = ''; open = false" 
                            class="w-full text-left px-5 py-3.5 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10"
                            :class="filterCollection === '' ? 'text-white bg-white/5' : 'text-white/40'">
                            All Collections
                        </button>
                        @foreach($collections as $col)
                            <button @click="filterCollection = '{{ $col }}'; open = false" 
                                class="w-full text-left px-5 py-3.5 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-white/10"
                                :class="filterCollection === '{{ $col }}' ? 'text-white bg-white/5' : 'text-white/40'">
                                {{ $col }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div id="products-grid" class="grid gap-3 md:gap-8 grid-cols-2 lg:grid-cols-4">
                @if(isset($products) && $products->count())
                    @foreach($products as $product)
                        <div x-show="(filterCategory === '' || '{{ $product->category }}' === filterCategory) && (filterCollection === '' || '{{ $product->collection }}' === filterCollection)"
                             class="flex flex-col bg-[#111111] rounded-2xl overflow-hidden border border-white/5 hover:border-white/10 transition-all duration-300 group">
                            <!-- Product Image -->
                            <div class="aspect-square md:aspect-[4/5] relative flex items-center justify-center p-3 md:p-8 bg-gradient-to-b from-[#1a1a1a] to-[#111111]">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                        class="max-w-[85%] max-h-[85%] md:max-w-full md:max-h-full object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.8)] group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="text-white/10"><i data-feather="image" style="width:24px;height:24px" class="md:w-[64px] md:h-[64px]"></i></div>
                                @endif
                                <span class="absolute top-3 left-3 md:top-6 md:left-6 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[7px] md:text-[9px] font-black px-1.5 md:px-3 py-0.5 md:py-1 rounded-full uppercase tracking-widest shadow-lg">Ready Stock</span>
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
                                        <span class="text-sm md:text-xl font-black text-white leading-none">
                                             {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('product.show', $product) }}" 
                                       class="w-7 h-7 md:w-10 md:h-10 bg-white rounded-full flex items-center justify-center text-black hover:bg-slate-200 transition-all hover:scale-110 active:scale-95 shadow-xl">
                                        <i data-feather="arrow-right" class="w-3.5 h-3.5 md:w-[18px] md:h-[18px]"></i>
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
        </div>
    </section>
@endsection