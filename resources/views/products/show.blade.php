@extends('layouts.public')

@section('title', $product->name . ' — Product Detail')

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
    <div class="bg-black min-h-screen pt-24 pb-32 px-6">
        <div class="max-w-7xl mx-auto">
            <!-- Product Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                
                <!-- Left: Cinematic Gallery -->
                <div class="space-y-6" id="prodGallery">
                    @php
                        $gallery = [];
                        if ($product->image_path) { $gallery[] = $product->image_path; }
                        foreach ($product->images as $img) { $gallery[] = $img->path; }
                    @endphp
                    
                    <div class="aspect-[4/5] bg-gradient-to-b from-[#1a1a1a] to-[#0a0a0a] rounded-[2.5rem] border border-white/5 relative overflow-hidden flex items-center justify-center p-12 group">
                        @if(count($gallery))
                            <img id="prodMain" src="{{ asset('storage/'.$gallery[0]) }}" alt="{{ $product->name }}" 
                                 class="max-w-full max-h-full object-contain drop-shadow-[0_40px_80px_rgba(0,0,0,0.9)] transition-transform duration-700 group-hover:scale-105" />
                            
                            <!-- Internal Controls -->
                            <button type="button" id="prevBtn" class="absolute left-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/5 border border-white/10 rounded-full flex items-center justify-center text-white hover:bg-white hover:text-black transition-all opacity-0 group-hover:opacity-100">
                                <i data-feather="chevron-left" style="width:20px;height:20px"></i>
                            </button>
                            <button type="button" id="nextBtn" class="absolute right-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/5 border border-white/10 rounded-full flex items-center justify-center text-white hover:bg-white hover:text-black transition-all opacity-0 group-hover:opacity-100">
                                <i data-feather="chevron-right" style="width:20px;height:20px"></i>
                            </button>
                        @else
                            <div class="text-white/10 flex flex-col items-center gap-4">
                                <i data-feather="image" style="width:80px;height:80px"></i>
                                <span class="text-[10px] uppercase font-black tracking-widest">No Image Asset</span>
                            </div>
                        @endif

                        <div class="absolute top-8 left-8">
                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg">Premium Edition</span>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    @if(count($gallery) > 1)
                        <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                            @foreach($gallery as $i => $path)
                                <button type="button" data-index="{{ $i }}" 
                                        class="thumb-btn flex-shrink-0 w-24 aspect-square bg-[#111111] rounded-2xl border border-white/5 p-4 transition-all hover:border-white/20"
                                        style="opacity: {{ $i === 0 ? '1' : '0.4' }}; border-color: {{ $i === 0 ? 'rgba(255,255,255,0.2)' : 'rgba(255,255,255,0.05)' }}">
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
                                <span class="text-white/40 font-black uppercase tracking-[0.3em] text-[10px] px-4 py-1.5 border border-white/5 rounded-full">{{ $product->jersey_type }}</span>
                            @endif
                            <div class="flex items-center gap-1">
                                @php $rounded = (int) round($feedbackAvg ?? 0); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <i data-feather="star" style="width:12px;height:12px" class="{{ $i <= $rounded ? 'fill-yellow-500 text-yellow-500' : 'text-white/10' }}"></i>
                                @endfor
                                <span class="text-[10px] font-bold text-white/40 ml-2">({{ $feedbackCount ?? 0 }})</span>
                            </div>
                        </div>

                        <h1 class="text-5xl md:text-7xl font-black text-white italic uppercase tracking-tighter leading-[0.9] mb-8">
                            {{ $product->name }}
                        </h1>

                        <p class="text-white/40 text-lg leading-relaxed mb-10 max-w-xl">
                            {{ $product->description ?: 'Precision-engineered performance apparel. Designed for the elite who demand absolute excellence on and off the field.' }}
                        </p>

                        <!-- Price Section -->
                        <div class="bg-[#111111] rounded-3xl p-8 border border-white/5 mb-12 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-black text-white/40 uppercase tracking-widest block mb-2" id="currencyLabel">{{ $currency }} INVESTMENT</span>
                                <div class="flex items-baseline gap-2">
                                    <span id="priceDisplay" class="text-5xl font-black text-white tracking-tight">
                                        {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                                    </span>
                                </div>
                            </div>
                            <div id="longSleevePrice" class="hidden text-right border-l border-white/5 pl-8">
                                <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest block mb-2">Extended Sleeve Upgrade</span>
                                <span id="longSleeveAdd" class="text-xl font-black text-white">+ {{ number_format($currencyConfig['longSleeve'], $currency == 'IDR' ? 0 : 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Configuration Panel -->
                    <div class="space-y-10">
                        @if($product->available_for_preorder)
                            <div class="p-8 bg-blue-500/10 border border-blue-500/20 rounded-3xl mb-8">
                                <h3 class="text-blue-400 font-black text-xs uppercase tracking-widest mb-4">Limited Pre-order Event</h3>
                                <p class="text-blue-400/60 text-sm mb-6">This item is currently in production. Reserve yours now for guaranteed priority deployment.</p>
                                <a href="{{ route('preorder.create', $product) }}" class="w-full bg-white text-black py-5 rounded-2xl font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:scale-[1.02] active:scale-95 transition-all shadow-xl">
                                    <i data-feather="zap" style="width:18px;height:18px"></i>
                                    Reserve Now
                                </a>
                            </div>
                        @elseif($product->is_active)
                            <form id="activeAddToCartForm" method="POST" action="{{ route('cart.add') }}" class="space-y-8">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <!-- Alert Section -->
                                @if($errors->any() || session('success'))
                                    <div class="animate-fade-in">
                                        @if($errors->any())
                                            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-xs font-bold flex items-center gap-3">
                                                <i data-feather="alert-circle" style="width:14px;height:14px"></i>
                                                {{ $errors->first() }}
                                            </div>
                                        @endif
                                        @if(session('success'))
                                            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl text-xs font-bold flex items-center gap-3">
                                                <i data-feather="check-circle" style="width:14px;height:14px"></i>
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Size Selection -->
                                <div class="space-y-5">
                                    <div class="flex justify-between items-end">
                                        <label class="text-[10px] font-black text-white/40 uppercase tracking-widest">Select Deployment Size</label>
                                        <button type="button" class="text-[10px] font-black text-white/20 uppercase tracking-widest hover:text-white transition-colors">Size Guide</button>
                                    </div>
                                    
                                    @if($product->hasVariants())
                                        <input type="hidden" name="product_variant_id" id="selectedVariantId" required />
                                        <input type="hidden" name="size" id="selectedSize" value="" />
                                        <div class="grid grid-cols-4 md:grid-cols-5 gap-3">
                                            @foreach($product->variants as $variant)
                                                <button type="button" 
                                                    class="size-btn-product group relative py-4 rounded-xl border font-black text-xs uppercase tracking-widest transition-all
                                                    {{ $variant->hasStock() ? 'border-white/10 text-white/40 hover:border-white/40 hover:text-white' : 'border-red-500/10 text-red-500/20 cursor-not-allowed opacity-50' }}"
                                                    data-variant-id="{{ $variant->id }}" 
                                                    data-variant-name="{{ $variant->name }}"
                                                    {{ $variant->hasStock() ? '' : 'disabled' }}>
                                                    {{ $variant->name }}
                                                    @if($variant->hasStock())
                                                        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center text-[7px] font-black border border-emerald-500/30 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            {{ $variant->stock }}
                                                        </span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="relative">
                                            <i data-feather="layers" class="absolute left-6 top-1/2 -translate-y-1/2 text-white/20" style="width:18px;height:18px"></i>
                                            <select id="sizeSelect" name="size" required class="w-full bg-white/5 border border-white/10 rounded-2xl pl-16 pr-8 py-5 text-white font-black uppercase tracking-widest focus:outline-none focus:border-white transition-all appearance-none">
                                                <option value="" class="bg-zinc-900">Select Frame</option>
                                                @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $s)
                                                    <option value="{{ $s }}" class="bg-zinc-900">{{ $s }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>

                                <!-- Quantity and Addon -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-white/40 uppercase tracking-widest">Quantity</label>
                                        <div class="relative">
                                            <input type="number" id="qtyInput" name="quantity" value="1" min="1" required 
                                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-5 text-white font-black focus:outline-none focus:border-white transition-all">
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-white/40 uppercase tracking-widest">Specifications</label>
                                        <label class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-2xl px-6 py-5 cursor-pointer hover:bg-white/10 transition-all">
                                            <input type="checkbox" id="longSleeveCheckbox" name="long_sleeve" value="1" class="w-5 h-5 rounded-md bg-black border-white/10 checked:bg-white checked:border-white transition-all appearance-none cursor-pointer border-2 relative checked:after:content-['✓'] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:-translate-x-1/2 checked:after:-translate-y-1/2 checked:after:text-black checked:after:font-black checked:after:text-[10px]">
                                            <span class="text-white font-black text-xs uppercase tracking-widest">Extended Sleeve</span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-white text-black py-6 rounded-2xl font-black uppercase tracking-[0.3em] flex items-center justify-center gap-4 hover:bg-zinc-200 hover:scale-[1.02] active:scale-95 transition-all shadow-2xl">
                                    <i data-feather="shopping-bag" style="width:20px;height:20px"></i>
                                    Add To Deployment
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detailed Specs & Feedback -->
            <div class="mt-40 grid grid-cols-1 lg:grid-cols-3 gap-16">
                <!-- Feedback Form -->
                <div class="lg:col-span-1">
                    <div class="sticky top-32">
                        <span class="text-white/40 font-black uppercase tracking-[0.3em] text-[10px] mb-4 block text-center lg:text-left">Performance Review</span>
                        <h2 class="text-4xl font-black text-white tracking-tighter uppercase italic mb-10 text-center lg:text-left">Submit <span class="text-white/40">Feedback.</span></h2>
                        
                        <div class="bg-[#111111] border border-white/5 rounded-[2.5rem] p-10 shadow-3xl">
                            <form method="POST" action="{{ route('feedback.store') }}" enctype="multipart/form-data" class="space-y-8">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="space-y-6">
                                    <div class="flex justify-center mb-4">
                                        <div class="flex items-center gap-2" id="ratingStars">
                                            @for ($r = 1; $r <= 5; $r++)
                                                <button type="button" class="star-btn text-3xl text-white/5 hover:text-yellow-500 transition-colors cursor-pointer" data-rating="{{ $r }}">★</button>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="rating" id="ratingInput" value="" required />
                                    </div>

                                    <div class="space-y-4">
                                        <input type="text" name="name" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm placeholder-white/20 focus:outline-none focus:border-white transition-all uppercase tracking-widest font-black" placeholder="Identifier (Optional)" />
                                        <textarea name="comment" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm placeholder-white/20 focus:outline-none focus:border-white transition-all" placeholder="Strategic feedback details..."></textarea>
                                    </div>

                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2 block">Upload Intel (Max 2)</label>
                                        <input type="file" name="images[]" id="feedbackImages" accept="image/*" multiple class="hidden" />
                                        <button type="button" onclick="document.getElementById('feedbackImages').click()" class="w-full border-2 border-dashed border-white/5 rounded-2xl py-8 flex flex-col items-center gap-3 text-white/20 hover:text-white hover:border-white/20 transition-all">
                                            <i data-feather="upload-cloud" style="width:24px;height:24px"></i>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Select Image Assets</span>
                                        </button>
                                        <div id="imagePreview" class="flex gap-4 flex-wrap"></div>
                                    </div>

                                    <button type="submit" class="w-full bg-white/5 border border-white/10 text-white py-4 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-white hover:text-black transition-all">Transmitting Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Feedback Feed -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-12">
                        <h3 class="text-2xl font-black text-white tracking-widest uppercase">Field Reports</h3>
                        <div class="h-px bg-white/5 flex-grow mx-8"></div>
                        <span class="text-white/40 font-black text-xs uppercase tracking-widest">{{ $feedbackCount ?? 0 }} Reports</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @forelse ($latestFeedback ?? [] as $fb)
                            @php 
                                /** @var \App\Models\Feedback|null $fb */
                                $rr = ($fb instanceof \App\Models\Feedback) ? (int) $fb->rating : 0; 
                            @endphp
                            <div class="bg-[#111111] border border-white/5 rounded-[2rem] p-8 shadow-xl transition-all hover:bg-white/[0.02]">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="pointer-events-none">
                                        <span class="text-white font-black text-xs uppercase tracking-widest block mb-1">{{ ($fb instanceof \App\Models\Feedback) ? $fb->name : 'ANONYMOUS OPERATIVE' }}</span>
                                        <span class="text-white/20 text-[9px] font-black uppercase tracking-widest">{{ ($fb instanceof \App\Models\Feedback && $fb->created_at) ? $fb->created_at->diffForHumans() : 'RECENTLY' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i data-feather="star" style="width:10px;height:10px" class="{{ $i <= $rr ? 'fill-yellow-500 text-yellow-500' : 'text-white/10' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-white/60 text-sm leading-relaxed mb-6 italic">"{{ ($fb instanceof \App\Models\Feedback) ? ($fb->comment ?? 'No additional logistics notes provided.') : 'LOGISTICS NOTE ENCRYPTED.' }}"</p>
                                
                                @if($fb instanceof \App\Models\Feedback && $fb->images && is_array($fb->images) && count($fb->images))
                                    <div class="flex gap-3">
                                        @foreach(array_slice($fb->images, 0, 2) as $img)
                                            <a href="{{ asset('storage/' . $img) }}" target="_blank" class="block w-20 h-20 rounded-xl overflow-hidden border border-white/5 hover:border-white/20 transition-all">
                                                <img src="{{ asset('storage/' . $img) }}" alt="Intel" class="w-full h-full object-cover">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-full py-24 text-center border-2 border-dashed border-white/5 rounded-[2.5rem]">
                                <i data-feather="inbox" class="mx-auto text-white/10 mb-6" style="width:48px;height:48px"></i>
                                <h4 class="text-white font-black uppercase tracking-widest mb-2">No Reports Filed</h4>
                                <p class="text-white/20 text-sm font-medium">This product awaits its first evaluation.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    t.style.borderColor = ti === idx ? 'rgba(255,255,255,0.2)' : 'rgba(255,255,255,0.05)';
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
                        star.classList.remove('text-white/5');
                    } else {
                        star.classList.add('text-white/5');
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
            const basePrice = parseFloat('{{ $product->price }}');
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

            sizeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.disabled) return;
                    sizeBtns.forEach(b => {
                        if(!b.disabled) {
                            b.classList.remove('bg-white', 'text-black', 'border-white');
                            b.classList.add('border-white/10', 'text-white/40');
                        }
                    });
                    this.classList.remove('border-white/10', 'text-white/40');
                    this.classList.add('bg-white', 'text-black', 'border-white');
                    
                    if(variantInput) variantInput.value = this.getAttribute('data-variant-id');
                    if(sizeStringInput) sizeStringInput.value = this.getAttribute('data-variant-name');
                });
            });
        })();
    </script>
@endsection
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
        const basePrice = parseFloat('{{ $product->price }}');
        
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

        // Size button selection functionality for product page
        const sizeButtonsProduct = document.querySelectorAll('.size-btn-product');
        const selectedVariantIdInput = document.getElementById('selectedVariantId');
        const selectedSizeInput = document.getElementById('selectedSize');

        sizeButtonsProduct.forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.disabled) return;

                // Remove selection from all buttons
                sizeButtonsProduct.forEach(b => {
                    b.style.borderColor = b.disabled ? '#fca5a5' : '#e2e8f0';
                    b.style.background = b.disabled ? '#fee2e2' : 'white';
                });

                // Mark this button as selected
                this.style.borderColor = '#111827';
                this.style.background = '#f3f4f6';

                // Update hidden inputs
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
