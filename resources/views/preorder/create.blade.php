@extends('layouts.public')

@section('title', 'Pre-order - Complete Details')



@section('content')
    <section class="preorder-container">
        <!-- Currency Selector -->


        <!-- Stepper -->
        <div class="stepper">
            <div class="step-item active" data-step="1">
                <div class="step-circle">1</div>
                <span class="step-label">Product</span>
            </div>
            <div class="step-connector"></div>
            <div class="step-item" data-step="2">
                <div class="step-circle">2</div>
                <span class="step-label">Details</span>
            </div>
            <div class="step-connector"></div>
            <div class="step-item" data-step="3">
                <div class="step-circle">3</div>
                <span class="step-label">Customize</span>
            </div>
            <div class="step-connector"></div>
            <div class="step-item" data-step="4">
                <div class="step-circle">4</div>
                <span class="step-label">Shipping</span>
            </div>
            <div class="step-connector"></div>
            <div class="step-item" data-step="5">
                <div class="step-circle">5</div>
                <span class="step-label">Review</span>
            </div>
        </div>

        <div class="preorder-layout">
            <!-- Product Summary Card -->
            <div class="product-summary-card">
                <div class="product-image-wrapper" id="orderGallery">
                    @php
                        $gallery = [];
                        if ($product->image_path) {
                            $gallery[] = $product->image_path;
                        }
                        foreach ($product->images as $img) {
                            $gallery[] = $img->path;
                        }
                    @endphp
                    @if(count($gallery))
                        <div style="position:relative; width:100%;">
                            <img id="orderMainImg" src="{{ asset('storage/' . $gallery[0]) }}" alt="{{ $product->name }}"
                                style="max-width:100%; max-height:280px; object-fit:contain; border-radius:0.5rem;" />
                            <div style="display:flex; gap:0.5rem; margin-top:0.5rem; flex-wrap:wrap;">
                                @foreach($gallery as $i => $path)
                                    <img data-index="{{ $i }}" src="{{ asset('storage/' . $path) }}" alt="thumb {{ $i + 1 }}"
                                        style="width:56px; height:56px; object-fit:cover; border-radius:0.375rem; border:1px solid #e2e8f0; cursor:pointer; opacity: 0.6;">
                                @endforeach
                            </div>
                        </div>
                    @else
                        <span class="placeholder">👕</span>
                    @endif
                </div>
                <div class="product-details">
                    <h2>{{ $product->name }}</h2>
                    @if($product->jersey_type)
                        <span class="product-type-badge">{{ $product->jersey_type }}</span>
                    @endif
                    <p class="product-description">
                        {{ $product->description ?: 'Premium quality jersey with breathable fabric.' }}
                    </p>

                    @if($product->sku || $product->stock)
                        <div class="product-meta">
                            @if($product->sku)
                                <div class="meta-item">
                                    <label>SKU</label>
                                    <span>{{ $product->sku }}</span>
                                </div>
                            @endif
                            @if($product->stock)
                                <div class="meta-item">
                                    <label>Available</label>
                                    <span>{{ $product->stock }} units</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="product-price-display">
                        <div class="label">Base Price</div>
                        <div class="price" id="basePriceDisplay">
                            {{ $currencyConfig['rate'] == 5200 || $currency == 'IDR' ? 'Rp' : ($currency == 'BND' ? '$' : ($currency == 'SGD' ? 'S$' : 'RM')) }}
                            {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Multi-Step Form -->
            <div class="form-card">
                <form method="POST"
                    action="{{ route($product->available_for_preorder ? 'preorder.store' : 'order.store') }}"
                    id="preorderForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    <input type="hidden" name="currency" id="currencyInput" value="{{ $currency }}" />

                    <!-- Step 1: Product Confirmation -->
                    <div class="form-step active" id="step1">
                        <h3 class="step-title"><span class="icon">1</span> Confirm Product</h3>

                        <div class="review-block">
                            <div class="label">Selected Product</div>
                            <div class="value">{{ $product->name }}</div>
                        </div>

                        <div class="review-block">
                            <div class="label">Price</div>
                            <div class="value" id="step1Price">
                                {{ $currencyConfig['rate'] == 5200 || $currency == 'IDR' ? 'Rp' : ($currency == 'BND' ? '$' : ($currency == 'SGD' ? 'S$' : 'RM')) }}
                                {{ number_format($product->price * $currencyConfig['rate'], $currency == 'IDR' ? 0 : 2) }}
                            </div>
                        </div>

                        <div class="form-nav">
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Continue →</button>
                        </div>
                    </div>

                    <!-- Step 2: Customer Details -->
                    <div class="form-step" id="step2">
                        <h3 class="step-title"><span class="icon">2</span> Your Details</h3>

                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}"
                                placeholder="Enter your full name" />
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Email (Optional)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                placeholder="your@email.com" />
                        </div>

                        <div class="form-group">
                            <label>Phone / WhatsApp <span class="required">*</span></label>
                            <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}"
                                placeholder="+673 xxxx xxxx" />
                            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Delivery Address <span class="required">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div>
                                    <label>Region <span class="required">*</span></label>
                                    <input type="text" name="region" class="form-control" required value="{{ old('region') }}" placeholder="Enter region" />
                                    @error('region')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label>Country/Province <span class="required">*</span></label>
                                    <input type="text" name="province" class="form-control" required value="{{ old('province') }}" placeholder="Enter country/province" />
                                    @error('province')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label>City <span class="required">*</span></label>
                                    <input type="text" name="city" class="form-control" required value="{{ old('city') }}" placeholder="Enter city" />
                                    @error('city')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label>Postal Code <span class="required">*</span></label>
                                    <input type="text" name="postal_code" class="form-control" required value="{{ old('postal_code') }}" placeholder="Enter postal code" />
                                    @error('postal_code')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="mt-2">
                                <label>Address Detail <span class="required">*</span></label>
                                <textarea name="address_detail" class="form-control" required placeholder="Enter address detail">{{ old('address_detail') }}</textarea>
                                @error('address_detail')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-nav">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">← Back</button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Continue →</button>
                        </div>
                    </div>

                    <!-- Step 3: Customize -->
                    <div class="form-step" id="step3">
                        <h3 class="step-title"><span class="icon">3</span> Customize Your Jersey</h3>

                        @if($errors->any())
                            <div class="form-error-summary"
                                style="background: #fef2f2; border: 1px solid #fecaca; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; color: #991b1b;">
                                <div style="font-weight: 700; margin-bottom: 0.5rem;">Please fix the following errors:</div>
                                <ul style="list-style: disc; padding-left: 1.5rem; margin: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Select Creates & Quantity <span class="required">*</span></label>

                            <div class="variant-list"
                                style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                                @foreach($product->variants as $variant)
                                    <div class="variant-card" data-variant-id="{{ $variant->id }}">
                                        <div class="variant-header">
                                            <div class="variant-title">{{ $variant->name }}</div>
                                            @if(!$product->available_for_preorder)
                                                <div
                                                    class="variant-stock {{ $variant->hasStock() ? ($variant->stock < 5 ? 'stock-low' : 'stock-ok') : 'stock-out' }}">
                                                    {{ $variant->hasStock() ? 'Stock: ' . $variant->stock : 'Out of Stock' }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Short Sleeve Section -->
                                        <div class="variant-section">
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                                <span class="section-label">Short Sleeve</span>
                                                @if($product->available_for_preorder || $variant->hasStock())
                                                    <div class="qty-stepper">
                                                        <button type="button" class="qty-btn"
                                                            onclick="decQty({{ $variant->id }}, 'ss')">−</button>
                                                        <input type="number" name="items[{{ $variant->id }}][quantity_ss]"
                                                            class="qty-input-display ss-input" value="0" min="0" readonly>
                                                        <button type="button" class="qty-btn"
                                                            onclick="incQty({{ $variant->id }}, 'ss')">+</button>
                                                    </div>
                                                @else
                                                    <span class="stock-out" style="font-size:0.9rem;">Unavailable</span>
                                                @endif
                                            </div>

                                            <!-- Nameset SS -->
                                            <label class="option-toggle" id="toggle-nameset-ss-{{ $variant->id }}"
                                                style="display:none;">
                                                <input type="checkbox" id="check-nameset-ss-{{ $variant->id }}"
                                                    onchange="toggleNameset({{ $variant->id }}, 'ss')">
                                                <div>
                                                    <div class="option-label">Add Name & Number</div>
                                                    <div class="option-price">+<span class="currency-symbol">RM</span> <span
                                                            class="nameset-price">0</span></div>
                                                </div>
                                            </label>

                                            <div class="nameset-container" id="nameset-container-ss-{{ $variant->id }}"
                                                style="display: none;"></div>
                                        </div>

                                        <!-- Long Sleeve Section -->
                                        <div class="ls-section-wrapper" id="section-ls-{{ $variant->id }}"
                                            style="display:none;">
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                                <div>
                                                    <span class="section-label">Long Sleeve (+<span
                                                            class="currency-symbol">RM</span> <span
                                                            class="long-sleeve-price">0</span>)</span>
                                                </div>
                                                <div class="qty-stepper">
                                                    <button type="button" class="qty-btn"
                                                        onclick="decQty({{ $variant->id }}, 'ls')">−</button>
                                                    <input type="number" name="items[{{ $variant->id }}][quantity_ls]"
                                                        class="qty-input-display ls-input" value="0" min="0" readonly>
                                                    <button type="button" class="qty-btn"
                                                        onclick="incQty({{ $variant->id }}, 'ls')">+</button>
                                                </div>
                                            </div>

                                            <!-- Nameset LS -->
                                            <label class="option-toggle" id="toggle-nameset-ls-{{ $variant->id }}"
                                                style="display:none;">
                                                <input type="checkbox" id="check-nameset-ls-{{ $variant->id }}"
                                                    onchange="toggleNameset({{ $variant->id }}, 'ls')">
                                                <div>
                                                    <div class="option-label">Add Name & Number</div>
                                                    <div class="option-price">+<span class="currency-symbol">RM</span> <span
                                                            class="nameset-price">0</span></div>
                                                </div>
                                            </label>

                                            <div class="nameset-container" id="nameset-container-ls-{{ $variant->id }}"
                                                style="display: none;"></div>
                                        </div>

                                        <!-- Add Long Sleeve Button (Visible when LS section is hidden) -->
                                        <button type="button" class="btn-action-ghost" id="btn-add-ls-{{ $variant->id }}"
                                            onclick="toggleLS({{ $variant->id }})">
                                            + Add Long Sleeve Option
                                        </button>

                                        <!-- Hidden checkbox to maintain logic compatibility if needed, or just use JS to handle visibility -->
                                        <input type="checkbox" id="check-ls-{{ $variant->id }}" style="display:none;">

                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 1.25rem;">
                            <label>Special Requests / Notes</label>
                            <textarea name="notes" class="form-control"
                                placeholder="Any special instructions or requests...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="form-nav">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">← Back</button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Review Order →</button>
                        </div>
                    </div>

                    <!-- Step 4: Shipping -->
                    <div class="form-step" id="step4">
                        <h3 class="step-title"><span class="icon">4</span> Select Shipping</h3>

                        <style>
                            @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
                            .shipping-card {
                                border: 2px solid #e2e8f0;
                                border-radius: 0.5rem;
                                padding: 1rem;
                                cursor: pointer;
                                transition: all 0.2s;
                                position: relative;
                            }
                            .shipping-card:hover {
                                border-color: #cbd5e1;
                                background: #f8fafc;
                            }
                            .shipping-card.selected {
                                border-color: #111827;
                                background: #f0f9ff;
                            }
                            .shipping-card img {
                                height: 32px;
                                object-fit: contain;
                                margin-bottom: 0.5rem;
                            }
                        </style>

                        <div id="shipping-loader" style="text-align: center; padding: 2rem; display: none;">
                            <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                            <p>Fetching best shipping rates...</p>
                        </div>

                        <div id="shipping-error" style="color: #ef4444; padding: 1rem; background: #fee2e2; border-radius: 0.5rem; display: none; margin-bottom: 1rem;"></div>

                        <div id="shipping-rates-list" class="shipping-options-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                            <!-- Populated by JS -->
                        </div>

                        <!-- Hidden inputs for selected shipping -->
                        <input type="hidden" name="shipping_courier_name" id="input_shipping_courier_name">
                        <input type="hidden" name="shipping_courier_logo" id="input_shipping_courier_logo">
                        <input type="hidden" name="shipping_service_name" id="input_shipping_service_name">
                        <input type="hidden" name="shipping_service_id" id="input_shipping_service_id">
                        <input type="hidden" name="shipping_cost" id="input_shipping_cost">

                        <div class="form-nav">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">← Back</button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()" id="btn-shipping-next" disabled>Continue →</button>
                        </div>
                    </div>

                    <!-- Step 5: Review -->
                    <div class="form-step" id="step5">
                        <h3 class="step-title"><span class="icon">✓</span> Review Your Order</h3>

                        <div class="review-block">
                            <div class="label">Product</div>
                            <div class="value">{{ $product->name }}</div>
                        </div>

                        <div class="review-block">
                            <div class="label">Customer Details</div>
                            <div class="value" id="reviewCustomerDetails">-</div>
                        </div>

                        <div class="review-block">
                            <div class="label">Order Summary</div>
                            <div id="reviewItemsList" class="review-items-list">
                                <!-- Populated by JS -->
                            </div>
                        </div>

                        <div class="review-total"
                            style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; font-weight: 700; font-size: 1.25rem; color: #111827;">
                                <span>Total Amount</span>
                                <span id="summaryTotal">-</span>
                            </div>
                        </div>

                        <div class="payment-method-selection" style="margin: 1.5rem 0;">
                            <h4 style="margin-bottom: 1rem; font-size: 1.1rem;">Select Payment Method</h4>

                            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                                <label class="payment-option" id="payment-cod"
                                    style="flex: 1; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
                                    <input type="radio" name="payment_method" value="cod" checked
                                        style="margin-right: 0.5rem;" onchange="updatePaymentMethod(this.value)">
                                    <span style="font-weight: 500;">💵 Cash on Delivery (COD)</span>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #64748b;">Pay when you
                                        receive the order</p>
                                </label>

                                <label class="payment-option" id="payment-stripe"
                                    style="flex: 1; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
                                    <input type="radio" name="payment_method" value="stripe" style="margin-right: 0.5rem;"
                                        onchange="updatePaymentMethod(this.value)">
                                    <span style="font-weight: 500;">💳 Credit/Debit Card (Stripe)</span>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #64748b;">Pay securely with
                                        Stripe</p>
                                </label>
                            </div>
                        </div>

                        <div class="form-nav">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">← Back</button>
                            <button type="button" class="btn btn-success" onclick="submitForm()">✓ Continue to
                                Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        (function () {
            const g = document.getElementById('orderGallery');
            if (g) {
                const main = document.getElementById('orderMainImg');
                const thumbs = Array.from(g.querySelectorAll('img[data-index]'));
                let idx = 0;
                if (thumbs.length) {
                    thumbs[0].style.opacity = '1';
                    thumbs[0].style.borderColor = '#111827';
                }
                function setIdx(i) {
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
                        setIdx(i);
                    });
                });
            }
        })();

        // Currency configuration
        const currencies = {
            MYR: { symbol: 'RM', rate: 1, longSleeve: 10, nameset: 35 },
            BND: { symbol: '$', rate: 1.05, longSleeve: 3, nameset: 13 },
            SGD: { symbol: 'S$', rate: 1.05, longSleeve: 3, nameset: 13 },
            IDR: { symbol: 'Rp', rate: 5200, longSleeve: 15600, nameset: 67600 }
        };

        let currentCurrency = '{{ $currency }}';
        let currentStep = 1;
        const totalSteps = 5;
        const basePrice = parseFloat('{{ number_format($product->price, 2, ".", "") }}');
        let shippingCost = 0;

        function updateCurrencyDisplay() {
            if (!currencies[currentCurrency]) return;
            const conf = currencies[currentCurrency];

            document.querySelectorAll('.currency-symbol').forEach(el => el.innerText = conf.symbol);

            // Update price display on product card (Use correct ID: basePriceDisplay)
            const convertedPrice = basePrice * conf.rate;
            const priceElement = document.getElementById('basePriceDisplay');
            if (priceElement) {
                if (currentCurrency === 'IDR') {
                    priceElement.innerText = conf.symbol + ' ' + Math.round(convertedPrice).toLocaleString('id-ID');
                } else {
                    priceElement.innerText = conf.symbol + ' ' + convertedPrice.toFixed(2);
                }
            }

            const step1Price = document.getElementById('step1Price');
            if (step1Price) {
                if (currentCurrency === 'IDR') {
                    step1Price.innerText = conf.symbol + ' ' + Math.round(convertedPrice).toLocaleString('id-ID');
                } else {
                    step1Price.innerText = conf.symbol + ' ' + convertedPrice.toFixed(2);
                }
            }

            // Update option prices
            document.querySelectorAll('.long-sleeve-price').forEach(el => {
                el.innerText = currentCurrency === 'IDR' ? conf.longSleeve.toLocaleString('id-ID') : conf.longSleeve.toFixed(2);
            });
            document.querySelectorAll('.nameset-price').forEach(el => {
                el.innerText = currentCurrency === 'IDR' ? conf.nameset.toLocaleString('id-ID') : conf.nameset.toFixed(2);
            });
        }

        function showStep(step) {
            if (step > currentStep) {
                if (currentStep === 1) {
                    // Step 1
                }
                if (currentStep === 2) {
                    // Step 2
                    const nameInput = document.querySelector('input[name="name"]');
                    const phoneInput = document.querySelector('input[name="phone"]');
                    const regionInput = document.querySelector('input[name="region"]');
                    const provinceInput = document.querySelector('input[name="province"]');
                    const cityInput = document.querySelector('input[name="city"]');
                    const postalInput = document.querySelector('input[name="postal_code"]');
                    const addressDetailInput = document.querySelector('textarea[name="address_detail"]');

                    if (!nameInput.value.trim()) { alert('Full name is required'); return; }
                    if (!phoneInput.value.trim()) { alert('Phone number is required'); return; }
                    if (!regionInput.value.trim()) { alert('Region is required'); return; }
                    if (!provinceInput.value.trim()) { alert('Country/Province is required'); return; }
                    if (!cityInput.value.trim()) { alert('City is required'); return; }
                    if (!postalInput.value.trim()) { alert('Postal code is required'); return; }
                    if (!addressDetailInput.value.trim()) { alert('Address detail is required'); return; }
                }
                if (currentStep === 3) {
                    // Step 3
                    let totalQ = 0;
                    document.querySelectorAll('.qty-input-display').forEach(inp => {
                        totalQ += parseInt(inp.value || 0);
                    });

                    if (totalQ === 0) {
                        alert('Please select at least one item quantity');
                        return;
                    }
                }
                if (currentStep === 4) {
                    // Step 4: Shipping
                    const courier = document.getElementById('input_shipping_courier_name').value;
                    if (!courier) {
                        alert('Please select a shipping method');
                        return;
                    }
                }
            }

            // Visual update
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            
            // Fix stepper visual update
            document.querySelectorAll('.stepper .step-item').forEach(el => el.classList.remove('active'));

            const targetStep = document.getElementById('step' + step);
            if (targetStep) {
                targetStep.classList.add('active');
                window.scrollTo(0, 0);
            }

            for (let i = 1; i <= step; i++) {
                const stepItem = document.querySelector(`.stepper .step-item[data-step="${i}"]`);
                if (stepItem) stepItem.classList.add('active');
            }

            currentStep = step;
            if (step === 4) {
                fetchShippingRates();
            }
            if (step === 5) {
                recalc();
                updateReviewDetails();
            }
        }

        function nextStep() {
            showStep(currentStep + 1);
        }

        function prevStep() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }

        function incQty(variantId, type) {
            const input = document.querySelector(`input[name="items[${variantId}][quantity_${type}]"]`);
            if (input) {
                let val = parseInt(input.value || 0);
                input.value = val + 1;
                handleQtyChange(variantId, type);
            }
        }

        function decQty(variantId, type) {
            const input = document.querySelector(`input[name="items[${variantId}][quantity_${type}]"]`);
            if (input) {
                let val = parseInt(input.value || 0);
                if (val > 0) {
                    input.value = val - 1;
                    handleQtyChange(variantId, type);
                }
            }
        }

        function toggleLS(variantId) {
            // Toggle Logic for LS
            const section = document.getElementById(`section-ls-${variantId}`);
            const btn = document.getElementById(`btn-add-ls-${variantId}`);
            const check = document.getElementById(`check-ls-${variantId}`); // Hidden checkbox for compatibility if needed

            if (section.style.display === 'none') {
                // Show
                section.style.display = 'block';
                btn.style.display = 'none';
                if (check) check.checked = true;
                // Set default qty to 1 if 0? Or let user start at 0
                // Let's set to 0. If they click add, maybe start at 1?
                // incQty(variantId, 'ls'); // Optional: start at 1
            } else {
                // Hide
                // Before hiding confirm? No, just reset.
                section.style.display = 'none';
                btn.style.display = 'block';
                if (check) check.checked = false;

                // Reset LS qty
                const lsInput = section.querySelector('.ls-input');
                if (lsInput) lsInput.value = 0;

                // Hide nameset
                toggleNameset(variantId, 'ls');

                handleQtyChange(variantId, 'ls');
            }
        }

        function handleQtyChange(variantId, type) {
            // type = 'ss' or 'ls'
            const qtyInput = document.querySelector(`input[name="items[${variantId}][quantity_${type}]"]`);
            const qty = parseInt(qtyInput.value || 0);

            // Show/Hide Nameset Toggle for this type
            const toggle = document.getElementById(`toggle-nameset-${type}-${variantId}`);
            if (toggle) {
                if (qty > 0) {
                    toggle.style.display = 'inline-flex';
                } else {
                    toggle.style.display = 'none';
                    // Also uncheck
                    const chmbk = document.getElementById(`check-nameset-${type}-${variantId}`);
                    if (chmbk && chmbk.checked) {
                        chmbk.checked = false;
                        toggleNameset(variantId, type);
                    }
                }
            }

            // Check if nameset is enabled to update inputs
            const nsCheck = document.getElementById(`check-nameset-${type}-${variantId}`);
            if (nsCheck && nsCheck.checked) {
                updateNamesetInputs(variantId, type, qty);
            }

            recalc();
        }

        function toggleNameset(variantId, type) {
            const checkbox = document.getElementById(`check-nameset-${type}-${variantId}`);
            const container = document.getElementById(`nameset-container-${type}-${variantId}`);
            const qtyInput = document.querySelector(`input[name="items[${variantId}][quantity_${type}]"]`);
            const qty = parseInt(qtyInput.value || 0);

            if (checkbox && checkbox.checked) {
                container.style.display = 'block';
                updateNamesetInputs(variantId, type, qty);
            } else {
                if (container) container.style.display = 'none';
                if (container) container.innerHTML = '';
            }
            recalc();
        }

        function updateNamesetInputs(variantId, type, qty) {
            const container = document.getElementById(`nameset-container-${type}-${variantId}`);
            if (!container) return;

            const currentRows = container.querySelectorAll('.nameset-row');
            const currentCount = currentRows.length;

            if (qty > currentCount) {
                // Add rows
                for (let i = currentCount; i < qty; i++) {
                    const row = document.createElement('div');
                    row.className = 'nameset-row';
                    // row.style.display = 'flex'; // handled by css
                    // row.style.gap = '0.5rem';
                    // row.style.marginBottom = '0.5rem';
                    row.innerHTML = `
                            <input type="text" name="items[${variantId}][namesets_${type}][${i}][key]" class="form-control" placeholder="Name #${i + 1}" required style="flex: 2; font-size: 0.9rem;">
                            <input type="text" name="items[${variantId}][namesets_${type}][${i}][value]" class="form-control" placeholder="Number" required style="flex: 1; font-size: 0.9rem;">
                        `;
                    container.appendChild(row);
                }
            } else if (qty < currentCount) {
                // Remove extra rows
                for (let i = currentCount - 1; i >= qty; i--) {
                    currentRows[i].remove();
                }
            }
        }

        function updateReviewDetails() {
            // Customer Details
            const name = document.querySelector('input[name="name"]').value || '-';
            const phone = document.querySelector('input[name="phone"]').value || '-';
            const region = document.querySelector('input[name="region"]').value || '';
            const province = document.querySelector('input[name="province"]').value || '';
            const city = document.querySelector('input[name="city"]').value || '';
            const postal = document.querySelector('input[name="postal_code"]').value || '';
            const addressDetail = document.querySelector('textarea[name="address_detail"]').value || '';
            const address = [addressDetail, city, province, (postal ? ('Postal ' + postal) : ''), region].filter(Boolean).join(', ');
            const customerDetailsEl = document.getElementById('reviewCustomerDetails');
            if (customerDetailsEl) {
                customerDetailsEl.innerHTML = `<div style="font-weight:700;">${name}</div><div style="font-size:0.9rem;color:#4b5563;">${phone}</div><div style="font-size:0.9rem;color:#4b5563;">${address}</div>`;
            }

            const itemsContainer = document.getElementById('reviewItemsList');
            itemsContainer.innerHTML = '';

            let hasItems = false;
            document.querySelectorAll('.variant-card').forEach(row => {
                const variantId = row.getAttribute('data-variant-id');
                const variantName = row.querySelector('.variant-title').textContent;

                // Check SS
                const qtySS = parseInt(row.querySelector(`input[name="items[${variantId}][quantity_ss]"]`).value || 0);
                if (qtySS > 0) {
                    hasItems = true;
                    const nsSS = row.querySelector(`#check-nameset-ss-${variantId}`).checked;
                    let details = ['Short Sleeve'];
                    if (nsSS) details.push('Nameset Included');

                    addItemToReview(itemsContainer, variantName, qtySS, details);
                }

                // Check LS
                const qtyLS = parseInt(row.querySelector(`input[name="items[${variantId}][quantity_ls]"]`).value || 0);
                if (qtyLS > 0) {
                    hasItems = true;
                    const nsLS = row.querySelector(`#check-nameset-ls-${variantId}`).checked;
                    let details = ['Long Sleeve'];
                    if (nsLS) details.push('Nameset Included');

                    addItemToReview(itemsContainer, variantName, qtyLS, details);
                }
            });

            // Add Shipping Row
            if (shippingCost > 0) {
                const courierName = document.getElementById('input_shipping_courier_name').value;
                const serviceName = document.getElementById('input_shipping_service_name').value;
                
                // Estimate converted shipping cost for display if needed, or just show RM
                // For clarity, let's show the converted amount that contributes to total
                const conf = currencies[currentCurrency];
                const displayShipping = shippingCost * conf.rate;
                const symbol = conf.symbol;
                const formattedShipping = currentCurrency === 'IDR' ? Math.round(displayShipping).toLocaleString('id-ID') : displayShipping.toFixed(2);

                const shippingDiv = document.createElement('div');
                shippingDiv.style.marginBottom = '1rem';
                shippingDiv.style.paddingBottom = '1rem';
                shippingDiv.style.borderBottom = '1px solid #f1f5f9';
                shippingDiv.innerHTML = `
                    <div style="display:flex; justify-content:space-between; font-weight:600; color: #0f172a;">
                        <span>Shipping: ${courierName}</span>
                        <span>${symbol} ${formattedShipping}</span>
                    </div>
                    <div style="font-size:0.85rem; color:#64748b; margin-top:0.25rem;">${serviceName}</div>
                `;
                itemsContainer.appendChild(shippingDiv);
            }

            if (!hasItems) itemsContainer.innerHTML = '<div style="color:#64748b; font-style:italic;">No items selected</div>';
        }

        function addItemToReview(container, name, qty, details) {
            const itemDiv = document.createElement('div');
            itemDiv.style.marginBottom = '1rem';
            itemDiv.style.paddingBottom = '1rem';
            itemDiv.style.borderBottom = '1px solid #f1f5f9';
            itemDiv.innerHTML = `
                    <div style="display:flex; justify-content:space-between; font-weight:600;">
                        <span>${name}</span>
                        <span>x${qty}</span>
                    </div>
                    ${details.length ? '<div style="font-size:0.85rem; color:#64748b; margin-top:0.25rem;">' + details.map(d => '• ' + d).join('<br>') + '</div>' : ''}
                `;
            container.appendChild(itemDiv);
        }

        function recalc() {
            if (!currencies[currentCurrency]) return;
            const conf = currencies[currentCurrency];

            let grandTotal = 0;

            document.querySelectorAll('.variant-card').forEach(row => {
                const variantId = row.getAttribute('data-variant-id');
                const qtySS = parseInt(row.querySelector(`input[name="items[${variantId}][quantity_ss]"]`).value || 0);
                const qtyLS = parseInt(row.querySelector(`input[name="items[${variantId}][quantity_ls]"]`).value || 0);

                const base = basePrice * conf.rate;

                // Calculate SS
                if (qtySS > 0) {
                    let cost = base * qtySS;
                    const nsSS = row.querySelector(`#check-nameset-ss-${variantId}`).checked;
                    if (nsSS) cost += (conf.nameset * qtySS);
                    grandTotal += cost;
                }

                // Calculate LS
                if (qtyLS > 0) {
                    let cost = (base + conf.longSleeve) * qtyLS;
                    const nsLS = row.querySelector(`#check-nameset-ls-${variantId}`).checked;
                    if (nsLS) cost += (conf.nameset * qtyLS);
                    grandTotal += cost;
                }
            });

            // Add Shipping Cost
            if (shippingCost > 0) {
                grandTotal += (shippingCost * conf.rate);
            }

            // Update Summary
            const el = document.getElementById('summaryTotal');
            if (el) {
                if (currentCurrency === 'IDR') el.innerText = conf.symbol + ' ' + Math.round(grandTotal).toLocaleString('id-ID');
                else el.innerText = conf.symbol + ' ' + grandTotal.toFixed(2);
            }
        }

        function fetchShippingRates() {
            const loader = document.getElementById('shipping-loader');
            const list = document.getElementById('shipping-rates-list');
            const errorDiv = document.getElementById('shipping-error');
            const btnNext = document.getElementById('btn-shipping-next');
            
            loader.style.display = 'block';
            list.style.display = 'none';
            errorDiv.style.display = 'none';
            list.innerHTML = '';
            btnNext.disabled = true;

            // Gather data
            const postcode = document.querySelector('input[name="postal_code"]').value;
            const state = document.querySelector('input[name="province"]').value;
            const country = 'MY'; // Default to Malaysia for now, or derive from province/input if possible. EasyParcel usually requires country code.
            // Note: If province/country logic is complex, might need mapping. For now assuming MY.
            
            // Gather items for weight calculation
            let items = [];
            document.querySelectorAll('.variant-card').forEach(row => {
                const variantId = row.getAttribute('data-variant-id');
                const qtySS = parseInt(row.querySelector(`input[name="items[${variantId}][quantity_ss]"]`).value || 0);
                const qtyLS = parseInt(row.querySelector(`input[name="items[${variantId}][quantity_ls]"]`).value || 0);
                
                if (qtySS > 0) items.push({ quantity: qtySS });
                if (qtyLS > 0) items.push({ quantity: qtyLS });
            });

            fetch('{{ route("shipping.rates") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    postcode: postcode,
                    state: state,
                    country: country,
                    items: items
                })
            })
            .then(res => res.json())
            .then(data => {
                loader.style.display = 'none';
                if (data.success && data.rates.length > 0) {
                    list.style.display = 'grid';
                    renderShippingRates(data.rates);
                } else {
                    errorDiv.innerText = data.message || 'No shipping rates available for your location.';
                    errorDiv.style.display = 'block';
                }
            })
            .catch(err => {
                loader.style.display = 'none';
                errorDiv.innerText = 'Failed to load shipping rates. Please try again.';
                errorDiv.style.display = 'block';
                console.error(err);
            });
        }

        function renderShippingRates(rates) {
            const list = document.getElementById('shipping-rates-list');
            rates.forEach(rate => {
                const card = document.createElement('div');
                card.className = 'shipping-card';
                card.onclick = () => selectShipping(card, rate);
                
                const logoHtml = rate.courier_logo ? `<img src="${rate.courier_logo}" alt="${rate.courier_name}">` : '';
                
                card.innerHTML = `
                    ${logoHtml}
                    <div style="font-weight: 600;">${rate.courier_name}</div>
                    <div style="font-size: 0.9rem; color: #64748b;">${rate.service_name}</div>
                    <div style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;">Est: ${rate.delivery_period}</div>
                    <div style="margin-top: 0.5rem; font-weight: 700; color: #111827;">
                        RM ${parseFloat(rate.price).toFixed(2)}
                    </div>
                `;
                list.appendChild(card);
            });
        }

        function selectShipping(cardElement, rate) {
            // UI
            document.querySelectorAll('.shipping-card').forEach(c => c.classList.remove('selected'));
            cardElement.classList.add('selected');
            
            // Enable Next
            document.getElementById('btn-shipping-next').disabled = false;

            // Set inputs
            document.getElementById('input_shipping_courier_name').value = rate.courier_name;
            document.getElementById('input_shipping_courier_logo').value = rate.courier_logo || '';
            document.getElementById('input_shipping_service_name').value = rate.service_name;
            document.getElementById('input_shipping_service_id').value = rate.service_id;
            document.getElementById('input_shipping_cost').value = rate.price;

            // Update cost variable
            shippingCost = parseFloat(rate.price);
        }

        function submitForm() {
            const form = document.getElementById('preorderForm');

            // precise validation check
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            let paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
            let paymentMethod = paymentMethodInput ? paymentMethodInput.value : 'cod';

            if (paymentMethod === 'stripe') {
                form.action = '{{ route("preorder.checkout.stripe") }}';
            } else {
                form.action = '{{ route($product->available_for_preorder ? "preorder.store" : "order.store") }}';
            }
            form.submit();
        }

        function updatePaymentMethod(val) {
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.style.borderColor = '#e2e8f0';
                opt.style.backgroundColor = '';
            });
            const selected = document.querySelector(`input[value="${val}"]`).closest('.payment-option');
            if (selected) {
                selected.style.borderColor = '#111827';
                selected.style.backgroundColor = '#f9fafb';
            }
        }

        // Initialize
        updateCurrencyDisplay();
        recalc();

        // Check for errors and restore step
        document.addEventListener("DOMContentLoaded", function () {
            const hasStep2Errors = {!! $errors->hasAny(['name','email','phone','region','province','city','postal_code','address_detail']) ? 'true' : 'false' !!};
            const hasStep3Errors = {!! ($errors->has('items') || collect($errors->keys())->contains(fn($k) => str_contains($k, 'items.'))) ? 'true' : 'false' !!};
            if (hasStep2Errors) {
                showStep(2);
            } else if (hasStep3Errors) {
                showStep(3);
            }
        });
    </script>
@endsection
