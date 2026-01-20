@extends('layouts.public')

@section('title', 'Products - Maxumax')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 text-center py-24 px-4">
        <div class="relative z-10 max-w-6xl mx-auto">
            <h1 class="text-white font-black text-4xl md:text-6xl mb-4">Available Products</h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto mb-8">
                Browse our collection of available jerseys. Ready to ship now!
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                <div
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-2 rounded-full font-semibold text-sm">
                    <span class="inline-flex w-5 h-5 rounded-full bg-emerald-500 items-center justify-center"><i
                            data-feather="check" style="width:12px;height:12px;"></i></span>
                    Ready to Ship
                </div>
                <div
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-2 rounded-full font-semibold text-sm">
                    <span class="inline-flex w-5 h-5 rounded-full bg-emerald-500 items-center justify-center"><i
                            data-feather="check" style="width:12px;height:12px;"></i></span>
                    Multiple Sizes
                </div>
                <div
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-2 rounded-full font-semibold text-sm">
                    <span class="inline-flex w-5 h-5 rounded-full bg-emerald-500 items-center justify-center"><i
                            data-feather="check" style="width:12px;height:12px;"></i></span>
                    Add to Cart
                </div>
            </div>
        </div>
    </section>

    <!-- Product Showcase Section -->
    <section class="bg-slate-100 py-16 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-2">Shop Now</h2>
                <p class="text-slate-500 text-base">Filter by category to find your perfect jersey</p>
            </div>

            @php
                $categories = isset($products) ? $products->pluck('jersey_type')->filter()->unique()->sort()->values() : collect();
            @endphp

            <div class="w-full flex justify-center mb-6">
                <div class="inline-flex flex-wrap gap-2 bg-slate-100 p-2 rounded-xl">
                    <button
                        class="category-btn px-4 py-2 rounded-lg font-semibold text-sm transition-all bg-black text-white"
                        data-category="">
                        All Categories
                    </button>
                    @foreach($categories as $cat)
                        <button
                            class="category-btn px-4 py-2 rounded-lg font-semibold text-sm transition-all bg-white text-slate-700 hover:bg-slate-200"
                            data-category="{{ strtolower($cat) }}">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div id="products-grid" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @if(isset($products) && $products->count())
                    @foreach($products as $product)
                        <a href="{{ route('product.show', $product) }}"
                            class="group bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition"
                            data-category="{{ strtolower($product->jersey_type ?? '') }}">
                            <div class="relative aspect-[4/3] bg-slate-50 flex items-center justify-center">
                                @if($product->image_path)
                                    <img class="w-full h-full object-contain p-2" src="{{ asset('storage/' . $product->image_path) }}"
                                        alt="{{ $product->name }}" />
                                @else
                                    <span class="text-4xl"><i data-feather="image"></i></span>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $product->name }}</h3>
                                @if($product->jersey_type)
                                    <span
                                        class="inline-block bg-slate-100 text-slate-500 text-xs font-semibold px-2 py-1 rounded mb-2">{{ $product->jersey_type }}</span>
                                @endif
                                <p class="text-slate-600 text-sm mb-4">
                                    {{ $product->description ?: 'Premium quality jersey with breathable fabric.' }}
                                </p>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                                    <div class="text-xl font-extrabold text-black">
                                        <span class="text-sm font-medium text-slate-500">
                                            {{ $currencyConfig['rate'] == 5200 || $currency == 'IDR' ? 'Rp' : ($currency == 'BND' ? '$' : ($currency == 'SGD' ? 'S$' : 'RM')) }}
                                        </span>
                                        {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                    </div>
                                    <div
                                        class="inline-flex items-center rounded-full bg-black text-white px-4 py-2 text-sm font-semibold group-hover:bg-slate-900 transition">
                                        View Details →</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="col-span-full text-center p-10 bg-white rounded-xl text-slate-500">
                        <div class="text-5xl mb-2 opacity-50">📦</div>
                        <p>No products available at the moment. Check back soon!</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <script>
        const productsGrid = document.getElementById('products-grid');
        const categoryBtns = document.querySelectorAll('.category-btn');

        function applyFilter(selectedCategory) {
            const val = (selectedCategory || '').toLowerCase();
            if (!productsGrid) return;

            // Update product visibility
            productsGrid.querySelectorAll('[data-category]').forEach(card => {
                const cat = (card.getAttribute('data-category') || '').toLowerCase();
                card.style.display = !val || cat === val ? '' : 'none';
            });

            // Update button styles
            categoryBtns.forEach(btn => {
                const btnCat = btn.getAttribute('data-category');
                if (btnCat === selectedCategory) {
                    btn.classList.remove('bg-white', 'text-slate-700', 'hover:bg-slate-200');
                    btn.classList.add('bg-black', 'text-white');
                } else {
                    btn.classList.remove('bg-black', 'text-white');
                    btn.classList.add('bg-white', 'text-slate-700', 'hover:bg-slate-200');
                }
            });
        }

        categoryBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const category = this.getAttribute('data-category');
                applyFilter(category);
            });
        });
    </script>
@endsection