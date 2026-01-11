@extends('layouts.public')

@section('title', 'Pre-order - Complete Details')

 

@section('content')
    <section class="preorder-container">
        <!-- Currency Selector -->
        <div class="currency-bar">
            <div class="currency-select">
                <label>Currency:</label>
                <select id="currencySelector">
                    <option value="MYR" selected>RM (Malaysia)</option>
                    <option value="BND">$ (Brunei)</option>
                    <option value="IDR">Rp (Indonesia)</option>
                </select>
            </div>
        </div>

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
                <span class="step-label">Review</span>
            </div>
        </div>

        <div class="preorder-layout">
            <!-- Product Summary Card -->
            <div class="product-summary-card">
                <div class="product-image-wrapper" id="orderGallery">
                    @php
                        $gallery = [];
                        if ($product->image_path) { $gallery[] = $product->image_path; }
                        foreach ($product->images as $img) { $gallery[] = $img->path; }
                    @endphp
                    @if(count($gallery))
                        <div style="position:relative; width:100%;">
                            <img id="orderMainImg" src="{{ asset('storage/'.$gallery[0]) }}" alt="{{ $product->name }}" style="max-width:100%; max-height:280px; object-fit:contain; border-radius:0.5rem;" />
                            <div style="display:flex; gap:0.5rem; margin-top:0.5rem; flex-wrap:wrap;">
                                @foreach($gallery as $i => $path)
                                    <img data-index="{{ $i }}" src="{{ asset('storage/'.$path) }}" alt="thumb {{ $i+1 }}" style="width:56px; height:56px; object-fit:cover; border-radius:0.375rem; border:1px solid #e2e8f0; cursor:pointer; opacity: 0.6;">
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
                        <div class="price" id="basePriceDisplay">RM {{ number_format($product->price, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Multi-Step Form -->
            <div class="form-card">
                <form method="POST" action="{{ route($product->available_for_preorder ? 'preorder.store' : 'order.store') }}" id="preorderForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    <input type="hidden" name="currency" id="currencyInput" value="MYR" />

                    <!-- Step 1: Product Confirmation -->
                    <div class="form-step active" id="step1">
                        <h3 class="step-title"><span class="icon">1</span> Confirm Product</h3>

                        <div class="review-block">
                            <div class="label">Selected Product</div>
                            <div class="value">{{ $product->name }}</div>
                        </div>

                        <div class="review-block">
                            <div class="label">Price</div>
                            <div class="value" id="step1Price">RM {{ number_format($product->price, 2) }}</div>
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
                            <textarea name="address" class="form-control" required
                                placeholder="Enter your full address for delivery">{{ old('address') }}</textarea>
                            @error('address')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-nav">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">← Back</button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Continue →</button>
                        </div>
                    </div>

                    <!-- Step 3: Customization -->
                    <div class="form-step" id="step3">
                        <h3 class="step-title"><span class="icon">3</span> Customize Your Jersey</h3>

                        <div class="form-group">
                            <label>Size <span class="required">*</span></label>
                            <select name="size" class="form-control" required>
                                <option value="">-- Select Size --</option>
                                <option value="S">S</option>
                                <option value="M" selected>M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quantity <span class="required">*</span></label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" id="quantity"
                                required />
                        </div>

                        <label class="form-check">
                            <input type="checkbox" name="long_sleeve" value="1" id="longSleeve" />
                            <span class="form-check-label">Long Sleeve</span>
                            <span class="form-check-price" id="longSleevePrice">+RM 3</span>
                        </label>

                        <!-- Dynamic Custom Fields Section -->
                        <div class="dynamic-fields-section">
                            <div class="dynamic-fields-header">
                                <h4>🏷️ Jersey Customization (Optional)</h4>
                                <button type="button" class="btn-add-field" onclick="addNamesetRow()">+ Add Nameset</button>
                            </div>
                            <div id="customFieldsContainer">
                                <!-- Dynamic fields will be added here -->
                            </div>
                            <p class="dynamic-fields-hint">Add custom details like Jersey Name, Jersey Number, or any other
                                request. Adding nameset (name/number) adds <span id="namesetPriceHint">RM 13</span> to the total.</p>
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

                    <!-- Step 4: Review -->
                    <div class="form-step" id="step4">
                        <h3 class="step-title"><span class="icon">✓</span> Review Your Order</h3>

                        <div class="review-block">
                            <div class="label">Product</div>
                            <div class="value">{{ $product->name }}</div>
                        </div>

                        <div class="review-block">
                            <div class="label">Customer</div>
                            <div class="value" id="reviewCustomerName">-</div>
                        </div>

                        <div class="review-block">
                            <div class="label">Quantity</div>
                            <div class="value" id="reviewQuantity">1</div>
                        </div>

                        <div class="review-block" id="reviewCustomFieldsBlock" style="display: none;">
                            <div class="label">Customizations</div>
                            <div class="value" id="reviewCustomFields">-</div>
                        </div>

                        <div class="review-total">
                            <div class="label">Total Amount</div>
                            <div class="price" id="reviewTotal">RM {{ number_format($product->price, 2) }}</div>
                        </div>

                        <div class="payment-method-selection" style="margin: 1.5rem 0;">
                            <h4 style="margin-bottom: 1rem; font-size: 1.1rem;">Select Payment Method</h4>
                            
                            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                                <label class="payment-option" id="payment-cod" style="flex: 1; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
                                    <input type="radio" name="payment_method" value="cod" checked style="margin-right: 0.5rem;">
                                    <span style="font-weight: 500;">💵 Cash on Delivery (COD)</span>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #64748b;">Pay when you receive the order</p>
                                </label>
                                
                                <label class="payment-option" id="payment-stripe" style="flex: 1; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
                                    <input type="radio" name="payment_method" value="stripe" style="margin-right: 0.5rem;">
                                    <span style="font-weight: 500;">💳 Credit/Debit Card (Stripe)</span>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #64748b;">Pay securely with Stripe</p>
                                </label>
                            </div>
                        </div>

                        <div class="form-nav">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">← Back</button>
                            <button type="button" class="btn btn-success" onclick="handlePreorderSubmit()">✓ Continue to Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        (function(){
            const g = document.getElementById('orderGallery');
            if (g) {
                const main = document.getElementById('orderMainImg');
                const thumbs = Array.from(g.querySelectorAll('img[data-index]'));
                let idx = 0;
                if (thumbs.length) {
                    thumbs[0].style.opacity = '1';
                    thumbs[0].style.borderColor = '#111827';
                }
                function setIdx(i){
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
            MYR: { symbol: 'RM', rate: 1, longSleeve: 3, nameset: 13 },
            BND: { symbol: '$', rate: 1.05, longSleeve: 3, nameset: 13 },
            IDR: { symbol: 'Rp', rate: 5200, longSleeve: 15600, nameset: 67600 }
        };

        let currentCurrency = '{{ $currency }}';
        let currentStep = 1;
        const totalSteps = 4;
        const basePrice = parseFloat('{{ number_format($product->price, 2, ".", "") }}');
        let customFieldIndex = 0;

        // Currency selector
        const currencySelector = document.getElementById('currencySelector');
        currencySelector.addEventListener('change', function () {
            currentCurrency = this.value;
            document.getElementById('currencyInput').value = currentCurrency;
            updateCurrencyDisplay();
            recalc();
        });

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

            document.getElementById('basePriceDisplay').textContent = formatPrice(basePrice);
            document.getElementById('step1Price').textContent = formatPrice(basePrice);
            document.getElementById('longSleevePrice').textContent = '+' + formatPrice(config.longSleeve / config.rate);
            document.getElementById('namesetPriceHint').textContent = formatPrice(config.nameset / config.rate);
        }

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');

            // Update stepper UI
            document.querySelectorAll('.step-item').forEach(el => {
                const stepNum = parseInt(el.getAttribute('data-step'));
                el.classList.remove('active', 'completed');
                if (stepNum < step) {
                    el.classList.add('completed');
                } else if (stepNum === step) {
                    el.classList.add('active');
                }
            });

            // Update connectors
            document.querySelectorAll('.step-connector').forEach((el, idx) => {
                if (idx < step - 1) {
                    el.classList.add('completed');
                } else {
                    el.classList.remove('completed');
                }
            });

            currentStep = step;
            recalc();
            updateReview();
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                // Validate current step
                if (currentStep === 2) {
                    const nameInput = document.querySelector('input[name="name"]');
                    const phoneInput = document.querySelector('input[name="phone"]');
                    const addressInput = document.querySelector('textarea[name="address"]');

                    if (!nameInput.value.trim()) {
                        alert('Full name is required');
                        nameInput.focus();
                        return;
                    }
                    if (!phoneInput.value.trim()) {
                        alert('Phone number is required');
                        phoneInput.focus();
                        return;
                    }
                    if (!addressInput.value.trim()) {
                        alert('Delivery address is required');
                        addressInput.focus();
                        return;
                    }
                }
                showStep(currentStep + 1);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }

        // Dynamic Custom Fields
        function addNamesetRow() {
            const container = document.getElementById('customFieldsContainer');
            const row = document.createElement('div');
            row.className = 'dynamic-field-row';
            row.innerHTML = `
                    <input type="hidden" name="custom_fields[${customFieldIndex}][key]" value="Jersey Name" />
                    <input type="text" name="custom_fields[${customFieldIndex}][value]" placeholder="e.g., WILDAN" required />
                    <input type="hidden" name="custom_fields[${customFieldIndex + 1}][key]" value="Jersey Number" />
                    <input type="text" name="custom_fields[${customFieldIndex + 1}][value]" placeholder="e.g., 91" required />
                    <button type="button" class="btn-remove-field" onclick="removeCustomField(this)">×</button>
                `;
            container.appendChild(row);
            customFieldIndex += 2;
            recalc();
        }

        function getRowValues(row) {
            const values = Array.from(row.querySelectorAll('input[name*="[value]"]')).map(i => i.value.trim());
            return { name: values[0] || '', number: values[1] || '' };
        }

        function removeCustomField(btn) {
            const row = btn.closest('.dynamic-field-row');
            if (row) row.remove();
            recalc();
        }

        // Price calculation
        const qty = document.getElementById('quantity');
        const longSleeve = document.getElementById('longSleeve');

        function getCustomFieldsCount() {
            return document.querySelectorAll('#customFieldsContainer .dynamic-field-row').length;
        }

        function recalc() {
            const config = currencies[currentCurrency];
            let unit = basePrice * config.rate;

            if (longSleeve && longSleeve.checked) unit += config.longSleeve;

            // Add nameset price if custom fields exist
            const customCount = getCustomFieldsCount();
            if (customCount > 0) {
                unit += config.nameset;
            }

            const q = Math.max(1, parseInt(qty ? qty.value : 1));
            const total = unit * q;

            const reviewTotal = document.getElementById('reviewTotal');
            if (reviewTotal) {
                if (currentCurrency === 'IDR') {
                    reviewTotal.textContent = getCurrencySymbol() + ' ' + Math.round(total).toLocaleString('id-ID');
                } else {
                    reviewTotal.textContent = getCurrencySymbol() + ' ' + total.toFixed(2);
                }
            }
        }

        function updateReview() {
            // Update customer name
            const nameInput = document.querySelector('input[name="name"]');
            document.getElementById('reviewCustomerName').textContent = nameInput.value || '-';

            // Update quantity
            document.getElementById('reviewQuantity').textContent = qty.value || '1';

            const customFieldRows = document.querySelectorAll('#customFieldsContainer .dynamic-field-row');
            const customFieldsBlock = document.getElementById('reviewCustomFieldsBlock');
            const customFieldsValue = document.getElementById('reviewCustomFields');

            if (customFieldRows.length > 0) {
                customFieldsBlock.style.display = 'block';
                const items = [];
                customFieldRows.forEach(row => {
                    const vals = getRowValues(row);
                    if (vals.name) items.push(`Jersey Name: ${vals.name}`);
                    if (vals.number) items.push(`Jersey Number: ${vals.number}`);
                });
                customFieldsValue.textContent = items.join(', ') || '-';
            } else {
                customFieldsBlock.style.display = 'none';
            }
        }

        if (longSleeve) longSleeve.addEventListener('change', recalc);
        if (qty) qty.addEventListener('input', recalc);

        // Payment method selection styling
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.style.borderColor = '#e2e8f0';
                    opt.style.backgroundColor = '';
                });
                const selected = document.getElementById('payment-' + this.value);
                if (selected) {
                    selected.style.borderColor = '#111827';
                    selected.style.backgroundColor = '#f9fafb';
                }
            });
        });
        // Initialize first option
        document.getElementById('payment-cod').style.borderColor = '#111827';
        document.getElementById('payment-cod').style.backgroundColor = '#f9fafb';

        // Handle preorder submission with payment method
        function handlePreorderSubmit() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const form = document.getElementById('preorderForm');
            
            // Validate required fields before proceeding
            if (currentStep === 4) {
                const nameInput = document.querySelector('input[name="name"]');
                const phoneInput = document.querySelector('input[name="phone"]');
                const addressInput = document.querySelector('textarea[name="address"]');
                const sizeInput = document.querySelector('select[name="size"]');

                if (!nameInput || !nameInput.value.trim()) {
                    alert('Full name is required');
                    showStep(2);
                    nameInput.focus();
                    return;
                }
                if (!phoneInput || !phoneInput.value.trim()) {
                    alert('Phone number is required');
                    showStep(2);
                    phoneInput.focus();
                    return;
                }
                if (!addressInput || !addressInput.value.trim()) {
                    alert('Delivery address is required');
                    showStep(2);
                    addressInput.focus();
                    return;
                }
                if (!sizeInput || !sizeInput.value) {
                    alert('Size is required');
                    showStep(3);
                    sizeInput.focus();
                    return;
                }
            }
            
            if (paymentMethod === 'stripe') {
                // For Stripe, submit to Stripe checkout endpoint
                form.action = '{{ route("preorder.checkout.stripe") }}';
                form.submit();
            } else {
                // For COD, submit normally to store endpoint
                form.action = '{{ route($product->available_for_preorder ? "preorder.store" : "order.store") }}';
                form.submit();
            }
        }

        // Initial setup
        updateCurrencyDisplay();
        recalc();
    </script>
@endsection
