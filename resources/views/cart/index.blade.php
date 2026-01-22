@extends('layouts.public')

@section('title', 'Your Collection - Maxumax')

@section('content')
    <section class="min-h-screen bg-slate-50 py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-8">
                <div>
                    <span
                        class="text-blue-600 font-black uppercase tracking-[0.3em] text-sm mb-4 inline-block">Checkout</span>
                    <h1
                        class="text-4xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">
                        Your <span class="text-blue-600">Cart.</span></h1>
                </div>
                @if(count($items))
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-widest pb-2">
                        {{ count($items) }} Unique Items in Collection
                    </p>
                @endif
            </div>

            @if(session('success'))
                <div
                    class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 font-bold text-sm">
                    <i data-feather="check-circle" style="width:18px;height:18px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl font-bold text-sm">
                    @if(session('error'))
                        <div class="flex items-center gap-3 mb-2">
                            <i data-feather="alert-circle" style="width:18px;height:18px;"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside ml-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            @if(!count($items))
                <div class="bg-white rounded-[3rem] p-24 text-center border border-slate-100 shadow-2xl shadow-slate-200/50">
                    <div
                        class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-8">
                        <i data-feather="shopping-bag" style="width:48px;height:48px;"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Your cart is empty.</h2>
                    <p class="text-slate-500 font-medium mb-12 max-w-sm mx-auto">Looks like you haven't added any gear to your
                        collection yet.</p>
                    <a href="{{ route('products.index') }}"
                        class="inline-block px-10 py-5 bg-slate-900 text-white font-black rounded-2xl hover:bg-blue-600 transition-all hover:scale-105 active:scale-95 uppercase tracking-widest">
                        Browse Gear
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                    <!-- Items List -->
                    <div class="lg:col-span-8 space-y-6">
                        @foreach($items as $it)
                            <div
                                class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col md:flex-row gap-8 items-center group transition-all hover:border-blue-600/20">
                                <div
                                    class="w-full md:w-40 aspect-square bg-slate-50 rounded-[2rem] flex items-center justify-center p-6 flex-shrink-0 group-hover:bg-blue-50 transition-colors">
                                    @if($it['image'])
                                        <img src="{{ asset('storage/' . $it['image']) }}" alt="{{ $it['name'] }}"
                                            class="w-full h-full object-contain drop-shadow-xl group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <i data-feather="image" class="text-slate-200" style="width:40px;height:40px;"></i>
                                    @endif
                                </div>
                                <div class="flex-grow text-center md:text-left">
                                    <span
                                        class="text-[10px] font-black text-blue-600 uppercase tracking-widest block mb-1">{{ $it['jersey_type'] ?? 'Elite' }}</span>
                                    <h3 class="text-xl font-black text-slate-900 mb-2 truncate max-w-xs">{{ $it['name'] }}</h3>
                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-6">
                                        <div
                                            class="px-3 py-1 bg-slate-50 rounded-lg text-xs font-black text-slate-500 uppercase tracking-widest border border-slate-100 italic">
                                            Size {{ $it['size'] ?? '-' }}
                                        </div>
                                        @if(!empty($it['variant_sku']))
                                            <div
                                                class="px-3 py-1 bg-slate-50 rounded-lg text-xs font-black text-slate-500 uppercase tracking-widest border border-slate-100 italic">
                                                SKU {{ $it['variant_sku'] }}
                                            </div>
                                        @endif
                                        @if(!empty($it['long_sleeve']))
                                            <div
                                                class="px-3 py-1 bg-emerald-50 rounded-lg text-xs font-black text-emerald-600 uppercase tracking-widest border border-emerald-100 italic">
                                                Long Sleeve (+{{ $items[0]['currency'] }}
                                                {{ number_format($currencyConfig['longSleeve'], 2) }})
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-center md:justify-start gap-3">
                                        <span class="text-xs font-bold text-slate-400">{{ $it['currency'] }}</span>
                                        <span
                                            class="text-2xl font-black text-slate-900">{{ number_format($it['line_total'], 2) }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-3 w-full md:w-auto">
                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $it['key'] ?? $it['product_id'] }}">
                                        <div class="flex items-center bg-slate-50 rounded-xl border border-slate-100 p-1">
                                            <input type="number" name="quantity" value="{{ $it['quantity'] }}" min="1"
                                                class="w-12 bg-transparent text-center font-black text-slate-900 focus:outline-none text-sm">
                                            <button type="submit"
                                                class="p-2 bg-slate-900 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                                <i data-feather="refresh-cw" style="width:14px;height:14px;"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $it['key'] ?? $it['product_id'] }}">
                                        <button type="submit"
                                            class="w-full px-4 py-2 border border-red-100 text-red-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-50 transition-colors">
                                            Remove Item
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary Sidebar -->
                    <div class="lg:col-span-4 lg:sticky lg:top-24">
                        <div class="bg-slate-900 rounded-[3rem] p-10 text-white shadow-2xl shadow-slate-900/20">
                            <h2 class="text-2xl font-black mb-8 uppercase italic tracking-tight">Order Summary.</h2>

                            <div class="space-y-6 mb-8">
                                <div
                                    class="flex justify-between items-center text-slate-400 font-bold text-sm uppercase tracking-widest">
                                    <span>Subtotal</span>
                                    <span class="text-white">{{ $currency }} {{ number_format($total, 2) }}</span>
                                </div>
                                <div
                                    class="flex justify-between items-center text-slate-400 font-bold text-sm uppercase tracking-widest">
                                    <span>Shipping</span>
                                    <span class="text-emerald-400">Fixed/COD</span>
                                </div>
                                <div class="pt-6 border-t border-white/10 flex justify-between items-end">
                                    <span class="font-black text-lg uppercase italic">Total</span>
                                    <div class="text-right">
                                        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                                            {{ $currency }}</div>
                                        <div class="text-4xl font-black text-white leading-none">{{ number_format($total, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('checkout.cod') }}" class="space-y-6">
                                @csrf
                                <input type="hidden" name="currency" value="{{ $currency }}">
                                <input type="hidden" id="selected_action_cod" value="{{ route('checkout.cod') }}">
                                <input type="hidden" id="selected_action_stripe" value="{{ route('checkout.stripe') }}">

                                <div class="space-y-4 pt-4">
                                    <div class="group">
                                        <input type="text" name="name" placeholder="Full Name" required
                                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="group">
                                        <input type="email" name="email" placeholder="Email"
                                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="group">
                                        <input type="text" name="phone" placeholder="WhatsApp Number" required
                                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div class="group">
                                            <input type="text" name="region" placeholder="Region" required
                                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                                        </div>
                                        <div class="group">
                                            <input type="text" name="province" placeholder="Country/Province" required
                                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                                        </div>
                                        <div class="group">
                                            <input type="text" name="city" placeholder="City" required
                                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                                        </div>
                                        <div class="group">
                                            <input type="text" name="postal_code" placeholder="Postal Code" required
                                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                                        </div>
                                    </div>
                                    <div class="group">
                                        <textarea name="address_detail" placeholder="Address Detail" required rows="3"
                                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors resize-none"></textarea>
                                    </div>
                                    <div class="group">
                                        <textarea name="notes" placeholder="Notes (optional)" rows="2"
                                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors resize-none"></textarea>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="text-slate-400 font-bold text-xs uppercase tracking-widest">
                                            Metode Pembayaran
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <label class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-6 py-4 cursor-pointer hover:border-blue-500 transition-colors">
                                                <input type="radio" name="payment_method" value="cod" class="accent-blue-500" checked>
                                                <div>
                                                    <div class="font-black text-white uppercase tracking-widest text-xs">Cash On Delivery</div>
                                                    <div class="text-slate-400 text-[10px] font-bold">Bayar saat barang tiba</div>
                                                </div>
                                            </label>
                                            <label class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-6 py-4 cursor-pointer hover:border-blue-500 transition-colors">
                                                <input type="radio" name="payment_method" value="stripe" class="accent-blue-500">
                                                <div>
                                                    <div class="font-black text-white uppercase tracking-widest text-xs">Stripe</div>
                                                    <div class="text-slate-400 text-[10px] font-bold">Kartu debit/kredit aman</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" id="checkoutSubmit"
                                    class="w-full py-6 bg-white text-slate-900 font-black text-lg rounded-[2rem] hover:bg-blue-400 hover:scale-101 active:scale-95 transition-all shadow-xl shadow-white/5 uppercase tracking-[0.2em]">
                                    Proceed Checkout
                                </button>
                                <p class="text-center text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                                    Secured via Maxumax Protocol v2.6
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <script>
        (function () {
            const form = document.querySelector('form[action*="checkout"]') || document.querySelector('form');
            const submitBtn = document.getElementById('checkoutSubmit');
            const codUrl = document.getElementById('selected_action_cod')?.value;
            const stripeUrl = document.getElementById('selected_action_stripe')?.value;
            if (form && submitBtn && codUrl && stripeUrl) {
                form.addEventListener('submit', function (e) {
                    const selected = document.querySelector('input[name="payment_method"]:checked')?.value || 'cod';
                    form.action = selected === 'stripe' ? stripeUrl : codUrl;
                });
            }
        })();
    </script>
@endsection
