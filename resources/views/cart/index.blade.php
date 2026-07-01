@extends('layouts.public')

@section('title', 'Your Collection - Maxumax')

@section('content')
    <div class="bg-white min-h-screen py-12 px-6">
        <div style="max-width: 1280px; margin: 0 auto;">
            <!-- Header section -->
            <div class="flex flex-col md:flex-row items-end justify-between mb-10 gap-6 animate-fade-in">
                <div>
                    <span class="text-[#155EEF] font-black uppercase tracking-[0.3em] text-[10px] mb-3 inline-block">Order
                        Configuration</span>
                    <h1 class="text-2xl md:text-5xl font-black text-[#111111] tracking-tighter uppercase italic leading-[0.9]">
                        Review <span class="text-[#999999]">Inventory.</span>
                    </h1>
                </div>
                @if(count($items))
                    <div class="flex items-center gap-3 border-l border-[#E8E8E3] pl-4 pb-1">
                        <span class="text-[#666666] font-black text-[9px] uppercase tracking-widest">Active Batch</span>
                        <span class="text-[#111111] font-black text-lg italic">{{ count($items) }} Units</span>
                    </div>
                @endif
            </div>

            <!-- Notifications -->
            @if(session('success') || session('error') || $errors->any())
                <div class="mb-10 space-y-3 animate-fade-in">
                    @if(session('success'))
                        <div
                            class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl flex items-center gap-2 font-bold text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error') || $errors->any())
                        <div
                            class="p-3 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl font-bold text-xs space-y-2">
                            @if(session('error'))
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if($errors->any())
                                <ul class="list-disc list-inside opacity-80">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            @if(!count($items))
                <div class="py-24 text-center border-2 border-dashed border-[#E8E8E3] rounded-2xl animate-fade-in">
                    <div
                        class="w-24 h-24 bg-[#F7F7F5] rounded-full flex items-center justify-center text-[#E8E8E3] mx-auto mb-8 border border-[#E8E8E3]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:40px;height:40px;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    </div>
                    <h2 class="text-2xl font-black text-[#111111] mb-3 tracking-tight uppercase italic">Inventory Empty.</h2>
                    <p class="text-[#666666] font-medium mb-8 max-w-sm mx-auto text-sm">No items currently queued for
                        deployment.</p>
                    <a href="{{ route('products.index') }}"
                        class="px-8 py-4 bg-[#155EEF] text-white font-black rounded-xl hover:bg-[#0D4BC3] transition-all hover:scale-105 active:scale-95 uppercase tracking-widest text-xs">
                        Browse Collection
                    </a>
                </div>
            @else
                <div class="flex flex-col gap-12">
                    <!-- Items List -->
                    <div class="space-y-5 animate-fade-in-up max-w-4xl mx-auto w-full">
                        @foreach($items as $it)
                            <div
                                class="bg-white rounded-2xl p-6 border border-[#E8E8E3] shadow-sm flex flex-col md:flex-row gap-6 items-center group transition-all hover:bg-[#F7F7F5]">
                                <!-- Product Visual -->
                                <div
                                    class="w-full md:w-32 aspect-square bg-[#F7F7F5] rounded-xl flex items-center justify-center p-5 flex-shrink-0 relative overflow-hidden">
                                    @if($it['image'])
                                        <img src="{{ asset('storage/' . $it['image']) }}" alt="{{ $it['name'] }}"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#E8E8E3]" style="width:48px;height:48px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-grow text-center md:text-left space-y-4">
                                    <div>
                                        <span
                                            class="text-[9px] font-black text-[#666666] uppercase tracking-[0.3em] block mb-1">{{ $it['jersey_type'] ?? 'ELITE PERFORMANCE' }}</span>
                                        <h3
                                            class="text-xl font-black text-[#111111] italic uppercase tracking-tighter truncate max-w-sm mx-auto md:mx-0">
                                            {{ $it['name'] }}
                                        </h3>
                                    </div>

                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                        <div
                                            class="px-3 py-1.5 bg-[#F7F7F5] rounded-full text-[8px] font-black text-[#666666] uppercase tracking-widest border border-[#E8E8E3]">
                                            Size {{ $it['size'] ?? 'VLD' }}
                                        </div>
                                        @if(!empty($it['long_sleeve']))
                                            <div
                                                class="px-3 py-1.5 bg-amber-50 rounded-full text-[8px] font-black text-amber-600 uppercase tracking-widest border border-amber-200 italic">
                                                EXT-SLEEVE ENHANCED
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-baseline justify-center md:justify-start gap-1.5">
                                        <span class="text-[9px] font-black text-[#666666] uppercase">{{ $it['currency'] }} UNIT
                                            PRICE</span>
                                        <span
                                            class="text-xl font-black text-[#111111] tracking-tight">{{ number_format($it['line_total'], 2) }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div
                                    class="flex flex-col gap-3 w-full md:w-auto border-t md:border-t-0 md:border-l border-[#E8E8E3] pt-4 md:pt-0 md:pl-6">
                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $it['key'] ?? $it['product_id'] }}">
                                        <div
                                            class="flex items-center bg-[#F7F7F5] rounded-xl border border-[#E8E8E3] p-1.5 group-focus-within:border-[#155EEF] transition-colors">
                                            <input type="number" name="quantity" value="{{ $it['quantity'] }}" min="1"
                                                class="w-14 bg-transparent text-center font-black text-[#111111] focus:outline-none text-sm appearance-none">
                                            <button type="submit"
                                                class="p-2 bg-[#155EEF] text-white rounded-lg hover:bg-[#0D4BC3] transition-all active:scale-95">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                            </button>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $it['key'] ?? $it['product_id'] }}">
                                        <button type="submit"
                                            class="w-full px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] hover:bg-rose-500 hover:text-white transition-all">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary Area -->
                    <div class="animate-fade-in-up w-full">
                        <div class="bg-white border border-[#E8E8E3] rounded-2xl p-8 lg:p-10 text-[#111111] shadow-sm">
                            <h2 class="text-2xl font-black mb-8 uppercase italic tracking-tighter border-b border-[#E8E8E3] pb-4">Checkout Logistics.</h2>
                            
                            <form method="POST" action="{{ route('checkout.cod') }}" id="cartCheckoutForm">
                                @csrf
                                <input type="hidden" name="currency" value="{{ $currency }}">
                                <input type="hidden" id="selected_action_cod" value="{{ route('checkout.cod') }}">
                                <input type="hidden" id="selected_action_stripe" value="{{ route('checkout.stripe') }}">

                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 mt-8">
                                    <!-- Delivery Details -->
                                    <div class="lg:col-span-5 space-y-6">
                                        <h3 class="text-sm font-black text-[#111111] uppercase tracking-wide">1. Delivery Details</h3>
                                        <div class="space-y-4">
                                            <input type="text" name="name" placeholder="Full name" required
                                                class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs">
                                            
                                            <div class="grid grid-cols-2 gap-4">
                                                <input type="email" name="email" placeholder="Email"
                                                    class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs">
                                                <input type="text" name="phone" placeholder="Phone" required
                                                    class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs">
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <input type="text" name="country" placeholder="Country (e.g. MY)" required value="MY"
                                                    class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs">
                                                <input type="text" name="region" placeholder="Region" required
                                                    class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs">
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <input type="text" name="province" placeholder="State/Province" required
                                                    class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs">
                                                <input type="text" name="city" placeholder="City" required
                                                    class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs">
                                            </div>
                                            
                                            <input type="text" name="postal_code" placeholder="Postal code" required
                                                class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs">

                                            <textarea name="address_detail" placeholder="Full delivery address" required rows="2"
                                                class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs resize-none"></textarea>

                                            <textarea name="notes" placeholder="Order notes (optional)" rows="1"
                                                class="w-full bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-xs resize-none"></textarea>
                                        </div>
                                    </div>

                                    <!-- Shipping & Payment -->
                                    <div class="lg:col-span-4 space-y-8">
                                        <!-- Shipping Module -->
                                        <div class="space-y-6">
                                            <h3 class="text-sm font-black text-[#111111] uppercase tracking-wide">2. Courier & Shipping</h3>
                                            <div class="bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl p-5">
                                                <p class="text-[10px] font-bold text-[#666666] uppercase mb-4">Choose self collection or fill in postal code, state and country, then check rates.</p>
                                                <div id="shipping-rates-list" class="grid grid-cols-1 gap-2.5 mb-4"></div>
                                                <div id="shipping-loader" class="text-center hidden py-4">
                                                    <div class="mx-auto mb-2 w-6 h-6 border-2 border-[#E8E8E3] border-t-[#155EEF] rounded-full animate-spin"></div>
                                                    <div class="text-[#666666] text-xs font-semibold">Loading rates…</div>
                                                </div>
                                                <div id="shipping-error"
                                                    class="hidden p-3 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl text-xs font-semibold text-center mb-3">
                                                </div>
                                                <button type="button" id="btnFetchRates"
                                                    class="w-full px-5 py-3 bg-[#111111] text-white rounded-xl font-bold text-xs hover:bg-black transition-all active:scale-[0.98] shadow-sm uppercase tracking-widest">
                                                    Check Rates
                                                </button>
                                            </div>
                                            <input type="hidden" name="shipping_courier_name" id="input_shipping_courier_name">
                                            <input type="hidden" name="shipping_courier_logo" id="input_shipping_courier_logo">
                                            <input type="hidden" name="shipping_service_name" id="input_shipping_service_name">
                                            <input type="hidden" name="shipping_service_id" id="input_shipping_service_id">
                                            <input type="hidden" name="shipping_cost" id="input_shipping_cost">
                                            <input type="hidden" name="shipping_source" id="input_shipping_source" value="">
                                        </div>

                                        <!-- Payment Module -->
                                        <div class="space-y-6 pt-6 border-t border-[#E8E8E3]">
                                            <h3 class="text-sm font-black text-[#111111] uppercase tracking-wide">3. Payment Method</h3>
                                            <div class="grid grid-cols-1 gap-3">
                                                <label class="group relative flex items-center gap-4 bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-4 py-3 cursor-pointer hover:border-[#155EEF] transition-all has-[:checked]:bg-[#155EEF] has-[:checked]:border-[#155EEF]">
                                                    <input type="radio" name="payment_method" value="cod" class="hidden" checked>
                                                    <div class="w-4 h-4 rounded-full border-2 border-[#999999] group-has-[:checked]:border-white group-has-[:checked]:bg-emerald-500 flex items-center justify-center transition-all flex-shrink-0">
                                                        <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100"></div>
                                                    </div>
                                                    <div>
                                                        <div class="font-black text-[#111111] group-has-[:checked]:text-white uppercase tracking-widest text-[10px]">On Receipt</div>
                                                    </div>
                                                </label>
                                                <label class="group relative flex items-center gap-4 bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl px-4 py-3 cursor-pointer hover:border-[#155EEF] transition-all has-[:checked]:bg-[#155EEF] has-[:checked]:border-[#155EEF]">
                                                    <input type="radio" name="payment_method" value="stripe" class="hidden">
                                                    <div class="w-4 h-4 rounded-full border-2 border-[#999999] group-has-[:checked]:border-white group-has-[:checked]:bg-emerald-500 flex items-center justify-center transition-all flex-shrink-0">
                                                        <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100"></div>
                                                    </div>
                                                    <div>
                                                        <div class="font-black text-[#111111] group-has-[:checked]:text-white uppercase tracking-widest text-[10px]">Stripe Pay</div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="lg:col-span-3">
                                        <div class="bg-[#F7F7F5] rounded-2xl p-6 border border-[#E8E8E3] h-full flex flex-col justify-between">
                                            <div>
                                                <h3 class="text-sm font-black text-[#111111] uppercase tracking-wide border-b border-[#DCDCC8] pb-4 mb-6">Order Summary</h3>
                                                <div class="space-y-4">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs font-bold text-[#666666] uppercase tracking-wide">Subtotal</span>
                                                        <span id="display-subtotal" class="text-sm font-black text-[#111111]">{{ $currency }} {{ number_format($total, 2) }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs font-bold text-[#666666] uppercase tracking-wide">Shipping</span>
                                                        <span id="display-shipping" class="text-sm font-black text-[#999999]">—</span>
                                                    </div>
                                                    <div class="pt-4 border-t border-[#DCDCC8] flex flex-col items-end mt-4">
                                                        <span class="font-black text-sm uppercase tracking-wide text-[#666666] mb-1">Total</span>
                                                        <div class="text-right">
                                                            <div id="display-total" class="text-3xl font-black leading-none tracking-tighter italic text-[#111111]">
                                                                {{ number_format($total, 2) }}
                                                            </div>
                                                            <div class="text-[9px] font-bold text-[#666666] uppercase tracking-widest mt-1">
                                                                {{ $currency }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-8">
                                                <button type="submit" id="checkoutSubmit"
                                                    class="w-full py-4 bg-[#155EEF] text-white font-black text-sm rounded-xl hover:bg-[#0D4BC3] hover:scale-[1.02] active:scale-95 transition-all shadow-lg uppercase tracking-widest">
                                                    Place Order
                                                </button>
                                                <p class="text-center text-[8px] text-[#999999] font-black uppercase tracking-[0.4em] mt-4">
                                                    SECURED PROTOCOL
                                                </p>
                                                <script>
                                                    window.cartSubtotal = parseFloat("{{ $total }}");
                                                    window.cartCurrency = "{{ $currency }}";
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        (function () {
            // General Form Handlers
            const form = document.querySelector('form[action*="checkout"]') || document.querySelector('form');
            const submitBtn = document.getElementById('checkoutSubmit');
            const codUrl = document.getElementById('selected_action_cod')?.value;
            const stripeUrl = document.getElementById('selected_action_stripe')?.value;

            if (form && submitBtn && codUrl && stripeUrl) {
                form.addEventListener('submit', function (e) {
                    const shipId = document.getElementById('input_shipping_service_id')?.value;
                    if (!shipId) {
                        e.preventDefault();
                        const errDiv = document.getElementById('shipping-error');
                        errDiv.classList.remove('hidden');
                        errDiv.innerText = 'SELECT LOGISTICS PROVIDER TO PROCEED.';
                        errDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }
                    const selected = document.querySelector('input[name="payment_method"]:checked')?.value || 'cod';
                    form.action = selected === 'stripe' ? stripeUrl : codUrl;
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'PROCESSING...';
                });
            }

            // Shipping Logic
            const btnFetch = document.getElementById('btnFetchRates');
            const loader = document.getElementById('shipping-loader');
            const list = document.getElementById('shipping-rates-list');
            const errDiv = document.getElementById('shipping-error');

            const selfCollectionRate = {
                source: 'self_collection',
                service_id: 'self_collection',
                courier_name: 'Self Collection at Store',
                courier_logo: null,
                service_name: 'Lot 1-35, Suria Sabah Shopping Mall, Kota Kinabalu 88000',
                price: 0,
                delivery: 'Pick up at store'
            };

            function selectShippingRate(rate) {
                document.getElementById('input_shipping_courier_name').value = rate.courier_name;
                document.getElementById('input_shipping_courier_logo').value = rate.courier_logo || '';
                document.getElementById('input_shipping_service_name').value = rate.service_name;
                document.getElementById('input_shipping_service_id').value = rate.service_id;
                document.getElementById('input_shipping_cost').value = rate.price;
                var srcEl = document.getElementById('input_shipping_source');
                if (srcEl) srcEl.value = rate.source || '';

                list.querySelectorAll('.rate-card').forEach(function (c) {
                    c.classList.remove('border-[#155EEF]', 'bg-[#155EEF]', '!text-white');
                    c.style.background = '';
                    c.style.color = '';
                    c.querySelectorAll('div').forEach(function (d) { d.style.color = ''; });
                });

                var card = list.querySelector('[data-service-id="' + rate.service_id + '"]');
                if (card) {
                    card.classList.add('border-[#155EEF]', 'bg-[#155EEF]', '!text-white');
                    card.style.background = '#155EEF';
                    card.style.color = 'white';
                    card.querySelectorAll('div').forEach(function (d) { d.style.color = 'white'; });
                }

                var shipEl = document.getElementById('display-shipping');
                var totalEl = document.getElementById('display-total');
                var subtotal = typeof window.cartSubtotal === 'number' ? window.cartSubtotal : 0;
                var shippingCost = parseFloat(rate.price) || 0;
                if (shipEl) {
                    shipEl.textContent = shippingCost > 0 ? ('RM ' + shippingCost.toFixed(2)) : 'FREE';
                    shipEl.classList.remove('text-[#999999]');
                }
                if (totalEl) {
                    totalEl.textContent = (subtotal + shippingCost).toFixed(2);
                }

                if (errDiv) {
                    errDiv.classList.add('hidden');
                }
            }

            function buildRateCard(rate) {
                const card = document.createElement('button');
                card.type = 'button';
                card.dataset.serviceId = rate.service_id;
                card.className = 'rate-card w-full text-left p-4 bg-white border-2 border-[#E8E8E3] rounded-xl hover:border-[#155EEF]/50 transition-all flex items-center justify-between group active:scale-[0.99]';

                const logo = rate.courier_logo ? '<img src="' + rate.courier_logo + '" alt="' + rate.courier_name + '" class="w-8 h-8 object-contain mr-2.5 flex-shrink-0">' : '';
                const priceLabel = parseFloat(rate.price) > 0 ? parseFloat(rate.price).toFixed(2) : 'FREE';

                card.innerHTML = `
                    <div class="flex items-center min-w-0">
                        ${logo}
                        <div class="min-w-0">
                            <div class="font-bold text-[#111111] text-sm truncate">${rate.courier_name}</div>
                            <div class="text-[#666666] text-xs truncate">${rate.service_name}</div>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-2.5">
                        <div class="text-xs text-[#999999] font-semibold">${parseFloat(rate.price) > 0 ? 'RM' : ''}</div>
                        <div class="text-lg font-black text-[#111111] leading-none">${priceLabel}</div>
                    </div>
                `;

                card.addEventListener('click', function () {
                    selectShippingRate(rate);
                });

                return card;
            }

            function renderSelfCollectionOption() {
                if (!list) return;
                list.innerHTML = '';
                list.classList.remove('hidden');
                list.appendChild(buildRateCard(selfCollectionRate));
            }

            function fetchRates() {
                const postcode = document.querySelector('input[name="postal_code"]').value;
                const state = document.querySelector('input[name="province"]').value;
                const countryInput = document.querySelector('input[name="country"]');
                const country = countryInput ? countryInput.value : 'MY';

                if (!postcode || !state || !country) {
                    errDiv.innerText = 'ENTER POSTCODE, STATE & COUNTRY FIRST.';
                    errDiv.classList.remove('hidden');
                    return;
                }

                loader.classList.remove('hidden');
                btnFetch.disabled = true;
                errDiv.classList.add('hidden');

                const raw = document.getElementById('cart_items_json')?.value || '[]';
                let items = [];
                try { items = JSON.parse(raw) } catch (e) { items = [] }
                const payloadItems = items.map(function (it) { return { quantity: parseInt(it.quantity || 0) }; }).filter(function (i) { return i.quantity > 0; });

                fetch('{{ route("shipping.rates") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ postcode: postcode, state: state, country: country, items: payloadItems })
                })
                    .then(function (r) {
                        if (!r.ok) {
                            return r.json().catch(function () {
                                return { success: false, message: 'LOGISTICS UPLINK FAILURE. RETRY.' };
                            });
                        }
                        return r.json();
                    })
                    .then(function (data) {
                        loader.classList.add('hidden');
                        btnFetch.disabled = false;
                        list.innerHTML = '';
                        list.classList.remove('hidden');
                        list.appendChild(buildRateCard(selfCollectionRate));

                        if (data.success && data.rates && data.rates.length > 0) {
                            data.rates.forEach(function (rate) {
                                list.appendChild(buildRateCard(rate));
                            });
                        } else {
                            errDiv.innerText = data.message || 'NO LOGISTICS BRIDGE AVAILABLE FOR THIS LOCATION.';
                            errDiv.classList.remove('hidden');
                        }
                    })
                    .catch(function () {
                        loader.classList.add('hidden');
                        btnFetch.disabled = false;
                        renderSelfCollectionOption();
                        errDiv.innerText = 'LOGISTICS UPLINK FAILURE. RETRY OR CHOOSE SELF COLLECTION.';
                        errDiv.classList.remove('hidden');
                    });
            }

            if (btnFetch) {
                btnFetch.addEventListener('click', fetchRates);
            }

            renderSelfCollectionOption();
            })();
    </script>
    <input type="hidden" id="cart_items_json" value="{{ e(json_encode($items)) }}">
@endsection
