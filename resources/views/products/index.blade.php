@extends('layouts.public')

@section('title', 'Season Inventory - Maxumax')

@section('content')
    <!-- Hero Section -->
    <section
        class="relative min-h-[40vh] flex items-center justify-center overflow-hidden bg-slate-900 border-b border-white/10">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-black opacity-95"></div>
            <div
                class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-blue-600/10 blur-[120px] animate-pulse">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
            <span
                class="inline-block px-5 py-2 mb-6 text-xs font-black tracking-[0.3em] text-blue-400 uppercase bg-blue-400/5 border border-blue-400/20 rounded-full">
                Ready Stock Drops
            </span>
            <h1 class="text-white font-black text-5xl md:text-7xl mb-6 tracking-tighter uppercase italic leading-none">
                Season <span class="text-blue-400">Inventory.</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                Precision-engineered athletic wear, available for immediate dispatch. Join the elite.
            </p>
        </div>
    </section>

    <!-- Product Showcase Section -->
    <section class="bg-white py-24 px-6 relative" x-data="{ 
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

            <div class="flex flex-col md:flex-row items-center justify-between mb-16 gap-8">
                <div class="inline-flex flex-wrap gap-3 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                    <button @click="applyFilter('')"
                        class="px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all"
                        :class="activeCategory === '' ? 'bg-slate-900 text-white shadow-xl shadow-slate-900/20' : 'text-slate-500 hover:bg-slate-100'">
                        All Editions
                    </button>
                    @foreach($categories as $cat)
                        <button @click="applyFilter('{{ $cat }}')"
                            class="px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all"
                            :class="activeCategory === '{{ $cat }}' ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-100'">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
                <div class="text-slate-400 font-bold text-sm uppercase tracking-widest">
                    {{ $products->count() }} Items Available
                </div>
            </div>

            <div id="products-grid" class="grid gap-12 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @if(isset($products) && $products->count())
                    @foreach($products as $product)
                        <a href="{{ route('product.show', $product) }}"
                            class="group block bg-white rounded-[2.5rem] border border-slate-100 hover:border-blue-600/20 hover:shadow-2xl hover:shadow-blue-600/5 transition-all duration-500 overflow-hidden"
                            data-category="{{ strtolower($product->jersey_type ?? '') }}">
                            <div class="relative aspect-square bg-slate-50 flex items-center justify-center p-8 overflow-hidden">
                                <span
                                    class="absolute top-6 left-6 bg-emerald-500 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg shadow-emerald-500/20">Ready
                                    Stock</span>
                                @if($product->image_path)
                                    <img class="w-full h-full object-contain drop-shadow-2xl transition-transform duration-700 group-hover:scale-110 group-hover:rotate-2"
                                        src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" />
                                @else
                                    <div class="text-slate-300 scale-[2]"><i data-feather="image"></i></div>
                                @endif
                                <div class="absolute inset-0 bg-blue-600/5 opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                            </div>
                            <div class="p-8">
                                <span
                                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest block mb-2">{{ $product->jersey_type ?? 'Performance' }}</span>
                                <h3
                                    class="text-xl font-black text-slate-900 mb-6 tracking-tight group-hover:text-blue-600 transition-colors">
                                    {{ $product->name }}</h3>

                                <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                    <div class="text-2xl font-black text-slate-900 flex items-baseline gap-1">
                                        <span class="text-xs font-bold text-slate-400">{{ $currency }}</span>
                                        {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                    </div>
                                    <div
                                        class="w-12 h-12 bg-slate-900 rounded-full flex items-center justify-center text-white group-hover:bg-blue-600 group-hover:scale-110 transition-all shadow-xl">
                                        <i data-feather="arrow-right" style="width:20px;height:20px;"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div
                        class="col-span-full text-center py-24 bg-slate-50 rounded-[3rem] border border-dashed border-slate-200">
                        <div class="text-6xl mb-6 grayscale opacity-30">📦</div>
                        <h4 class="text-xl font-bold text-slate-900 mb-2">Inventory Empty</h4>
                        <p class="text-slate-500 font-medium">We're currently restocking our season drops. Check back soon.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection