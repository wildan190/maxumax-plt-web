@extends('layouts.public')

@section('title', $product->name . ' — Product Detail')

@section('meta_description', e(Str::limit(strip_tags($product->description ?: 'Precision-engineered performance apparel. Designed for the elite who demand absolute excellence on and off the field.'), 160)))
@section('meta_image', $product->image_path ? asset('storage/' . $product->image_path) : asset('assets/img/logo.png'))
@section('og_type', 'product')

@push('schema')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{ $product->name }}",
      "image": [
        "{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('assets/img/logo.png') }}"
        @foreach ($product->images as $img)
            , "{{ asset('storage/' . $img->path) }}"
        @endforeach
      ],
      "description": "{{ str_replace(["\r", "\n", '"'], ['', ' ', '\"'], $product->description ?: 'Precision-engineered performance apparel. Designed for the elite who demand absolute excellence on and off the field.') }}",
      "sku": "{{ $product->uuid }}",
      "brand": {
        "@type": "Brand",
        "name": "MAXUMAX"
      },
      @if($product->category)
      "category": "{{ $product->category }}",
      @endif
      "offers": {
        "@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "MYR",
        "price": "{{ $product->on_sale && $product->discounted_price !== null ? $product->discounted_price : $product->price }}",
        "itemCondition": "https://schema.org/NewCondition",
        "availability": "{{ $product->available_for_preorder ? 'https://schema.org/PreOrder' : ($product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock') }}",
        "seller": {
          "@type": "Organization",
          "name": "MAXUMAX"
        }
      }
    }
    </script>
@endpush

@section('content')
    <div x-data="{ showSizeModal: false }" class="px-3 md:px-6 py-4 md:py-8">
        <div style="max-width: 1280px; margin: 0 auto;">
            <!-- Product Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12 items-start">
                
                <!-- Left: Gallery -->
                <div class="space-y-3" id="prodGallery">
                    @php
                        $gallery = [];
                        if ($product->image_path) { $gallery[] = $product->image_path; }
                        foreach ($product->images as $img) { $gallery[] = $img->path; }
                    @endphp
                    
                    <div class="aspect-square bg-gradient-to-b from-[#F7F7F5] to-white rounded-xl border border-[#E8E8E3] relative overflow-hidden flex items-center justify-center p-3 md:p-6 group">
                        @if(count($gallery))
                            <img id="prodMain" src="{{ asset('storage/'.$gallery[0]) }}" alt="{{ $product->name }}" 
                                 class="max-w-full max-h-full object-contain drop-shadow-sm transition-transform duration-500 group-hover:scale-105" />
                            
                            <!-- Internal Controls -->
                            <button type="button" id="prevBtn" class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 bg-[#F7F7F5] border border-[#E8E8E3] rounded-full flex items-center justify-center text-[#111111] hover:bg-[#155EEF] hover:text-white hover:border-[#155EEF] transition-all opacity-0 group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            </button>
                            <button type="button" id="nextBtn" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 bg-[#F7F7F5] border border-[#E8E8E3] rounded-full flex items-center justify-center text-[#111111] hover:bg-[#155EEF] hover:text-white hover:border-[#155EEF] transition-all opacity-0 group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </button>
                        @else
                            <div class="text-[#E8E8E3] flex flex-col items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span class="text-[10px] uppercase font-black tracking-widest">No Image</span>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if(count($gallery) > 1)
                        <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                            @foreach($gallery as $i => $path)
                                <button type="button" data-index="{{ $i }}" 
                                        class="thumb-btn flex-shrink-0 w-12 md:w-16 aspect-square bg-[#F7F7F5] rounded-lg border border-[#E8E8E3] p-1 transition-all hover:border-[#155EEF]"
                                        style="opacity: {{ $i === 0 ? '1' : '0.4' }}; border-color: {{ $i === 0 ? 'rgb(21, 94, 239)' : 'rgb(232, 232, 227)' }}">
                                    <img src="{{ asset('storage/'.$path) }}" alt="thumb {{ $i+1 }}" class="w-full h-full object-contain">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right: Purchase Area -->
                <div class="flex flex-col">
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            @if($product->jersey_type)
                                <span class="text-[#666666] font-black uppercase tracking-[0.2em] text-[10px] md:text-[9px] px-2 py-1 border border-[#E8E8E3] rounded-full">{{ $product->jersey_type }}</span>
                            @endif
                        </div>

                        <h1 class="text-xl md:text-3xl font-black text-[#111111] italic uppercase tracking-tighter leading-tight mb-3">
                            {{ $product->name }}
                        </h1>

                        <p class="text-[#666666] text-xs md:text-sm leading-relaxed mb-4">
                            {{ $product->description ?: 'Precision-engineered performance apparel. Designed for the elite who demand absolute excellence on and off the field.' }}
                        </p>

                        <!-- Product Specifications -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                            @if($product->category)
                                <div class="space-y-0.5">
                                    <span class="text-[9px] md:text-[8px] font-black text-[#999999] uppercase tracking-[0.15em]">Category</span>
                                    <span class="block text-[11px] md:text-[10px] font-black text-[#111111] uppercase tracking-widest">{{ $product->category }}</span>
                                </div>
                            @endif
                            @if($product->collections || $product->collection)
                                <div class="space-y-0.5">
                                    <span class="text-[9px] md:text-[8px] font-black text-[#999999] uppercase tracking-[0.15em]">Collection</span>
                                    <span class="block text-[11px] md:text-[10px] font-black text-[#111111] uppercase tracking-widest">
                                        @if($product->collections)
                                            {{ implode(', ', $product->collections) }}
                                        @else
                                            {{ $product->collection }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                            @if($product->material)
                                <div class="space-y-0.5">
                                    <span class="text-[9px] md:text-[8px] font-black text-[#999999] uppercase tracking-[0.15em]">Material</span>
                                    <span class="block text-[11px] md:text-[10px] font-black text-[#111111] uppercase tracking-widest">{{ $product->material }}</span>
                                </div>
                            @endif
                            @if($product->gender)
                                <div class="space-y-0.5">
                                    <span class="text-[9px] md:text-[8px] font-black text-[#999999] uppercase tracking-[0.15em]">Gender</span>
                                    <span class="block text-[11px] md:text-[10px] font-black text-[#111111] uppercase tracking-widest">{{ $product->gender }}</span>
                                </div>
                            @endif
                            @if($product->fit)
                                <div class="space-y-0.5">
                                    <span class="text-[9px] md:text-[8px] font-black text-[#999999] uppercase tracking-[0.15em]">Fit</span>
                                    <span class="block text-[11px] md:text-[10px] font-black text-[#111111] uppercase tracking-widest">{{ $product->fit }}</span>
                                </div>
                            @endif
                            @if($product->color)
                                <div class="space-y-0.5">
                                    <span class="text-[9px] md:text-[8px] font-black text-[#999999] uppercase tracking-[0.15em]">Color</span>
                                    <span class="block text-[11px] md:text-[10px] font-black text-[#111111] uppercase tracking-widest">{{ $product->color }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Price Section -->
                        <div class="bg-[#F7F7F5] rounded-xl p-4 border border-[#E8E8E3] mb-6 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] md:text-[9px] font-black text-[#999999] uppercase tracking-widest block mb-1" id="currencyLabel">{{ $currency }}</span>
                                <div class="flex flex-col gap-0.5">
                                    @if($product->on_sale && $product->discounted_price !== null)
                                        <div class="flex items-center gap-2 mb-1">
                                            <span id="originalPriceDisplay" class="text-xs md:text-sm font-bold text-[#999999] line-through">
                                                {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                            </span>
                                            <span class="bg-rose-500 text-white text-[9px] font-black px-2 py-0.5 rounded-md uppercase tracking-widest">SALE</span>
                                        </div>
                                        <span id="priceDisplay" class="text-2xl md:text-3xl font-black text-rose-600 tracking-tight">
                                            {{ number_format($product->discounted_price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                        </span>
                                    @else
                                        <span id="priceDisplay" class="text-2xl md:text-3xl font-black text-[#111111] tracking-tight">
                                            {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div id="longSleevePrice" class="hidden text-right border-l border-[#E8E8E3] pl-4">
                                <span class="text-[9px] md:text-[8px] font-black text-amber-600 uppercase tracking-widest block mb-1">Long Sleeve</span>
                                <span id="longSleeveAdd" class="text-base md:text-lg font-black text-[#111111]">+ {{ number_format($currencyConfig['longSleeve'], $currency == 'IDR' ? 0 : 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Configuration Panel -->
                    <div class="space-y-4">
                        @if($product->available_for_preorder)
                            <div class="p-4 bg-[#155EEF]/10 border border-[#155EEF]/20 rounded-xl mb-4">
                                <h3 class="text-[#155EEF] font-black text-[10px] md:text-[9px] uppercase tracking-widest mb-1.5">Pre-order</h3>
                                <p class="text-[#155EEF]/60 text-xs mb-3">Reserve yours now for guaranteed priority deployment.</p>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('preorder.create', $product) }}" class="flex-grow bg-[#155EEF] text-white py-3 rounded-xl font-black uppercase tracking-[0.15em] flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-95 transition-all shadow-lg text-xs md:text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                        Reserve Now
                                    </a>
                                    @if($product->size_guide)
                                        <button type="button" @click="showSizeModal = true" class="bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] px-3 py-3 rounded-xl font-black uppercase tracking-[0.15em] flex items-center justify-center gap-2 hover:bg-white hover:scale-[1.01] active:scale-95 transition-all shadow-sm text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                            Size Guide
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @elseif($product->is_active)
                            <form id="activeAddToCartForm" method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <!-- Alert Section -->
                                @if($errors->any() || session('success'))
                                    <div class="animate-fade-in">
                                        @if($errors->any())
                                            <div class="p-2.5 bg-red-500/10 border border-red-500/20 text-red-600 rounded-xl text-xs font-bold flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                                {{ $errors->first() }}
                                            </div>
                                        @endif
                                        @if(session('success'))
                                            <div class="p-2.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 rounded-xl text-xs font-bold flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Size Selection -->
                                <div class="space-y-3">
                                    <div class="flex justify-between items-end">
                                        <label class="text-[10px] md:text-[9px] font-black text-[#999999] uppercase tracking-widest">SIZE</label>
                                        @if($product->size_guide)
                                            <button type="button" @click="showSizeModal = true" class="text-[10px] md:text-[9px] font-black text-[#155EEF] hover:text-[#0d46b3] uppercase tracking-widest transition-colors flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-2.5 h-2.5"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                                Guide
                                            </button>
                                        @endif
                                    </div>
                                    
                                    @if($product->hasVariants())
                                        <input type="hidden" name="product_variant_id" id="selectedVariantId" required />
                                        <input type="hidden" name="size" id="selectedSize" value="" />
                                        <div class="grid grid-cols-4 md:grid-cols-5 gap-2">
                                            @foreach($product->variants as $variant)
                                                <button type="button" 
                                                    class="size-btn-product group relative py-2.5 rounded-lg border font-black text-[11px] uppercase tracking-widest transition-all
                                                    {{ $variant->hasStock() ? 'border-[#E8E8E3] text-[#666666] hover:border-[#155EEF] hover:text-[#155EEF]' : 'border-red-500/20 text-red-400/80 cursor-not-allowed opacity-60' }}"
                                                    data-variant-id="{{ $variant->id }}" 
                                                    data-variant-name="{{ $variant->name }}"
                                                    {{ $variant->hasStock() ? '' : 'disabled' }}>
                                                    {{ $variant->name }}
                                                    @if($variant->hasStock())
                                                        <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-emerald-500/20 text-emerald-600 rounded-full flex items-center justify-center text-[6px] font-black border border-emerald-500/30 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            {{ $variant->stock }}
                                                        </span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-[#999999]" style="width:12px;height:12px"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                            <select id="sizeSelect" name="size" required class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl pl-10 pr-4 py-3 text-[#111111] font-black uppercase tracking-widest text-sm focus:outline-none focus:border-[#155EEF] transition-all appearance-none">
                                                <option value="" class="bg-white text-[#111111]">Select Size</option>
                                                @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $s)
                                                    <option value="{{ $s }}" class="bg-white text-[#111111]">{{ $s }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>

                                <!-- Quantity -->
                                <div class="space-y-2">
                                    <label class="text-[10px] md:text-[9px] font-black text-[#999999] uppercase tracking-widest">Quantity</label>
                                    <div class="relative">
                                        <input type="number" id="qtyInput" name="quantity" value="1" min="1" required 
                                               class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-4 py-3 text-[#111111] font-black text-sm focus:outline-none focus:border-[#155EEF] transition-all">
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-[#155EEF] text-white py-4 rounded-xl font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:bg-[#0d46b3] hover:scale-[1.01] active:scale-95 transition-all shadow-lg text-xs md:text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 3 0 0 1-8 0"></path></svg>
                                    Add To Cart
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Size Guide Modal -->
        @if($product->size_guide)
            <div x-show="showSizeModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
                 x-cloak>
                <div class="bg-white border border-[#E8E8E3] rounded-2xl max-w-2xl w-full overflow-hidden shadow-xl relative" @click.away="showSizeModal = false">
                    <div class="p-6 border-b border-[#E8E8E3] flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-[#111111] font-black text-sm uppercase tracking-widest">Size Guide</h3>
                            <p class="text-[10px] text-[#999999] mt-1">Compare body measurements.</p>
                        </div>
                        <button type="button" @click="showSizeModal = false" class="w-8 h-8 rounded-full bg-[#F7F7F5] hover:bg-[#E8E8E3] flex items-center justify-center text-[#666666] hover:text-[#111111] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="p-6 flex items-center justify-center bg-[#F7F7F5]">
                        <img src="{{ asset('storage/'.$product->size_guide) }}" alt="Size Chart" class="max-w-full max-h-[60vh] object-contain rounded-xl border border-[#E8E8E3] shadow-lg">
                    </div>
                    <div class="p-5 border-t border-[#E8E8E3] bg-white flex justify-end">
                        <button type="button" @click="showSizeModal = false" class="px-5 py-2.5 bg-[#155EEF] text-white font-black text-xs uppercase tracking-widest rounded-lg hover:bg-[#0d46b3] transition-all">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <script>
            // Gallery Functionality
            (function(){
                const galleryEl = document.getElementById('prodGallery');
                if (!galleryEl) return;
                const main = document.getElementById('prodMain');
                const thumbs = Array.from(galleryEl.querySelectorAll('.thumb-btn'));
                const prev = document.getElementById('prevBtn');
                const next = document.getElementById('nextBtn');
                let idx = 0;

                function setIndex(i){
                    if (!thumbs.length) return;
                    idx = i;
                    const src = thumbs[idx].querySelector('img').getAttribute('src');
                    main.setAttribute('src', src);
                    thumbs.forEach((t, ti) => {
                        t.style.opacity = ti === idx ? '1' : '0.4';
                        t.style.borderColor = ti === idx ? '#155EEF' : '#E8E8E3';
                    });
                }

                thumbs.forEach(t => {
                    t.addEventListener('click', () => {
                        const i = parseInt(t.getAttribute('data-index'), 10) || 0;
                        setIndex(i);
                    });
                });

                prev?.addEventListener('click', (e) => {
                    e.preventDefault();
                    setIndex((idx - 1 + thumbs.length) % thumbs.length);
                });

                next?.addEventListener('click', (e) => {
                    e.preventDefault();
                    setIndex((idx + 1) % thumbs.length);
                });
            })();

            // Price Logic
            (function(){
                const currencies = {
                    MYR: { symbol: 'RM', rate: 1, longSleeve: {{ config('currencies.MYR.longSleeve', 10) }} },
                    BND: { symbol: '$', rate: 1.05, longSleeve: {{ config('currencies.BND.longSleeve', 3) }} },
                    IDR: { symbol: 'Rp', rate: {{ $currencyConfig['rate'] }}, longSleeve: {{ $currencyConfig['longSleeve'] }} }
                };
                
                const currentCurrency = '{{ $currency }}';
                const basePrice = parseFloat('{{ ($product->on_sale && $product->discounted_price !== null) ? $product->discounted_price : $product->price }}');
                const longSleeveCheckbox = document.getElementById('longSleeveCheckbox');
                const priceDisplay = document.getElementById('priceDisplay');
                const longSleevePriceRow = document.getElementById('longSleevePrice');

                function updatePrice() {
                    const config = currencies[currentCurrency];
                    let total = basePrice * config.rate;
                    if (longSleeveCheckbox && longSleeveCheckbox.checked) {
                        total += config.longSleeve;
                        longSleevePriceRow.classList.remove('hidden');
                    } else if(longSleevePriceRow) {
                        longSleevePriceRow.classList.add('hidden');
                    }

                    if (currentCurrency === 'IDR') {
                        priceDisplay.textContent = Math.round(total).toLocaleString('id-ID');
                    } else {
                        priceDisplay.textContent = total.toFixed(2);
                    }
                }

                if (longSleeveCheckbox) {
                    longSleeveCheckbox.addEventListener('change', updatePrice);
                }

                // Sync with global currency selector if it changes
                document.getElementById('currencySelector')?.addEventListener('change', function() {
                    window.location.reload(); // Quickest way to sync complex rates
                });
            })();

            // Size Buttons
            (function(){
                const sizeBtns = document.querySelectorAll('.size-btn-product');
                const variantInput = document.getElementById('selectedVariantId');
                const sizeStringInput = document.getElementById('selectedSize');

                function setSelected(btn) {
                    sizeBtns.forEach(b => {
                        if (b.disabled) return;
                        b.classList.remove('bg-white', 'text-black', 'border-white');
                        b.classList.add('border-white/20', 'text-white/70');
                    });
                    if (btn && !btn.disabled) {
                        btn.classList.remove('border-white/20', 'text-white/70');
                        btn.classList.add('bg-white', 'text-black', 'border-white');
                    }
                }

                sizeBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (this.disabled) return;
                        setSelected(this);
                        if(variantInput) variantInput.value = this.getAttribute('data-variant-id');
                        if(sizeStringInput) sizeStringInput.value = this.getAttribute('data-variant-name');
                    });
                });
            })();

            // Form validation
            (function(){
                const form = document.getElementById('activeAddToCartForm');
                if (form) {
                    form.addEventListener('submit', function(e){
                        const sizeSel = document.getElementById('sizeSelect');
                        const qty = document.getElementById('qtyInput');
                        
                        // Validate size
                        if (sizeSel && (!sizeSel.value || sizeSel.value === '')) {
                            e.preventDefault();
                            sizeSel.style.borderColor = '#ef4444';
                            sizeSel.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.2)';
                            sizeSel.focus();
                            alert('Silakan pilih ukuran terlebih dahulu');
                            return false;
                        }
                        
                        // Reset border if valid
                        if (sizeSel) {
                            sizeSel.style.borderColor = '';
                            sizeSel.style.boxShadow = '';
                        }
                        
                        // Ensure quantity is valid
                        if (qty) {
                            const qtyValue = parseInt(qty.value || '1', 10);
                            if (qtyValue < 1) {
                                qty.value = 1;
                            }
                        }
                    });
                }
            })();
        </script>
    </div>
@endsection
