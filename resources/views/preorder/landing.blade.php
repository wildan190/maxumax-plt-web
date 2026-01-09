@extends('layouts.public')

@section('title', 'Pre-order Jersey - MaxuMax')

 

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 text-center py-24 px-4">
        <div class="relative z-10 max-w-6xl mx-auto">
            <h1 class="text-white font-black text-4xl md:text-6xl mb-4">Exclusive MaxuMax Jerseys</h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto mb-8">
                Pre-order our limited edition jerseys now. Pay on delivery when we arrive in Brunei — late January 2026.
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-2 rounded-full font-semibold text-sm">
                    <span class="inline-flex w-5 h-5 rounded-full bg-emerald-500 items-center justify-center"><i data-feather="check" style="width:12px;height:12px;"></i></span>
                    Pay on Delivery
                </div>
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-2 rounded-full font-semibold text-sm">
                    <span class="inline-flex w-5 h-5 rounded-full bg-emerald-500 items-center justify-center"><i data-feather="check" style="width:12px;height:12px;"></i></span>
                    4 Jersey Options
                </div>
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-2 rounded-full font-semibold text-sm">
                    <span class="inline-flex w-5 h-5 rounded-full bg-emerald-500 items-center justify-center"><i data-feather="check" style="width:12px;height:12px;"></i></span>
                    Full Customization
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-50">
                    <img class="w-full h-full object-cover" data-jpeg="{{ asset('assets/img/banner1.jpeg') }}" alt="MaxuMax Banner 1" />
                </div>
                <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-50">
                    <img class="w-full h-full object-cover" data-jpeg="{{ asset('assets/img/banner2.jpeg') }}" alt="MaxuMax Banner 2" />
                </div>
            </div>
        </div>
    </section>

    <!-- Product Showcase Section -->
    <section id="products" class="bg-slate-100 py-16 px-4">
        <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-2">Choose Your Jersey</h2>
            <p class="text-slate-500 text-base">Filter by category and switch between Pre-order and Available Now</p>
        </div>

        <div class="flex justify-center gap-2 mb-6">
            <button class="tab-btn inline-flex items-center rounded-full border px-4 py-2 font-semibold text-sm bg-black text-white border-black" data-tab="preorder">Pre-order</button>
            <button class="tab-btn inline-flex items-center rounded-full border px-4 py-2 font-semibold text-sm bg-white text-slate-800 border-slate-300" data-tab="active">Available Now</button>
        </div>

        @php
            $preorderProducts = isset($products) ? $products->filter(fn($p) => $p->available_for_preorder) : collect();
            $activeProducts = isset($products) ? $products->filter(fn($p) => $p->is_active && !$p->available_for_preorder) : collect();
            $categories = isset($products) ? $products->pluck('jersey_type')->filter()->unique()->sort()->values() : collect();
        @endphp

        <div class="w-full flex justify-end mb-6">
            <select id="categoryFilter" class="bg-white border border-slate-300 rounded-lg px-3 py-2 min-w-[220px] font-semibold text-slate-800">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div id="grid-preorder" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @if($preorderProducts->count())
                @foreach($preorderProducts as $product)
                    <a href="{{ route('preorder.create', $product) }}" class="group bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition" data-category="{{ strtolower($product->jersey_type ?? '') }}">
                        <div class="relative aspect-[4/3] bg-slate-50 flex items-center justify-center">
                            <span class="absolute top-3 left-3 inline-flex items-center rounded bg-black text-white text-xs font-semibold px-2 py-1">Pre-order</span>
                            @if($product->image_path)
                                <img class="w-full h-full object-contain p-2" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" />
                            @else
                                <span class="text-4xl"><i data-feather="image"></i></span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $product->name }}</h3>
                            @if($product->jersey_type)
                                <span class="inline-block bg-slate-100 text-slate-500 text-xs font-semibold px-2 py-1 rounded mb-2">{{ $product->jersey_type }}</span>
                            @endif
                            <p class="text-slate-600 text-sm mb-4">
                                {{ $product->description ?: 'Premium quality jersey with breathable fabric.' }}</p>
                            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                                <div class="text-xl font-extrabold text-black">
                                    <span class="text-sm font-medium text-slate-500">RM</span> {{ number_format($product->price, 2) }}
                                </div>
                                <div class="inline-flex items-center rounded-full bg-black text-white px-4 py-2 text-sm font-semibold group-hover:bg-slate-900 transition">Order Now →</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="col-span-full text-center p-10 bg-white rounded-xl text-slate-500">
                    <div class="text-5xl mb-2 opacity-50">📦</div>
                    <p>No pre-order products available at the moment. Check back soon!</p>
                </div>
            @endif
        </div>

        <div id="grid-active" class="hidden grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @if($activeProducts->count())
                @foreach($activeProducts as $product)
                    <a href="{{ route('product.show', $product) }}" class="group bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition" data-category="{{ strtolower($product->jersey_type ?? '') }}">
                        <div class="relative aspect-[4/3] bg-slate-50 flex items-center justify-center">
                            @if($product->image_path)
                                <img class="w-full h-full object-contain p-2" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" />
                            @else
                                <span class="text-4xl">👕</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $product->name }}</h3>
                            @if($product->jersey_type)
                                <span class="inline-block bg-slate-100 text-slate-500 text-xs font-semibold px-2 py-1 rounded mb-2">{{ $product->jersey_type }}</span>
                            @endif
                            <p class="text-slate-600 text-sm mb-4">
                                {{ $product->description ?: 'Premium quality jersey with breathable fabric.' }}</p>
                            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                                <div class="text-xl font-extrabold text-black">
                                    <span class="text-sm font-medium text-slate-500">RM</span> {{ number_format($product->price, 2) }}
                                </div>
                                <div class="inline-flex items-center rounded-full bg-black text-white px-4 py-2 text-sm font-semibold group-hover:bg-slate-900 transition">Order Now →</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="col-span-full text-center p-10 bg-white rounded-xl text-slate-500">
                    <div class="text-5xl mb-2 opacity-50">📦</div>
                    <p>No active products available at the moment. Check back soon!</p>
                </div>
            @endif
        </div>
        </div>
    </section>

    <script>
        (function(){
            const imgs = document.querySelectorAll('img[data-jpeg]');
            imgs.forEach(function(img){
                const url = img.getAttribute('data-jpeg');
                const im = new Image();
                im.onload = function(){
                    const c = document.createElement('canvas');
                    c.width = im.naturalWidth;
                    c.height = im.naturalHeight;
                    const ctx = c.getContext('2d');
                    ctx.drawImage(im, 0, 0);
                    let webp;
                    try {
                        webp = c.toDataURL('image/webp', 0.9);
                    } catch(e) {
                        webp = null;
                    }
                    img.src = webp && webp.indexOf('data:image/webp') === 0 ? webp : url;
                };
                im.onerror = function(){
                    img.src = url;
                };
                im.src = url;
            });
        })();

        const tabs = document.querySelectorAll('.tab-btn');
        const gridPre = document.getElementById('grid-preorder');
        const gridAct = document.getElementById('grid-active');
        const catFilter = document.getElementById('categoryFilter');

        function setActive(btn) {
            tabs.forEach(b => {
                b.classList.remove('bg-black','text-white','border-black');
                b.classList.add('bg-white','text-slate-800','border-slate-300');
            });
            btn.classList.remove('bg-white','text-slate-800','border-slate-300');
            btn.classList.add('bg-black','text-white','border-black');
        }

        tabs.forEach(btn => {
            btn.addEventListener('click', () => {
                setActive(btn);
                const tab = btn.getAttribute('data-tab');
                if (tab === 'preorder') {
                    gridPre.classList.remove('hidden');
                    gridAct.classList.add('hidden');
                } else {
                    gridPre.classList.add('hidden');
                    gridAct.classList.remove('hidden');
                }
                applyFilter();
            });
        });

        function applyFilter() {
            const val = (catFilter.value || '').toLowerCase();
            [gridPre, gridAct].forEach(grid => {
                if (!grid) return;
                grid.querySelectorAll('[data-category]').forEach(card => {
                    const cat = (card.getAttribute('data-category') || '').toLowerCase();
                    card.style.display = !val || cat === val ? '' : 'none';
                });
            });
        }

        if (catFilter) {
            catFilter.addEventListener('change', applyFilter);
        }
    </script>
@endsection
