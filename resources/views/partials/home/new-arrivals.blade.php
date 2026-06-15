<!-- 8. Product Highlights (Only show if products exist) -->
@if(isset($products) && $products->isNotEmpty())
<section class="bg-white py-20 md:py-28 px-6 border-b border-[#E8E8E3]">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-[#111111] uppercase tracking-tight mb-3">Product Highlights</h2>
                <p class="text-[#666666] font-medium">Explore our latest ready stock drops and performance gear.</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'new-arrivals']) }}" class="inline-flex items-center gap-2 text-[#155EEF] font-black uppercase tracking-widest text-xs hover:text-[#0d46b3] transition-colors">
                View All <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
            @foreach ($products as $product)
                <div class="group cursor-pointer" onclick="window.location='{{ $product->available_for_preorder ? route('preorder.create', $product) : route('product.show', $product) }}'">
                    <!-- Product Card -->
                    <div class="flex flex-col bg-[#F7F7F5] rounded-lg overflow-hidden border border-[#E8E8E3] hover:border-[#999999] transition-all duration-300 h-full">
                        <!-- Product Image -->
                        <div class="aspect-square relative flex items-center justify-center p-2 md:p-3 bg-white">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/img/logo.png') }}';"
                                    class="max-w-[90%] max-h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="text-[#E8E8E3]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 md:w-8 md:h-8"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                </div>
                            @endif
                            @if($product->on_sale && $product->discounted_price !== null)
                                <span class="absolute top-1 right-1 md:top-2 md:right-2 bg-rose-500 text-white text-[6px] md:text-[8px] font-black px-1.5 md:px-2 py-0.5 md:py-1 rounded-md uppercase tracking-widest shadow-sm">SALE</span>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-2 md:p-3 flex flex-col flex-grow bg-white border-t border-[#E8E8E3]">
                            <h3 class="text-[#111111] font-black text-[8px] md:text-[9px] uppercase tracking-widest text-center mb-1.5 md:mb-2 leading-tight min-h-[1rem] md:min-h-[1.5rem] flex items-center justify-center line-clamp-2">{{ $product->name }}</h3>
                            
                            <!-- Badges -->
                            <div class="flex flex-wrap justify-center gap-0.5 mb-2 md:mb-3">
                                @if($product->jersey_type)
                                    <span class="px-1 md:px-2 py-0.5 md:py-0.5 rounded-md border border-[#E8E8E3] text-[6px] md:text-[7px] font-bold text-[#666666] uppercase tracking-widest">
                                        {{ $product->jersey_type }}
                                    </span>
                                @endif
                                @if($product->available_for_preorder)
                                    <span class="px-1 md:px-2 py-0.5 md:py-0.5 rounded-md border border-amber-300 bg-amber-50 text-[6px] md:text-[7px] font-bold text-amber-700 uppercase tracking-widest">
                                        Preorder
                                    </span>
                                @endif
                            </div>

                            <!-- Price and Action -->
                            <div class="mt-auto pt-2 md:pt-3 border-t border-[#E8E8E3] flex items-center justify-between gap-1">
                                <div class="flex flex-col">
                                    <span class="text-[6px] md:text-[8px] font-bold text-[#999999] uppercase tracking-widest mb-0.5">{{ $currency }}</span>
                                    @if($product->on_sale && $product->discounted_price !== null)
                                        <div class="flex flex-col gap-0.5">
                                            <span class="text-[8px] md:text-[9px] font-bold text-[#999999] line-through">
                                                {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                            </span>
                                            <span class="text-[10px] md:text-xs font-black text-rose-500 leading-none">
                                                {{ number_format($product->discounted_price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-[10px] md:text-xs font-black text-[#111111] leading-none">
                                            {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="w-5 h-5 md:w-7 md:h-7 bg-[#155EEF] rounded-lg flex items-center justify-center text-white hover:bg-[#0d46b3] transition-all hover:scale-110 active:scale-95 shadow-sm flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-2.5 h-2.5 md:w-3.5 md:h-3.5"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
