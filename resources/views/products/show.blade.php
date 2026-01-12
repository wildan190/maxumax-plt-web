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
    <section class="prod-container">
        <!-- Currency Selector -->
        <div class="currency-bar">
            <div class="currency-select">
                <label>Currency:</label>
                <select id="currencySelector">
                    <option value="MYR" {{ ($currency ?? 'MYR') === 'MYR' ? 'selected' : '' }}>RM (Malaysia)</option>
                    <option value="BND" {{ ($currency ?? 'MYR') === 'BND' ? 'selected' : '' }}>$ (Brunei)</option>
                    <option value="IDR" {{ ($currency ?? 'MYR') === 'IDR' ? 'selected' : '' }}>Rp (Indonesia)</option>
                </select>
            </div>
        </div>
        <div class="prod-grid">
            <div class="prod-image" id="prodGallery">
                @php
                    $gallery = [];
                    if ($product->image_path) { $gallery[] = $product->image_path; }
                    foreach ($product->images as $img) { $gallery[] = $img->path; }
                @endphp
                @if(count($gallery))
                    <div style="position:relative; width:100%;">
                        <img id="prodMain" src="{{ asset('storage/'.$gallery[0]) }}" alt="{{ $product->name }}" style="max-width:100%; border-radius:0.5rem;" />
                        <button type="button" id="prevBtn" style="position:absolute; left:8px; top:50%; transform:translateY(-50%); background:#000; color:#fff; border:none; border-radius:9999px; width:32px; height:32px; cursor:pointer; opacity:0.8;">‹</button>
                        <button type="button" id="nextBtn" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:#000; color:#fff; border:none; border-radius:9999px; width:32px; height:32px; cursor:pointer; opacity:0.8;">›</button>
                        <div style="display:flex; gap:0.5rem; margin-top:0.5rem; justify-content:center;">
                            @foreach($gallery as $i => $path)
                                <img data-index="{{ $i }}" src="{{ asset('storage/'.$path) }}" alt="thumb {{ $i+1 }}" style="width:52px; height:52px; object-fit:cover; border-radius:0.375rem; border:1px solid #e2e8f0; cursor:pointer; opacity:0.6;">
                            @endforeach
                        </div>
                    </div>
                @else
                    <i data-feather="image" style="width:48px;height:48px;color:#64748b;"></i>
                @endif
            </div>
            <div>
                <h1 class="prod-title">{{ $product->name }}</h1>
                @if($product->jersey_type)
                    <span class="prod-type">{{ $product->jersey_type }}</span>
                @endif
                <p class="prod-desc">{{ $product->description ?: 'Premium quality jersey with breathable fabric.' }}</p>
                <div class="prod-price" id="priceDisplay">
                    <span id="currencySymbol" style="font-size:0.875rem; font-weight:600; color:#64748b;">{{ $currency === 'MYR' ? 'RM' : ($currency === 'BND' ? '$' : 'Rp') }}</span> 
                    <span id="basePrice">{{ number_format($product->price * ($currency === 'MYR' ? 1 : ($currency === 'BND' ? 1.05 : 5200)), 2) }}</span>
                </div>
                <div id="longSleevePrice" style="display:none; margin-top:0.5rem; color:#64748b; font-size:0.875rem;">
                    <span>Base price: <span id="basePriceText"></span></span><br>
                    <span>+ Long Sleeve: <span id="longSleeveAdd"></span></span>
                </div>
                <div style="margin-top:0.75rem;">
                    @if($product->available_for_preorder)
                        <a href="{{ route('preorder.create', $product) }}" class="btn"><i data-feather="shopping-cart"></i> Pre-order</a>
                    @elseif($product->is_active)
                        @if($errors->any())
                            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                                <ul style="margin: 0; padding-left: 1.25rem;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('success'))
                            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form id="activeAddToCartForm" method="POST" action="{{ route('cart.add') }}" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div id="configPanel" style="display:grid; grid-template-columns: 1fr 1fr; gap:0.75rem; margin-bottom:0.75rem; max-width:420px;">
                                <div style="grid-column: span 2;">
                                    <label style="display:block; font-weight:600; color:#111827; margin-bottom:0.5rem;">Ukuran <span style="color:#ef4444;">*</span></label>
                                    @if($product->hasVariants())
                                        <input type="hidden" name="product_variant_id" id="selectedVariantId" required />
                                        <input type="hidden" name="size" id="selectedSize" value="" />
                                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap: 0.5rem;">
                                            @foreach($product->variants as $variant)
                                                <button 
                                                    type="button" 
                                                    class="size-btn-product" 
                                                    data-variant-id="{{ $variant->id }}" 
                                                    data-variant-name="{{ $variant->name }}"
                                                    data-variant-stock="{{ $variant->stock }}"
                                                    {{ $variant->hasStock() ? '' : 'disabled' }}
                                                    style="padding: 0.75rem 0.5rem; border: 2px solid {{ $variant->hasStock() ? '#e2e8f0' : '#fca5a5' }}; background: {{ $variant->hasStock() ? 'white' : '#fee2e2' }}; border-radius: 0.5rem; font-weight: 600; font-size: 0.95rem; cursor: {{ $variant->hasStock() ? 'pointer' : 'not-allowed' }}; transition: all 0.2s; text-align: center; opacity: {{ $variant->hasStock() ? '1' : '0.6' }};"
                                                >
                                                    <div style="font-size: 1rem; margin-bottom: 0.15rem;">{{ $variant->name }}</div>
                                                    <div style="font-size: 0.7rem; color: {{ $variant->hasStock() ? '#6b7280' : '#dc2626' }}; font-weight: normal;">
                                                        {{ $variant->hasStock() ? $variant->stock . ' left' : 'Habis' }}
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <select id="sizeSelect" name="size" required style="width:100%; padding:0.5rem; border:1px solid #e5e7eb; border-radius:0.5rem;">
                                            <option value="">Pilih ukuran</option>
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                            <option value="XXL">XXL</option>
                                        </select>
                                    @endif
                                </div>
                                <div>
                                    <label style="display:block; font-weight:600; color:#111827; margin-bottom:0.25rem;">Jumlah</label>
                                    <input type="number" id="qtyInput" name="quantity" value="1" min="1" required style="width:100%; padding:0.5rem; border:1px solid #e5e7eb; border-radius:0.5rem;">
                                </div>
                                <label style="grid-column: span 2; display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                                    <input type="checkbox" id="longSleeveCheckbox" name="long_sleeve" value="1" style="width:1.25rem;height:1.25rem; cursor:pointer;">
                                    <span style="color:#111827;">Long Sleeve <span id="longSleeveLabel" style="color:#64748b; font-size:0.875rem;">(+RM 3.00)</span></span>
                                </label>
                            </div>
                            <button type="submit" class="btn" style="background:#111827;"><i data-feather="shopping-bag"></i> Add to Cart</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div style="margin-top:1.5rem;" class="card">
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <div style="font-weight:800; color:#0f172a;">Feedback & Rating</div>
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    @php $rounded = (int) round($feedbackAvg ?? 0); @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $rounded ? 'text-yellow-500' : 'text-slate-300' }}">★</span>
                    @endfor
                    <span style="color:#64748b; font-size:0.875rem;">{{ number_format($feedbackAvg ?? 0, 2) }} dari {{ $feedbackCount ?? 0 }} feedback</span>
                </div>
            </div>
            <div style="margin-top:0.75rem;">
                <form method="POST" action="{{ route('feedback.store') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.75rem;">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama (opsional)</label>
                            <input type="text" name="name" class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Email (opsional)</label>
                            <input type="email" name="email" class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Rating</label>
                        <div class="flex items-center gap-1" id="ratingStars">
                            @for ($r = 1; $r <= 5; $r++)
                                <button type="button" class="star-btn text-2xl text-slate-300 hover:text-yellow-400 transition-colors cursor-pointer" data-rating="{{ $r }}" style="background: none; border: none; padding: 0; line-height: 1;">★</button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="" required />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Feedback</label>
                        <textarea name="comment" rows="3" class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" placeholder="Tulis masukan Anda di sini..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Upload Gambar (Maksimal 2 gambar)</label>
                        <input type="file" name="images[]" id="feedbackImages" accept="image/*" multiple class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
                        <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, GIF. Maksimal 5MB per gambar.</p>
                        <div id="imagePreview" class="mt-3 flex gap-3 flex-wrap"></div>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center bg-black text-white px-5 py-3 rounded-md font-semibold text-base transition hover:bg-slate-900 hover:-translate-y-0.5">Kirim Feedback</button>
                    </div>
                </form>
            </div>

            <div style="margin-top:1rem;">
                <div class="text-slate-900 font-bold text-lg mb-2">Feedback Terbaru</div>
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:0.75rem;">
                    @forelse ($latestFeedback ?? [] as $fb)
                        @php $rr = (int) $fb->rating; @endphp
                        <div class="bg-white border border-slate-200 rounded-xl p-4">
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                                <div class="font-semibold text-slate-900">{{ $fb->name ?? 'Anonim' }}</div>
                                <div class="flex items-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $rr ? 'text-yellow-500' : 'text-slate-300' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-slate-600 text-sm">{{ $fb->comment ?? '-' }}</div>
                            @if($fb->images && count($fb->images))
                                <div class="flex gap-2 mt-2 flex-wrap">
                                    @foreach(array_slice($fb->images, 0, 2) as $img)
                                        <a href="{{ asset('storage/' . $img) }}" target="_blank" class="block">
                                            <img src="{{ asset('storage/' . $img) }}" alt="Feedback image" class="w-20 h-20 object-cover rounded border border-slate-200 hover:opacity-80 transition" />
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            <div class="text-slate-400 text-xs mt-2">{{ $fb->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="text-slate-500">Belum ada feedback.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
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
            MYR: { symbol: 'RM', rate: 1, longSleeve: 3 },
            BND: { symbol: '$', rate: 1.05, longSleeve: 3 },
            IDR: { symbol: 'Rp', rate: 5200, longSleeve: 15600 }
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
