@extends('layouts.public')

@section('title', 'Your Collection - Maxumax')

@section('content')
    <div class="bg-black min-h-screen pt-24 pb-32 px-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header section -->
            <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-8 animate-fade-in">
                <div>
                    <span class="text-white/40 font-black uppercase tracking-[0.3em] text-[10px] mb-4 inline-block">Order
                        Configuration</span>
                    <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter uppercase italic leading-[0.9]">
                        Review <span class="text-white/40">Inventory.</span>
                    </h1>
                </div>
                @if(count($items))
                    <div class="flex items-center gap-4 border-l border-white/5 pl-6 pb-2">
                        <span class="text-white/40 font-black text-[10px] uppercase tracking-widest">Active Batch</span>
                        <span class="text-white font-black text-xl italic">{{ count($items) }} Units</span>
                    </div>
                @endif
            </div>

            <!-- Notifications -->
            @if(session('success') || session('error') || $errors->any())
                <div class="mb-12 space-y-4 animate-fade-in">
                    @if(session('success'))
                        <div
                            class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl flex items-center gap-3 font-bold text-xs">
                            <i data-feather="check-circle" style="width:16px;height:16px;"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error') || $errors->any())
                        <div
                            class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl font-bold text-xs space-y-2">
                            @if(session('error'))
                                <div class="flex items-center gap-3">
                                    <i data-feather="alert-circle" style="width:16px;height:16px;"></i>
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
                <div class="py-40 text-center border-2 border-dashed border-white/5 rounded-[3rem] animate-fade-in">
                    <div
                        class="w-32 h-32 bg-white/5 rounded-full flex items-center justify-center text-white/10 mx-auto mb-10 border border-white/5">
                        <i data-feather="shopping-bag" style="width:48px;height:48px;"></i>
                    </div>
                    <h2 class="text-3xl font-black text-white mb-4 tracking-tight uppercase italic">Inventory Empty.</h2>
                    <p class="text-white/20 font-medium mb-12 max-w-sm mx-auto text-sm">No items currently queued for
                        deployment.</p>
                    <a href="{{ route('products.index') }}"
                        class="px-12 py-6 bg-white text-black font-black rounded-2xl hover:bg-zinc-200 transition-all hover:scale-105 active:scale-95 uppercase tracking-widest text-xs">
                        Browse Collection
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                    <!-- Items List -->
                    <div class="lg:col-span-8 space-y-8 animate-fade-in-up">
                        @foreach($items as $it)
                            <div
                                class="bg-[#111111] rounded-[2.5rem] p-8 md:p-10 border border-white/5 shadow-2xl flex flex-col md:flex-row gap-10 items-center group transition-all hover:bg-white/[0.02]">
                                <!-- Product Visual -->
                                <div
                                    class="w-full md:w-48 aspect-square bg-gradient-to-b from-white/[0.03] to-transparent rounded-[2rem] flex items-center justify-center p-8 flex-shrink-0 relative overflow-hidden">
                                    @if($it['image'])
                                        <img src="{{ asset('storage/' . $it['image']) }}" alt="{{ $it['name'] }}"
                                            class="w-full h-full object-contain drop-shadow-2xl group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <i data-feather="image" class="text-white/5" style="width:60px;height:60px;"></i>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-grow text-center md:text-left space-y-6">
                                    <div>
                                        <span
                                            class="text-[10px] font-black text-white/20 uppercase tracking-[0.3em] block mb-2">{{ $it['jersey_type'] ?? 'ELITE PERFORMANCE' }}</span>
                                        <h3
                                            class="text-2xl font-black text-white italic uppercase tracking-tighter truncate max-w-sm">
                                            {{ $it['name'] }}
                                        </h3>
                                    </div>

                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                        <div
                                            class="px-4 py-2 bg-white/5 rounded-full text-[9px] font-black text-white/40 uppercase tracking-widest border border-white/5">
                                            Size {{ $it['size'] ?? 'VLD' }}
                                        </div>
                                        @if(!empty($it['variant_sku']))
                                            <div
                                                class="px-4 py-2 bg-white/5 rounded-full text-[9px] font-black text-white/40 uppercase tracking-widest border border-white/5">
                                                ID {{ $it['variant_sku'] }}
                                            </div>
                                        @endif
                                        @if(!empty($it['long_sleeve']))
                                            <div
                                                class="px-4 py-2 bg-amber-500/10 rounded-full text-[9px] font-black text-amber-500 uppercase tracking-widest border border-amber-500/20 italic">
                                                EXT-SLEEVE ENHANCED
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-baseline justify-center md:justify-start gap-2">
                                        <span class="text-[10px] font-black text-white/20 uppercase">{{ $it['currency'] }} UNIT
                                            PRICE</span>
                                        <span
                                            class="text-2xl font-black text-white tracking-tight">{{ number_format($it['line_total'], 2) }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div
                                    class="flex flex-col gap-4 w-full md:w-auto border-t md:border-t-0 md:border-l border-white/5 pt-8 md:pt-0 md:pl-10">
                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-3">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $it['key'] ?? $it['product_id'] }}">
                                        <div
                                            class="flex items-center bg-white/5 rounded-2xl border border-white/10 p-2 group-focus-within:border-white transition-colors">
                                            <input type="number" name="quantity" value="{{ $it['quantity'] }}" min="1"
                                                class="w-16 bg-transparent text-center font-black text-white focus:outline-none text-sm appearance-none">
                                            <button type="submit"
                                                class="p-3 bg-white text-black rounded-xl hover:bg-zinc-200 transition-all active:scale-95">
                                                <i data-feather="refresh-cw" style="width:16px;height:16px;"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $it['key'] ?? $it['product_id'] }}">
                                        <button type="submit"
                                            class="w-full px-6 py-4 bg-red-500/5 border border-red-500/10 text-red-500 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-red-500 hover:text-white transition-all">
                                            Purge Unit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary Sidebar -->
                    <div class="lg:col-span-4 lg:sticky lg:top-32 animate-fade-in-right">
                        <div class="bg-white border border-white/5 rounded-[3rem] p-12 text-black shadow-3xl">
                            <h2 class="text-3xl font-black mb-10 uppercase italic tracking-tighter">Logistics.</h2>

                            <!-- Totals -->
                            <div class="space-y-8 mb-12">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-black/40 uppercase tracking-[0.2em]">Batch
                                        Subtotal</span>
                                    <span class="text-lg font-black">{{ $currency }} {{ number_format($total, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-black/40 uppercase tracking-[0.2em]">Delivery
                                        Matrix</span>
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Calculated</span>
                                </div>
                                <div class="pt-10 border-t border-black/5 flex justify-between items-end">
                                    <span
                                        class="font-black text-3xl uppercase italic tracking-tighter leading-none">Total</span>
                                    <div class="text-right">
                                        <div class="text-[10px] font-black text-black/40 uppercase tracking-widest mb-1">
                                            {{ $currency }}
                                        </div>
                                        <div class="text-5xl font-black leading-[0.8] tracking-tighter italic">
                                            {{ number_format($total, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Checkout Form -->
                            <form method="POST" action="{{ route('checkout.cod') }}" class="space-y-10" id="cartCheckoutForm">
                                @csrf
                                <input type="hidden" name="currency" value="{{ $currency }}">
                                <input type="hidden" id="selected_action_cod" value="{{ route('checkout.cod') }}">
                                <input type="hidden" id="selected_action_stripe" value="{{ route('checkout.stripe') }}">

                                <div class="space-y-6">
                                    <h3 class="text-[10px] font-black text-black/40 uppercase tracking-[0.3em]">Deployment Intel
                                    </h3>

                                    <div class="space-y-4">
                                        <input type="text" name="name" placeholder="Full Legal Name" required
                                            class="w-full bg-black/5 border border-black/10 rounded-2xl px-6 py-5 text-black font-black placeholder-black/20 focus:outline-none focus:border-black transition-all uppercase tracking-widest text-xs">
                                        <input type="email" name="email" placeholder="Communication Email"
                                            class="w-full bg-black/5 border border-black/10 rounded-2xl px-6 py-5 text-black font-black placeholder-black/20 focus:outline-none focus:border-black transition-all uppercase tracking-widest text-xs">
                                        <input type="text" name="phone" placeholder="Contact Terminal (WA)" required
                                            class="w-full bg-black/5 border border-black/10 rounded-2xl px-6 py-5 text-black font-black placeholder-black/20 focus:outline-none focus:border-black transition-all uppercase tracking-widest text-xs">

                                        <div class="grid grid-cols-2 gap-4">
                                            <input type="text" name="region" placeholder="Region" required
                                                class="w-full bg-black/5 border border-black/10 rounded-2xl px-6 py-5 text-black font-black placeholder-black/20 focus:outline-none focus:border-black transition-all uppercase tracking-widest text-xs">
                                            <input type="text" name="province" placeholder="State/Prov" required
                                                class="w-full bg-black/5 border border-black/10 rounded-2xl px-6 py-5 text-black font-black placeholder-black/20 focus:outline-none focus:border-black transition-all uppercase tracking-widest text-xs">
                                            <input type="text" name="city" placeholder="City" required
                                                class="w-full bg-black/5 border border-black/10 rounded-2xl px-6 py-5 text-black font-black placeholder-black/20 focus:outline-none focus:border-black transition-all uppercase tracking-widest text-xs">
                                            <input type="text" name="postal_code" placeholder="Postal Code" required
                                                class="w-full bg-black/5 border border-black/10 rounded-2xl px-6 py-5 text-black font-black placeholder-black/20 focus:outline-none focus:border-black transition-all uppercase tracking-widest text-xs">
                                        </div>

                                        <textarea name="address_detail" placeholder="Full Deployment Address" required rows="3"
                                            class="w-full bg-black/5 border border-black/10 rounded-2xl px-6 py-5 text-black font-black placeholder-black/20 focus:outline-none focus:border-black transition-all uppercase tracking-widest text-xs resize-none"></textarea>

                                        <textarea name="notes" placeholder="Logistic Notes (Optional)" rows="2"
                                            class="w-full bg-black/5 border border-black/10 rounded-2xl px-6 py-5 text-black font-black placeholder-black/20 focus:outline-none focus:border-black transition-all uppercase tracking-widest text-xs resize-none"></textarea>
                                    </div>

                                    <!-- Shipping Module -->
                                    <div class="space-y-6 pt-6 border-t border-black/5">
                                        <h3 class="text-[10px] font-black text-black/40 uppercase tracking-[0.3em]">Logistic
                                            Provider</h3>
                                        <div class="bg-black/5 border border-black/10 rounded-3xl p-8">
                                            <div id="shipping-loader" class="text-center hidden py-4">
                                                <div
                                                    class="mx-auto mb-4 w-12 h-12 border-4 border-black/5 border-t-black rounded-full animate-spin">
                                                </div>
                                                <div class="text-black/40 font-black text-[9px] uppercase tracking-widest">
                                                    Querying Matrix...</div>
                                            </div>
                                            <div id="shipping-error"
                                                class="hidden p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">
                                            </div>
                                            <div id="shipping-rates-list" class="grid grid-cols-1 gap-4"></div>
                                            <div class="mt-6">
                                                <button type="button" id="btnFetchRates"
                                                    class="w-full px-8 py-5 bg-black text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] hover:bg-zinc-800 transition-all active:scale-95 shadow-lg">
                                                    Check Shipping Rates
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="shipping_courier_name" id="input_shipping_courier_name">
                                        <input type="hidden" name="shipping_courier_logo" id="input_shipping_courier_logo">
                                        <input type="hidden" name="shipping_service_name" id="input_shipping_service_name">
                                        <input type="hidden" name="shipping_service_id" id="input_shipping_service_id">
                                        <input type="hidden" name="shipping_cost" id="input_shipping_cost">
                                    </div>

                                    <!-- Payment Module -->
                                    <div class="space-y-6 pt-6 border-t border-black/5">
                                        <h3 class="text-[10px] font-black text-black/40 uppercase tracking-[0.3em]">Payment
                                            Protocol</h3>
                                        <div class="grid grid-cols-1 gap-4">
                                            <label
                                                class="group relative flex items-center gap-6 bg-black/5 border border-black/10 rounded-3xl px-8 py-6 cursor-pointer hover:border-black transition-all has-[:checked]:bg-black has-[:checked]:border-black">
                                                <input type="radio" name="payment_method" value="cod" class="hidden" checked>
                                                <div
                                                    class="w-6 h-6 rounded-full border-2 border-black/20 group-has-[:checked]:border-white group-has-[:checked]:bg-emerald-500 flex items-center justify-center transition-all">
                                                    <div
                                                        class="w-2 h-2 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100">
                                                    </div>
                                                </div>
                                                <div>
                                                    <div
                                                        class="font-black text-black group-has-[:checked]:text-white uppercase tracking-widest text-[11px] mb-1">
                                                        On Receipt</div>
                                                    <div
                                                        class="text-black/40 group-has-[:checked]:text-white/40 text-[9px] font-black uppercase tracking-widest">
                                                        Post-Deployment Payment</div>
                                                </div>
                                            </label>
                                            <label
                                                class="group relative flex items-center gap-6 bg-black/5 border border-black/10 rounded-3xl px-8 py-6 cursor-pointer hover:border-black transition-all has-[:checked]:bg-black has-[:checked]:border-black">
                                                <input type="radio" name="payment_method" value="stripe" class="hidden">
                                                <div
                                                    class="w-6 h-6 rounded-full border-2 border-black/20 group-has-[:checked]:border-white group-has-[:checked]:bg-emerald-500 flex items-center justify-center transition-all">
                                                    <div
                                                        class="w-2 h-2 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100">
                                                    </div>
                                                </div>
                                                <div>
                                                    <div
                                                        class="font-black text-black group-has-[:checked]:text-white uppercase tracking-widest text-[11px] mb-1">
                                                        Stripe Pay</div>
                                                    <div
                                                        class="text-black/40 group-has-[:checked]:text-white/40 text-[9px] font-black uppercase tracking-widest">
                                                        Secured Card Bridge</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" id="checkoutSubmit"
                                    class="w-full py-8 bg-black text-white font-black text-xl rounded-[2.5rem] hover:bg-zinc-800 hover:scale-[1.02] active:scale-95 transition-all shadow-3xl uppercase tracking-[0.4em] italic mt-12">
                                    Initialize Order.
                                </button>
                                <p class="text-center text-[9px] text-black/20 font-black uppercase tracking-[0.4em]">
                                    MAXUMAX SECURED PROTOCOL V2.6
                                </p>
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

            function fetchRates() {
                const postcode = document.querySelector('input[name="postal_code"]').value;
                const state = document.querySelector('input[name="province"]').value;

                if (!postcode || !state) {
                    errDiv.innerText = 'ENTER POSTCODE & STATE FIRST.';
                    errDiv.classList.remove('hidden');
                    return;
                }

                loader.classList.remove('hidden');
                btnFetch.disabled = true;
                list.innerHTML = '';
                list.classList.add('hidden');
                errDiv.classList.add('hidden');

                const raw = document.getElementById('cart_items_json')?.value || '[]';
                let items = [];
                try { items = JSON.parse(raw) } catch (e) { items = [] }
                const payloadItems = items.map(function (it) { return { quantity: parseInt(it.quantity || 0) }; }).filter(function (i) { return i.quantity > 0; });

                fetch('{{ route("shipping.rates") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ postcode, state, country: 'MY', items: payloadItems })
                })
                    .then(r => r.json())
                    .then(data => {
                        loader.classList.add('hidden');
                        btnFetch.disabled = false;
                        if (data.success && data.rates && data.rates.length > 0) {
                            list.classList.remove('hidden');
                            renderRates(data.rates);
                        } else {
                            errDiv.innerText = data.message || 'NO LOGISTICS BRIDGE AVAILABLE FOR THIS LOCATION.';
                            errDiv.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        loader.classList.add('hidden');
                        btnFetch.disabled = false;
                        errDiv.innerText = 'LOGISTICS UPLINK FAILURE. RETRY.';
                        errDiv.classList.remove('hidden');
                    });
            }

            function renderRates(rates) {
    list.innerHTML = '';
                    rates.forEach(rate => {
                        const card = document.createElement('button');
                        card.type = 'button';
                        card.className = 'w-full text-left p-6 bg-black/[0.03] border border-black/5 rounded-2xl hover:border-black transition-all flex items-center justify-between group active:scale-[0.98]';

                        const logo = rate.courier_logo ? `<img src="${rate.courier_logo}" alt="${rate.courier_name}" class="w-10 h-10 object-contain mr-4 filter grayscale group-hover:grayscale-0 transition-all">` : '';

                        card.innerHTML = `
                            <div class="flex items-center">
                                ${logo}
                                <div>
                                    <div class="font-black text-black text-[10px] uppercase tracking-widest mb-1 leading-none">${rate.courier_name}</div>
                                    <div class="text-black/40 text-[9px] font-black uppercase tracking-widest leading-none">${rate.service_name}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-[9px] font-black text-black/40 uppercase tracking-widest mb-1 leading-none">RM</div>
                                <div class="text-2xl font-black text-black tracking-tighter italic leading-none">${parseFloat(rate.price).toFixed(2)}</div>
                            </div>
                        `;

                        card.addEventListener('click', function () {
                            document.getElementById('input_shipping_courier_name').value = rate.courier_name;
                            document.getElementById('input_shipping_courier_logo').value = rate.courier_logo || '';
                            document.getElementById('input_shipping_service_name').value = rate.service_name;
                            document.getElementById('input_shipping_service_id').value = rate.service_id;
                            document.getElementById('input_shipping_cost').value = rate.price;

                            Array.from(list.children).forEach(c => {
                                c.classList.remove('border-black', 'bg-black', 'text-white');
                                c.querySelector('.text-2xl')?.classList.remove('text-white');
                                c.querySelector('.text-black')?.classList.remove('text-white');
                            });

                            card.classList.add('border-black', 'bg-black');
                            card.querySelectorAll('div').forEach(d => d.style.color = 'white');
                            card.style.color = 'white';
                        });
                        list.appendChild(card);
                    });
                }

                if (btnFetch) {
                    btnFetch.addEventListener('click', fetchRates);
                }
            })();
        </script>
        <input type="hidden" id="cart_items_json" value="{{ e(json_encode($items)) }}">
@endsection