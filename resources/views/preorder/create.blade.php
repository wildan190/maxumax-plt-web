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
                                <label>Select Variants & Quantity <span class="required">*</span></label>
                                
                                <div class="variant-list" style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem;">
                                    @foreach($product->variants as $variant)
                                        <div class="variant-row" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                                            <div class="variant-info">
                                                <div style="font-weight: 600; font-size: 1rem;">{{ $variant->name }}</div>
                                                @if(!$product->available_for_preorder)
                                                    <div style="font-size: 0.75rem; color: {{ $variant->hasStock() ? '#6b7280' : '#dc2626' }};">
                                                        {{ $variant->hasStock() ? 'Stock: ' . $variant->stock : 'Out of Stock' }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="variant-input" style="width: 100px;">
                                                <input type="number" 
                                                       name="items[{{ $variant->id }}]" 
                                                       class="form-control quantity-input" 
                                                       value="0" 
                                                       min="0" 
                                                       {{ (!$product->available_for_preorder && !$variant->hasStock()) ? 'disabled' : '' }}
                                                       data-price="{{ $product->price }}"
                                                       data-variant-id="{{ $variant->id }}"
                                                       style="text-align: center;"
                                                       onchange="recalc()"
                                                       onkeyup="recalc()"
                                                >
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="totalQtyDisplay" style="margin-top: 0.5rem; font-size: 0.875rem; color: #64748b; text-align: right;">Total Items: 0</div>
                            </div>

                            <div class="form-group">
                                <label class="checkbox-label" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="checkbox" name="long_sleeve" value="1" id="longSleeveCheck" onchange="recalc()">
                                    <span>Long Sleeve (+<span class="currency-symbol">RM</span> <span class="long-sleeve-price">0</span>)</span>
                                </label>
                            </div>
                            
                            <!-- Nameset/Customization Section -->
                            <div style="margin-top: 1rem; background: #f8fafc; padding: 1rem; border-radius: 0.5rem; border: 1px dashed #cbd5e1;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <label style="margin:0; font-weight:600; font-size: 0.9rem;">Add Name & Number?</label>
                                    <span style="font-size: 0.8rem; color: #64748b;">+<span class="currency-symbol">RM</span> <span class="nameset-price">0</span> / pcs</span>
                                </div>
                                <div id="customFieldsContainer">
                                    <!-- Dynamic fields will be added here -->
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="addCustomField()" style="width: 100%; margin-top: 0.5rem;">+ Add Name & Number</button>
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
                            <div class="label">Customer Details</div>
                            <div class="value" id="reviewCustomerDetails">-</div>
                        </div>

                        <div class="review-block">
                            <div class="label">Items Selected</div>
                            <div id="reviewItemsList" style="font-size: 0.95rem; font-weight: 500; color: #111827;">
                                <!-- Populated by JS -->
                            </div>
                        </div>

                        <div class="review-block" id="reviewNamesetBlock" style="display: none;">
                            <div class="label">Customizations</div>
                            <div class="value" id="reviewNamesetList">-</div>
                        </div>

                        <div class="review-total" style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Base Price</span>
                                <span id="summaryBasePrice">-</span>
                            </div>
                            <div id="summaryLongSleeveRow" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; display: none;">
                                <span>Long Sleeve Surcharge</span>
                                <span id="summaryLongSleeve">-</span>
                            </div>
                            <div id="summaryNamesetRow" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; display: none;">
                                <span>Custom Nameset</span>
                                <span id="summaryNameset">-</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px dashed #cbd5e1; font-weight: 700; font-size: 1.1rem; color: #111827;">
                                <span>Total Amount</span>
                                <span id="summaryTotal">-</span>
                            </div>
                        </div>

                        <div class="payment-method-selection" style="margin: 1.5rem 0;">
                            <h4 style="margin-bottom: 1rem; font-size: 1.1rem;">Select Payment Method</h4>
                            
                            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                                <label class="payment-option" id="payment-cod" style="flex: 1; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
                                    <input type="radio" name="payment_method" value="cod" checked style="margin-right: 0.5rem;" onchange="updatePaymentMethod(this.value)">
                                    <span style="font-weight: 500;">💵 Cash on Delivery (COD)</span>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #64748b;">Pay when you receive the order</p>
                                </label>
                                
                                <label class="payment-option" id="payment-stripe" style="flex: 1; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
                                    <input type="radio" name="payment_method" value="stripe" style="margin-right: 0.5rem;" onchange="updatePaymentMethod(this.value)">
                                    <span style="font-weight: 500;">💳 Credit/Debit Card (Stripe)</span>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #64748b;">Pay securely with Stripe</p>
                                </label>
                            </div>
                        </div>

                        <div class="form-nav">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">← Back</button>
                            <button type="button" class="btn btn-success" onclick="submitForm()">✓ Continue to Payment</button>
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

    // Currency selector
    const currencySelector = document.getElementById('currencySelector');
    if (currencySelector) {
        currencySelector.addEventListener('change', function () {
            currentCurrency = this.value;
            const currencyInput = document.getElementById('currencyInput');
            if (currencyInput) currencyInput.value = currentCurrency;
            updateCurrencyDisplay();
            recalc();
        });
    }

    function updateCurrencyDisplay() {
        if (!currencies[currentCurrency]) return;
        const conf = currencies[currentCurrency];
        
        document.querySelectorAll('.currency-symbol').forEach(el => el.innerText = conf.symbol);
        
        // Update price display on product card
        const convertedPrice = basePrice * conf.rate;
        const priceElement = document.getElementById('displayPrice');
        if(priceElement) {
            if(currentCurrency === 'IDR') {
                priceElement.innerText = Math.round(convertedPrice).toLocaleString('id-ID');
            } else {
                priceElement.innerText = convertedPrice.toFixed(2);
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
                // Step 1: Product Card - No inputs to validate
            }
            if (currentStep === 2) {
                 // Step 2: Details
                 const nameInput = document.querySelector('input[name="name"]');
                 const phoneInput = document.querySelector('input[name="phone"]');
                 const addressInput = document.querySelector('textarea[name="address"]');
                 
                 if (!nameInput.value.trim()) { alert('Full name is required'); return; }
                 if (!phoneInput.value.trim()) { alert('Phone number is required'); return; }
                 if (!addressInput.value.trim()) { alert('Delivery address is required'); return; }
            }
            if (currentStep === 3) {
                // Step 3: Variants
                let totalQ = 0;
                document.querySelectorAll('.quantity-input').forEach(inp => {
                    totalQ += parseInt(inp.value || 0);
                });
                
                if (totalQ === 0) {
                    alert('Please select at least one item quantity');
                    return;
                }
            }
        }
        
        // Navigation visual update
        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
        
        const targetStep = document.getElementById('step' + step);
        if (targetStep) {
            targetStep.classList.add('active');
            window.scrollTo(0, 0);
        }
        
        // Update header steps
        for(let i=1; i<=step; i++) {
            const stepEl = document.querySelector('.steps .step:nth-child('+i+')');
            if(stepEl) stepEl.classList.add('active');
        }
        
        currentStep = step;
        if(step === 4) {
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

    function addCustomField() {
        const container = document.getElementById('customFieldsContainer');
        const count = container.querySelectorAll('.custom-field-row').length;
        
        const div = document.createElement('div');
        div.className = 'custom-field-row';
        div.style.display = 'flex';
        div.style.gap = '0.5rem';
        div.style.marginBottom = '0.5rem';
        div.innerHTML = `
            <input type="text" name="custom_fields[${count}][key]" class="form-control" placeholder="Name (e.g. RONALDO)" required style="flex: 2;">
            <input type="text" name="custom_fields[${count}][value]" class="form-control" placeholder="Number (e.g. 7)" required style="flex: 1;">
            <button type="button" class="btn btn-secondary" onclick="this.parentElement.remove(); recalc();" style="color: #ef4444; border-color: #ef4444; padding: 0.5rem;">×</button>
        `;
        container.appendChild(div);
        recalc();
    }

    function updateReviewDetails() {
        // Customer Details
        const name = document.querySelector('input[name="name"]').value || '-';
        const phone = document.querySelector('input[name="phone"]').value || '-';
        const address = document.querySelector('textarea[name="address"]').value || '-';
        const customerHtml = `
            <div style="font-weight: 700;">${name}</div>
            <div style="font-size: 0.9rem; color: #4b5563;">${phone}</div>
            <div style="font-size: 0.9rem; color: #4b5563;">${address}</div>
        `;
        const customerDetailsEl = document.getElementById('reviewCustomerDetails');
        if(customerDetailsEl) customerDetailsEl.innerHTML = customerHtml;

        // Items List
        const itemsContainer = document.getElementById('reviewItemsList');
        if(itemsContainer) {
            itemsContainer.innerHTML = '';
            const variantInputs = document.querySelectorAll('.quantity-input');
            let hasItems = false;
            
            variantInputs.forEach(inp => {
                const qty = parseInt(inp.value || 0);
                if (qty > 0) {
                   hasItems = true;
                   const row = document.createElement('div');
                   const variantRow = inp.closest('.variant-row');
                   const variantName = variantRow ? variantRow.querySelector('.variant-info div').textContent.trim() : 'Variant';
                   
                   row.style.marginBottom = '0.5rem';
                   row.style.display = 'flex';
                   row.style.justifyContent = 'space-between';
                   row.innerHTML = `<span>${variantName}</span> <span>x${qty}</span>`;
                   itemsContainer.appendChild(row);
                }
            });
            
            if (!hasItems) itemsContainer.innerHTML = '<div>No items selected</div>';
        }
        
        // Custom Fields (Nameset)
        const customRows = document.querySelectorAll('.custom-field-row');
        const namesetList = document.getElementById('reviewNamesetList');
        const namesetBlock = document.getElementById('reviewNamesetBlock');
        
        if (customRows.length > 0) {
            if(namesetBlock) namesetBlock.style.display = 'block';
            let html = '<div style="display:flex; flex-direction:column; gap:0.25rem;">';
            customRows.forEach(row => {
                const k = row.querySelector('input[name*="[key]"]').value;
                const v = row.querySelector('input[name*="[value]"]').value;
                if (k || v) {
                    html += `<div style="display:flex; justify-content:space-between; font-size:0.9rem;">
                        <span style="color:#64748b">${k || 'Custom'}</span>
                        <span style="font-weight:600">${v || '-'}</span>
                    </div>`;
                }
            });
            html += '</div>';
            if(namesetList) namesetList.innerHTML = html;
        } else {
            if(namesetBlock) namesetBlock.style.display = 'none';
        }
    }

    function recalc() {
        if (!currencies[currentCurrency]) return;
        const conf = currencies[currentCurrency];
        
        let totalQty = 0;
        document.querySelectorAll('.quantity-input').forEach(inp => {
            totalQty += parseInt(inp.value || 0);
        });
        
        const totalQtyDisplay = document.getElementById('totalQtyDisplay');
        if (totalQtyDisplay) totalQtyDisplay.innerText = 'Total Items: ' + totalQty;
        
        let totalBase = (basePrice * conf.rate) * totalQty;
        
        const longSleeveCheck = document.getElementById('longSleeveCheck');
        const isLongSleeve = longSleeveCheck ? longSleeveCheck.checked : false;
        let totalLongSleeve = isLongSleeve ? (conf.longSleeve * totalQty) : 0;
        
        const customRows = document.querySelectorAll('.custom-field-row').length;
        let totalNameset = customRows * conf.nameset;
        
        let grandTotal = totalBase + totalLongSleeve + totalNameset;
        
        // Update Summary
        const updateText = (id, val) => {
            const el = document.getElementById(id);
            if (el) {
                if (currentCurrency === 'IDR') el.innerText = Math.round(val).toLocaleString('id-ID');
                else el.innerText = val.toFixed(2);
            }
        };
        
        updateText('summaryBasePrice', totalBase);
        updateText('summaryLongSleeve', totalLongSleeve);
        updateText('summaryNameset', totalNameset);
        updateText('summaryTotal', grandTotal);
        
        // Visibility
        const lsRow = document.getElementById('summaryLongSleeveRow');
        if(lsRow) lsRow.style.display = isLongSleeve ? 'flex' : 'none';
        
        const nsRow = document.getElementById('summaryNamesetRow');
        if(nsRow) nsRow.style.display = totalNameset > 0 ? 'flex' : 'none';
        
        // Also update hidden quantity for simplicity if backend expects "quantity" somewhere?
        // No, backend expects "items" array.
    }

    function submitForm() {
        const form = document.getElementById('preorderForm');
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
    </script>
@endsection
