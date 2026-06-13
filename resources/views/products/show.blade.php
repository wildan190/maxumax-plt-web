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

@push('styles')
    <style>
        .prod-container { max-width: 1000px; margin: 0 auto; padding: 2rem 1rem; }
        .currency-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }
        .currency-select {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .currency-select label {
            font-weight: 600;
            color: #0f172a;
            font-size: 0.875rem;
        }
        .currency-select select {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            background: #fff;
            font-weight: 600;
            cursor: pointer;
        }
        .prod-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        @media (max-width: 768px) {
            .prod-grid { grid-template-columns: 1fr; }
            .prod-image { order: -1; }
        }
        .prod-image { background:#fff; border:1px solid #e2e8f0; border-radius:0.75rem; padding:0.75rem; display:flex; align-items:center; justify-content:center; min-height: 320px; }
        .prod-title { font-size:2rem; font-weight:800; color:#0f172a; margin:0 0 0.5rem; }
        .prod-type { display:inline-block; background:#f1f5f9; color:#64748b; font-weight:600; font-size:0.75rem; padding:0.25rem 0.5rem; border-radius:0.375rem; }
        .prod-desc { color:#64748b; margin:0.75rem 0; }
        .prod-price { font-size:1.5rem; font-weight:800; color:#000; }
        .btn { display:inline-flex; align-items:center; gap:0.5rem; background:#000; color:#fff; padding:0.5rem 0.75rem; border-radius:0.5rem; font-weight:600; }
        .card { background:#fff; border:1px solid #e2e8f0; border-radius:0.75rem; padding:1rem; }
    </style>
@endpush

@section('content')
    <div class="bg-white min-h-screen pt-24 pb-32 px-6" x-data="{ showSizeModal: false }">
        <div style="max-width: 1280px; margin: 0 auto;">
            <!-- Product Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                
                <!-- Left: Cinematic Gallery -->
                <div class="space-y-6" id="prodGallery">
                    @php
                        $gallery = [];
                        if ($product->image_path) { $gallery[] = $product->image_path; }
                        foreach ($product->images as $img) { $gallery[] = $img->path; }
                    @endphp
                    
                    <div class="aspect-[4/5] bg-gradient-to-b from-[#F7F7F5] to-white rounded-[2.5rem] border border-[#E8E8E3] relative overflow-hidden flex items-center justify-center p-12 group">
                        @if(count($gallery))
                            <img id="prodMain" src="{{ asset('storage/'.$gallery[0]) }}" alt="{{ $product->name }}" 
                                 class="max-w-full max-h-full object-contain drop-shadow-md transition-transform duration-700 group-hover:scale-105" />
                            
                            <!-- Internal Controls -->
                            <button type="button" id="prevBtn" class="absolute left-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-[#F7F7F5] border border-[#E8E8E3] rounded-full flex items-center justify-center text-[#111111] hover:bg-[#155EEF] hover:text-white hover:border-[#155EEF] transition-all opacity-0 group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            </button>
                            <button type="button" id="nextBtn" class="absolute right-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-[#F7F7F5] border border-[#E8E8E3] rounded-full flex items-center justify-center text-[#111111] hover:bg-[#155EEF] hover:text-white hover:border-[#155EEF] transition-all opacity-0 group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </button>
                        @else
                            <div class="text-[#E8E8E3] flex flex-col items-center gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:80px;height:80px"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span class="text-[10px] uppercase font-black tracking-widest">No Image Asset</span>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if(count($gallery) > 1)
                        <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                            @foreach($gallery as $i => $path)
                                <button type="button" data-index="{{ $i }}" 
                                        class="thumb-btn flex-shrink-0 w-24 aspect-square bg-[#F7F7F5] rounded-2xl border border-[#E8E8E3] p-4 transition-all hover:border-[#155EEF]"
                                        style="opacity: {{ $i === 0 ? '1' : '0.4' }}; border-color: {{ $i === 0 ? 'rgb(21, 94, 239)' : 'rgb(232, 232, 227)' }}">
                                    <img src="{{ asset('storage/'.$path) }}" alt="thumb {{ $i+1 }}" class="w-full h-full object-contain">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right: High-Impact Purchase Area -->
                <div class="flex flex-col">
                    <div class="mb-10">
                        <div class="flex items-center gap-4 mb-6">
                            @if($product->jersey_type)
                                <span class="text-[#666666] font-black uppercase tracking-[0.3em] text-[10px] px-4 py-1.5 border border-[#E8E8E3] rounded-full">{{ $product->jersey_type }}</span>
                            @endif
                        </div>

                        <h1 class="text-5xl md:text-7xl font-black text-[#111111] italic uppercase tracking-tighter leading-[0.9] mb-8">
                            {{ $product->name }}
                        </h1>

                        <p class="text-[#666666] text-lg leading-relaxed mb-10 max-w-xl">
                            {{ $product->description ?: 'Precision-engineered performance apparel. Designed for the elite who demand absolute excellence on and off the field.' }}
                        </p>

                        <!-- Product Specifications -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-12">
                            @if($product->category)
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black text-[#999999] uppercase tracking-[0.2em]">Category</span>
                                    <span class="block text-xs font-black text-[#111111] uppercase tracking-widest">{{ $product->category }}</span>
                                </div>
                            @endif
                            @if($product->collections || $product->collection)
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black text-[#999999] uppercase tracking-[0.2em]">Collection</span>
                                    <span class="block text-xs font-black text-[#111111] uppercase tracking-widest">
                                        @if($product->collections)
                                            {{ implode(', ', $product->collections) }}
                                        @else
                                            {{ $product->collection }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                            @if($product->material)
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black text-[#999999] uppercase tracking-[0.2em]">Material</span>
                                    <span class="block text-xs font-black text-[#111111] uppercase tracking-widest">{{ $product->material }}</span>
                                </div>
                            @endif
                            @if($product->gender)
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black text-[#999999] uppercase tracking-[0.2em]">Gender</span>
                                    <span class="block text-xs font-black text-[#111111] uppercase tracking-widest">{{ $product->gender }}</span>
                                </div>
                            @endif
                            @if($product->fit)
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black text-[#999999] uppercase tracking-[0.2em]">Fit</span>
                                    <span class="block text-xs font-black text-[#111111] uppercase tracking-widest">{{ $product->fit }}</span>
                                </div>
                            @endif
                            @if($product->color)
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black text-[#999999] uppercase tracking-[0.2em]">Color</span>
                                    <span class="block text-xs font-black text-[#111111] uppercase tracking-widest">{{ $product->color }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Price Section -->
                        <div class="bg-[#F7F7F5] rounded-3xl p-8 border border-[#E8E8E3] mb-12 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-black text-[#999999] uppercase tracking-widest block mb-2" id="currencyLabel">{{ $currency }}</span>
                                <div class="flex flex-col gap-1">
                                    @if($product->on_sale && $product->discounted_price !== null)
                                        <div class="flex items-center gap-3 mb-1">
                                            <span id="originalPriceDisplay" class="text-lg font-bold text-[#999999] line-through">
                                                {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                            </span>
                                            <span class="bg-rose-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest animate-pulse">SALE</span>
                                        </div>
                                        <span id="priceDisplay" class="text-5xl font-black text-rose-600 tracking-tight">
                                            {{ number_format($product->discounted_price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                        </span>
                                    @else
                                        <span id="priceDisplay" class="text-5xl font-black text-[#111111] tracking-tight">
                                            {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div id="longSleevePrice" class="hidden text-right border-l border-[#E8E8E3] pl-8">
                                <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest block mb-2">Extended Sleeve Upgrade</span>
                                <span id="longSleeveAdd" class="text-xl font-black text-[#111111]">+ {{ number_format($currencyConfig['longSleeve'], $currency == 'IDR' ? 0 : 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Configuration Panel -->
                    <div class="space-y-10">
                        @if($product->available_for_preorder)
                            <div class="p-8 bg-[#155EEF]/10 border border-[#155EEF]/20 rounded-3xl mb-8">
                                <h3 class="text-[#155EEF] font-black text-xs uppercase tracking-widest mb-4">Limited Pre-order Event</h3>
                                <p class="text-[#155EEF]/60 text-sm mb-6">This item is currently in production. Reserve yours now for guaranteed priority deployment.</p>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('preorder.create', $product) }}" class="flex-grow bg-[#155EEF] text-white py-5 rounded-2xl font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:scale-[1.02] active:scale-95 transition-all shadow-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                        Reserve Now
                                    </a>
                                    @if($product->size_guide)
                                        <button type="button" @click="showSizeModal = true" class="bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] px-6 py-5 rounded-2xl font-black uppercase tracking-[0.2em] flex items-center justify-center gap-2 hover:bg-white hover:scale-[1.02] active:scale-95 transition-all shadow-xl">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                            Size Guide
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @elseif($product->is_active)
                            <form id="activeAddToCartForm" method="POST" action="{{ route('cart.add') }}" class="space-y-8">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <!-- Alert Section -->
                                @if($errors->any() || session('success'))
                                    <div class="animate-fade-in">
                                        @if($errors->any())
                                            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-600 rounded-2xl text-xs font-bold flex items-center gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                                {{ $errors->first() }}
                                            </div>
                                        @endif
                                        @if(session('success'))
                                            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 rounded-2xl text-xs font-bold flex items-center gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Size Selection -->
                                <div class="space-y-5">
                                    <div class="flex justify-between items-end">
                                        <label class="text-[10px] font-black text-[#999999] uppercase tracking-widest">SIZE</label>
                                        @if($product->size_guide)
                                            <button type="button" @click="showSizeModal = true" class="text-[10px] font-black text-[#155EEF] hover:text-[#0d46b3] uppercase tracking-widest transition-colors flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                                Size Guide
                                            </button>
                                        @endif
                                    </div>
                                    
                                    @if($product->hasVariants())
                                        <input type="hidden" name="product_variant_id" id="selectedVariantId" required />
                                        <input type="hidden" name="size" id="selectedSize" value="" />
                                        <div class="grid grid-cols-4 md:grid-cols-5 gap-3">
                                            @foreach($product->variants as $variant)
                                                <button type="button" 
                                                    class="size-btn-product group relative py-4 rounded-xl border font-black text-xs uppercase tracking-widest transition-all
                                                    {{ $variant->hasStock() ? 'border-[#E8E8E3] text-[#666666] hover:border-[#155EEF] hover:text-[#155EEF]' : 'border-red-500/20 text-red-400/80 cursor-not-allowed opacity-60' }}"
                                                    data-variant-id="{{ $variant->id }}" 
                                                    data-variant-name="{{ $variant->name }}"
                                                    {{ $variant->hasStock() ? '' : 'disabled' }}>
                                                    {{ $variant->name }}
                                                    @if($variant->hasStock())
                                                        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-emerald-500/20 text-emerald-600 rounded-full flex items-center justify-center text-[7px] font-black border border-emerald-500/30 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            {{ $variant->stock }}
                                                        </span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-6 top-1/2 -translate-y-1/2 text-[#999999]" style="width:18px;height:18px"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                            <select id="sizeSelect" name="size" required class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-2xl pl-16 pr-8 py-5 text-[#111111] font-black uppercase tracking-widest focus:outline-none focus:border-[#155EEF] transition-all appearance-none">
                                                <option value="" class="bg-white text-[#111111]">Select Frame</option>
                                                @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $s)
                                                    <option value="{{ $s }}" class="bg-white text-[#111111]">{{ $s }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>

                                <!-- Quantity -->
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-[#999999] uppercase tracking-widest">Quantity</label>
                                    <div class="relative">
                                        <input type="number" id="qtyInput" name="quantity" value="1" min="1" required 
                                               class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-2xl px-6 py-5 text-[#111111] font-black focus:outline-none focus:border-[#155EEF] transition-all">
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-[#155EEF] text-white py-6 rounded-2xl font-black uppercase tracking-[0.3em] flex items-center justify-center gap-4 hover:bg-[#0d46b3] hover:scale-[1.02] active:scale-95 transition-all shadow-2xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                    Add To Cart
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>


    <!-- Size Guide Modal -->
    @if($product->size_guide)
        <div x-show="showSizeModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
             x-cloak>
            <div class="bg-white border border-[#E8E8E3] rounded-[2rem] max-w-2xl w-full overflow-hidden shadow-2xl relative" @click.away="showSizeModal = false">
                <div class="p-8 border-b border-[#E8E8E3] flex justify-between items-center bg-white">
                    <div>
                        <h3 class="text-[#111111] font-black text-lg uppercase tracking-widest">Size Reference Guide</h3>
                        <p class="text-xs text-[#999999] mt-1">Compare body measurements to choose your ideal fit.</p>
                    </div>
                    <button type="button" @click="showSizeModal = false" class="w-10 h-10 rounded-full bg-[#F7F7F5] hover:bg-[#E8E8E3] flex items-center justify-center text-[#666666] hover:text-[#111111] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="p-8 flex items-center justify-center bg-[#F7F7F5]">
                    <img src="{{ asset('storage/' . $product->size_guide) }}" alt="Size Chart" class="max-w-full max-h-[70vh] object-contain rounded-2xl border border-[#E8E8E3] shadow-2xl" />
                </div>
                <div class="p-6 border-t border-[#E8E8E3] bg-white flex justify-end">
                    <button type="button" @click="showSizeModal = false" class="px-6 py-3 bg-[#155EEF] text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-[#0d46b3] transition-all">
                        Close Guide
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

        // Feedbacks Logic
        (function(){
            const ratingStars = document.getElementById('ratingStars');
            const ratingInput = document.getElementById('ratingInput');
            if (!ratingStars || !ratingInput) return;
            
            const stars = Array.from(ratingStars.querySelectorAll('.star-btn'));
            let selectedRating = 0;
            
            function updateStars(rating) {
                stars.forEach((star, index) => {
                    const starRating = index + 1;
                    if (starRating <= rating) {
                        star.classList.add('text-yellow-500');
                        star.classList.remove('text-white/30');
                    } else {
                        star.classList.add('text-white/30');
                        star.classList.remove('text-yellow-500');
                    }
                });
                ratingInput.value = rating;
            }
            
            stars.forEach((star, index) => {
                const starRating = index + 1;
                star.addEventListener('click', () => {
                    selectedRating = starRating;
                    updateStars(selectedRating);
                });
                star.addEventListener('mouseenter', () => updateStars(starRating));
            });
            
            ratingStars.addEventListener('mouseleave', () => updateStars(selectedRating));

            // Image Preview
            const imageInput = document.getElementById('feedbackImages');
            const imagePreview = document.getElementById('imagePreview');
            if (imageInput && imagePreview) {
                imageInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    if (files.length > 2) {
                        alert('Max 2 images allowed.');
                        e.target.value = '';
                        imagePreview.innerHTML = '';
                        return;
                    }
                    imagePreview.innerHTML = '';
                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            const div = document.createElement('div');
                            div.className = 'w-20 h-20 rounded-xl overflow-hidden border border-white/10 relative mt-4';
                            div.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">`;
                            imagePreview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
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
    </script>
    <script>
        (function(){
            const form = document.getElementById('activeAddToCartForm');
            if (form) {
                form.addEventListener('submit', function(e){
                    const sizeSel = document.getElementById('sizeSelect');
                    const qty = document.getElementById('qtyInput');
                    const longSleeveCheckbox = document.getElementById('longSleeveCheckbox');
                    
                    // Validate size
                    if (sizeSel && (!sizeSel.value || sizeSel.value === '')) {
                        e.preventDefault();
                        sizeSel.style.borderColor = '#ef4444';
                        sizeSel.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.2)';
                        sizeSel.focus();
                        const panel = document.getElementById('configPanel');
                        panel && panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
                    
                    // Form will submit normally with all fields (size, quantity, long_sleeve are already in form)
                });
            }
        })();
        (function(){
            const galleryEl = document.getElementById('prodGallery');
            if (!galleryEl) return;
            const main = document.getElementById('prodMain');
            const thumbs = Array.from(galleryEl.querySelectorAll('img[data-index]'));
            const prev = document.getElementById('prevBtn');
            const next = document.getElementById('nextBtn');
            let idx = 0;
            function setIndex(i){
                idx = i;
                const src = thumbs[idx].getAttribute('src');
                main.setAttribute('src', src);
                thumbs.forEach((t, ti) => {
                    t.style.opacity = ti === idx ? '1' : '0.6';
                    t.style.borderColor = ti === idx ? '#111827' : '#e2e8f0';
                });
            }
            thumbs.forEach(t => {
                t.addEventListener('click', () => {
                    const i = parseInt(t.getAttribute('data-index'), 10) || 0;
                    setIndex(i);
                });
            });
            prev?.addEventListener('click', () => {
                if (!thumbs.length) return;
                setIndex((idx - 1 + thumbs.length) % thumbs.length);
            });
            next?.addEventListener('click', () => {
                if (!thumbs.length) return;
                setIndex((idx + 1) % thumbs.length);
            });
        })();
        (function(){
            const ratingStars = document.getElementById('ratingStars');
            const ratingInput = document.getElementById('ratingInput');
            if (!ratingStars || !ratingInput) return;
            
            const stars = Array.from(ratingStars.querySelectorAll('.star-btn'));
            let selectedRating = 0;
            
            function updateStars(rating) {
                stars.forEach((star, index) => {
                    const starRating = index + 1;
                    if (starRating <= rating) {
                        star.style.color = '#fbbf24'; // yellow-400
                    } else {
                        star.style.color = '#cbd5e1'; // slate-300
                    }
                });
                ratingInput.value = rating;
            }
            
            stars.forEach((star, index) => {
                const starRating = index + 1;
                
                star.addEventListener('click', () => {
                    selectedRating = starRating;
                    updateStars(selectedRating);
                });
                
                star.addEventListener('mouseenter', () => {
                    updateStars(starRating);
                });
            });
            
            ratingStars.addEventListener('mouseleave', () => {
                updateStars(selectedRating);
            });
        })();
        // Currency configuration
        const currencies = {
            MYR: { symbol: 'RM', rate: 1, longSleeve: {{ config('currencies.MYR.longSleeve', 10) }} },
            BND: { symbol: '$', rate: 1.05, longSleeve: {{ config('currencies.BND.longSleeve', 3) }} },
            IDR: { symbol: 'Rp', rate: 5200, longSleeve: {{ config('currencies.IDR.longSleeve', 15600) }} }
        };
        
        let currentCurrency = '{{ $currency ?? 'MYR' }}';
        const basePrice = parseFloat('{{ ($product->on_sale && $product->discounted_price !== null) ? $product->discounted_price : $product->price }}');
        
        function getCurrencySymbol() {
            return currencies[currentCurrency].symbol;
        }
        
        function formatPrice(price) {
            const config = currencies[currentCurrency];
            const converted = price * config.rate;
            if (currentCurrency === 'IDR') {
                return getCurrencySymbol() + ' ' + Math.round(converted).toLocaleString('id-ID');
            }
            return getCurrencySymbol() + ' ' + converted.toFixed(2);
        }
        
        function updateCurrencyDisplay() {
            const config = currencies[currentCurrency];
            const currencySymbolEl = document.getElementById('currencySymbol');
            const basePriceEl = document.getElementById('basePrice');
            const longSleeveLabelEl = document.getElementById('longSleeveLabel');
            const longSleeveAddEl = document.getElementById('longSleeveAdd');
            const basePriceTextEl = document.getElementById('basePriceText');
            
            if (currencySymbolEl) currencySymbolEl.textContent = config.symbol;
            if (longSleeveLabelEl) longSleeveLabelEl.textContent = '(+' + formatPrice(config.longSleeve / config.rate) + ')';
            if (longSleeveAddEl) longSleeveAddEl.textContent = formatPrice(config.longSleeve / config.rate);
            if (basePriceTextEl) basePriceTextEl.textContent = formatPrice(basePrice);
            
            updatePrice();
        }
        
        function updatePrice() {
            const longSleeveCheckbox = document.getElementById('longSleeveCheckbox');
            const basePriceEl = document.getElementById('basePrice');
            const longSleevePriceEl = document.getElementById('longSleevePrice');
            
            if (!longSleeveCheckbox || !basePriceEl) return;
            
            const config = currencies[currentCurrency];
            const isLongSleeve = longSleeveCheckbox.checked;
            let price = basePrice * config.rate;
            
            if (isLongSleeve) {
                price += config.longSleeve;
                if (longSleevePriceEl) {
                    longSleevePriceEl.style.display = 'block';
                }
            } else {
                if (longSleevePriceEl) {
                    longSleevePriceEl.style.display = 'none';
                }
            }
            
            if (currentCurrency === 'IDR') {
                basePriceEl.textContent = Math.round(price).toLocaleString('id-ID');
            } else {
                basePriceEl.textContent = price.toFixed(2);
            }
        }
        
        // Currency selector
        const currencySelector = document.getElementById('currencySelector');
        if (currencySelector) {
            currencySelector.addEventListener('change', async function() {
                currentCurrency = this.value;
                
                // Save to session
                try {
                    await fetch('{{ route('currency.set') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ currency: currentCurrency })
                    });
                } catch (e) {
                    console.error('Failed to save currency:', e);
                }
                
                updateCurrencyDisplay();
            });
        }
        
        // Long sleeve checkbox
        const longSleeveCheckbox = document.getElementById('longSleeveCheckbox');
        if (longSleeveCheckbox) {
            longSleeveCheckbox.addEventListener('change', updatePrice);
        }
        
        // Initial display
        updateCurrencyDisplay();

        // Size button selection: selected = #155EEF background
        const sizeButtonsProduct = document.querySelectorAll('.size-btn-product');
        const selectedVariantIdInput = document.getElementById('selectedVariantId');
        const selectedSizeInput = document.getElementById('selectedSize');

        sizeButtonsProduct.forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.disabled) return;

                sizeButtonsProduct.forEach(b => {
                    if (b.disabled) {
                        b.style.borderColor = 'rgba(239,68,68,0.3)';
                        b.style.background = 'transparent';
                        b.style.color = '';
                    } else {
                        b.style.borderColor = '#E8E8E3';
                        b.style.background = 'transparent';
                        b.style.color = '#666666';
                    }
                });

                this.style.borderColor = '#155EEF';
                this.style.background = '#155EEF';
                this.style.color = '#FFFFFF';

                const variantId = this.getAttribute('data-variant-id');
                const variantName = this.getAttribute('data-variant-name');
                if (selectedVariantIdInput) selectedVariantIdInput.value = variantId;
                if (selectedSizeInput) selectedSizeInput.value = variantName;
            });
        });

        (function(){
            const imageInput = document.getElementById('feedbackImages');
            const imagePreview = document.getElementById('imagePreview');
            if (!imageInput || !imagePreview) return;
            
            imageInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                
                // Limit to max 2 images
                if (files.length > 2) {
                    alert('Maksimal 2 gambar yang dapat diupload');
                    e.target.value = '';
                    imagePreview.innerHTML = '';
                    return;
                }
                
                imagePreview.innerHTML = '';
                
                files.forEach((file, index) => {
                    if (!file.type.startsWith('image/')) {
                        alert('File harus berupa gambar');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-24 h-24 object-cover rounded border border-slate-300" />
                            <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600" data-index="${index}">×</button>
                        `;
                        imagePreview.appendChild(div);
                        
                        // Remove button functionality
                        div.querySelector('button').addEventListener('click', function() {
                            const dataTransfer = new DataTransfer();
                            const newFiles = Array.from(imageInput.files).filter((_, i) => i !== parseInt(this.getAttribute('data-index')));
                            newFiles.forEach(file => dataTransfer.items.add(file));
                            imageInput.files = dataTransfer.files;
                            div.remove();
                            // Trigger change event to update preview
                            imageInput.dispatchEvent(new Event('change'));
                        });
                    };
                    reader.readAsDataURL(file);
                });
            });
        })();
    </script>
@endsection
