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
    <section class="bg-black py-24 px-6 relative" x-data="{ 
                activeCategory: '',
                applyFilter(cat) {
                    this.activeCategory = cat;
                    const grid = document.getElementById('products-grid');
                    if (!grid) return;
                    const val = cat.toLowerCase();
                    grid.querySelectorAll('[data-category]').forEach(card => {
                        const cardCat = (card.getAttribute('data-category') || '').toLowerCase();
                        if (!val || cardCat === val) {
                           card.style.display = '';
                        } else {
                           card.style.display = 'none';
                        }
                    });
                }
            }">
        <div class="max-w-7xl mx-auto">
            @php
                $categories = isset($products) ? $products->pluck('jersey_type')->filter()->unique()->sort()->values() : collect();
            @endphp

            <div class="flex flex-col md:flex-row items-center justify-between mb-20 gap-8">
                <div class="inline-flex flex-wrap gap-3 bg-white/5 p-2 rounded-2xl border border-white/10">
                    <button @click="applyFilter('')"
                        class="px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all"
                        :class="activeCategory === '' ? 'bg-white text-black shadow-xl' : 'text-white/40 hover:bg-white/10'">
                        All Editions
                    </button>
                    @foreach($categories as $cat)
                        <button @click="applyFilter('{{ $cat }}')"
                            class="px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all"
                            :class="activeCategory === '{{ $cat }}' ? 'bg-white text-black shadow-xl' : 'text-white/40 hover:bg-white/10'">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
                <div class="text-white/20 font-black text-xs uppercase tracking-widest">
                    {{ $products->count() }} Items Available
                </div>
            </div>

            <div id="products-grid" class="grid gap-3 md:gap-8 grid-cols-2 lg:grid-cols-4">
                @if(isset($products) && $products->count())
                    @foreach($products as $product)
                        <div class="flex flex-col bg-[#111111] rounded-2xl overflow-hidden border border-white/5 hover:border-white/10 transition-all duration-300 group"
                            data-category="{{ strtolower($product->jersey_type ?? '') }}">
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
                                    @if($product->jersey_type)
                                        <span class="px-1.5 md:px-4 py-0.5 md:py-1.5 rounded-full border border-white/10 text-[7px] md:text-[9px] font-black text-white uppercase tracking-widest">
                                            {{ $product->jersey_type }}
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