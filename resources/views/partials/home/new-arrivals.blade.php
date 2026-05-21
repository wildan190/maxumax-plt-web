<!-- 8. New Arrivals (Only show if products exist) -->
@if(isset($products) && $products->isNotEmpty())
<section class="bg-black py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight mb-2">New Arrivals</h2>
                <p class="text-slate-400 font-medium">Explore our latest ready stock drops.</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'new-arrivals']) }}" class="inline-flex items-center gap-2 text-white font-bold uppercase tracking-widest text-xs hover:text-slate-400 transition-colors">
                View All <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
            @foreach ($products as $product)
                <div class="flex flex-col bg-[#111111] rounded-2xl overflow-hidden border border-white/5 hover:border-white/10 transition-all duration-300 group relative">
                    <!-- Product Image -->
                    <div class="aspect-square md:aspect-[4/5] relative flex items-center justify-center p-3 md:p-8 bg-gradient-to-b from-[#1a1a1a] to-[#111111]">
                        @if ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                onerror="this.onerror=null;this.src='{{ asset('assets/img/logo.png') }}';"
                                class="max-w-[85%] max-h-[85%] md:max-w-full md:max-h-full object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.8)] group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="text-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 md:w-16 md:h-16"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            </div>
                        @endif
                        @if($product->on_sale && $product->discounted_price !== null)
                            <span class="absolute top-3 right-3 md:top-6 md:right-6 bg-rose-500 text-white text-[7px] md:text-[9px] font-black px-1.5 md:px-3 py-0.5 md:py-1 rounded-full uppercase tracking-widest shadow-lg animate-pulse">SALE</span>
                        @endif
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
                            @if($product->available_for_preorder)
                                <span class="px-1.5 md:px-4 py-0.5 md:py-1.5 rounded-full border border-yellow-500/30 text-[7px] md:text-[9px] font-black text-yellow-500 uppercase tracking-widest">
                                    Preorder
                                </span>
                            @else
                                <span class="px-1.5 md:px-4 py-0.5 md:py-1.5 rounded-full border border-green-500/30 text-[7px] md:text-[9px] font-black text-green-500 uppercase tracking-widest">
                                    Ready Stock
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
                            <a href="{{ $product->available_for_preorder ? route('preorder.create', $product) : route('product.show', $product) }}" 
                               class="w-7 h-7 md:w-10 md:h-10 bg-white rounded-full flex items-center justify-center text-black hover:bg-slate-200 transition-all hover:scale-110 active:scale-95 shadow-xl after:absolute after:inset-0 after:z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 md:w-[18px] md:h-[18px]"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
