@extends('layouts.public')

@section('title', 'Order Confirmed - Maxumax')

@section('content')
    <div class="bg-white min-h-screen pt-32 pb-40 px-6 overflow-hidden relative">
        <!-- Background Accents -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[50vh] bg-gradient-to-b from-emerald-500/10 to-transparent pointer-events-none">
        </div>

        <div class="max-w-4xl mx-auto relative z-10">
            <!-- Success Header -->
            <div class="text-center mb-20 animate-fade-in">
                <div
                    class="w-24 h-24 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-10 shadow-lg group">
                    <i data-feather="check"
                        class="text-white w-12 h-12 stroke-[3px] group-hover:scale-110 transition-transform"></i>
                </div>

                <span
                    class="text-emerald-600 font-black uppercase tracking-[0.2em] text-xs mb-6 inline-block">Thank you for your purchase</span>

                <h1 class="text-5xl md:text-8xl font-black text-[#111111] tracking-tighter uppercase italic leading-[0.8] mb-8">
                    Order <span class="text-emerald-500">Confirmed.</span>
                </h1>

                @php
                    $status = 'pending';
                    $isStripe = false;
                    if (isset($orders) && count($orders) > 0) {
                        $firstOrder = $orders[0];
                        $status = $firstOrder->status;
                        if ($firstOrder->stripe_payment_intent_id) {
                            $isStripe = true;
                        }
                    }
                @endphp

                <div class="inline-flex items-center gap-4 bg-emerald-50 border border-emerald-200 rounded-full px-8 py-3">
                    <div
                        class="w-2 h-2 rounded-full {{ $status === 'paid' ? 'bg-emerald-500 animate-pulse' : ($status === 'confirmed' ? 'bg-blue-500 animate-pulse' : 'bg-amber-500 animate-pulse') }}">
                    </div>
                    <span class="text-emerald-800 font-black uppercase tracking-widest text-[10px]">
                        Status:
                        {{ $status === 'paid' ? 'PAYMENT RECEIVED' : ($status === 'confirmed' ? 'ORDER CONFIRMED' : 'AWAITING PAYMENT') }}
                    </span>
                </div>
            </div>

            <!-- Order Cards -->
            <div class="space-y-6 animate-fade-in-up">
                <div class="bg-white border border-gray-200 rounded-3xl p-10 md:p-16 shadow-xl">
                    <div class="flex items-center justify-between mb-12 border-b border-gray-100 pb-8">
                        <h2 class="text-2xl font-black text-[#111111] italic uppercase tracking-tighter">Order Details</h2>
                        <span class="text-gray-400 font-black text-[10px] uppercase tracking-widest">{{ count($orders) }}
                            {{ count($orders) == 1 ? 'Item' : 'Items' }}</span>
                    </div>

                    @if(isset($orders) && count($orders))
                        <div class="space-y-4">
                            @foreach($orders as $o)
                                <div
                                    class="bg-gray-50 border border-gray-100 rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 hover:bg-gray-100 transition-colors group">
                                    <div class="flex items-center gap-8">
                                        <div
                                            class="bg-emerald-100 w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <i data-feather="package" class="text-emerald-600 w-8 h-8"></i>
                                        </div>
                                        <div>
                                            <div
                                                class="text-[#111111] font-black font-mono text-xl tracking-tighter mb-1 uppercase">
                                                {{ $o->order_number }}</div>
                                            <div class="text-gray-600 font-black text-[10px] uppercase tracking-[0.2em]">
                                                {{ optional($o->product)->name ?? $o->jersey_type ?? 'PRODUCT' }}
                                                @if($o->size)
                                                    <span class="text-gray-400 ml-2">• SIZE {{ $o->size }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="px-6 py-2 bg-emerald-100 rounded-full text-[9px] font-black text-emerald-700 uppercase tracking-widest border border-emerald-200 italic">
                                        {{ $o->status }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Info Section -->
                        <div class="mt-12 p-8 bg-blue-50 border border-blue-200 rounded-2xl">
                            <div class="flex items-center gap-4 mb-4 text-blue-700">
                                <i data-feather="info" class="w-5 h-5"></i>
                                <span class="font-black uppercase tracking-[0.2em] text-xs">Order Information</span>
                            </div>
                            <p class="text-blue-700 text-xs font-bold leading-relaxed">
                                @if($isStripe)
                                    Your payment has been processed securely via Stripe. We will begin processing your order shortly.
                                @else
                                    You have selected cash on delivery. Our team will contact you to arrange delivery and payment.
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="py-20 text-center text-gray-400 font-black uppercase tracking-widest text-sm italic">
                            No active purchases found.
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-10">
                    <a href="{{ route('order.track') }}"
                        class="group bg-[#155EEF] rounded-2xl p-8 flex items-center justify-between transition-all hover:scale-[1.02] active:scale-95">
                        <div>
                            <span class="text-blue-100 font-black text-[10px] uppercase tracking-widest block mb-1">Track Order</span>
                            <span class="text-white font-black text-2xl italic uppercase tracking-tighter">View Progress</span>
                        </div>
                        <div
                            class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-[#155EEF] group-hover:rotate-12 transition-transform">
                            <i data-feather="arrow-right"></i>
                        </div>
                    </a>

                    <a href="{{ route('products.index') }}"
                        class="group bg-gray-100 border border-gray-200 rounded-2xl p-8 flex items-center justify-between transition-all hover:scale-[1.02] active:scale-95">
                        <div>
                            <span class="text-gray-500 font-black text-[10px] uppercase tracking-widest block mb-1">Continue Shopping</span>
                            <span class="text-[#111111] font-black text-2xl italic uppercase tracking-tighter">View Products</span>
                        </div>
                        <div
                            class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-gray-600 group-hover:-rotate-12 transition-transform border border-gray-200">
                            <i data-feather="shopping-bag"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-20 text-center text-[9px] text-gray-400 font-black uppercase tracking-[0.5em]">
                MAXUMAX ORDER REFERENCE #{{ date('Ymd-His') }}
            </p>
        </div>
    </div>
@endsection