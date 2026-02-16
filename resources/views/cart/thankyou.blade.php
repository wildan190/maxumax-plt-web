@extends('layouts.public')

@section('title', 'Deployment Confirmed - Maxumax')

@section('content')
    <div class="bg-black min-h-screen pt-32 pb-40 px-6 overflow-hidden relative">
        <!-- Background Accents -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[50vh] bg-gradient-to-b from-emerald-500/10 to-transparent pointer-events-none">
        </div>

        <div class="max-w-4xl mx-auto relative z-10">
            <!-- Success Header -->
            <div class="text-center mb-20 animate-fade-in">
                <div
                    class="w-24 h-24 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-10 shadow-[0_0_50px_rgba(16,185,129,0.3)] group">
                    <i data-feather="check"
                        class="text-black w-12 h-12 stroke-[3px] group-hover:scale-110 transition-transform"></i>
                </div>

                <span
                    class="text-emerald-500 font-black uppercase tracking-[0.4em] text-[10px] mb-6 inline-block">Transmission
                    Successful</span>

                <h1 class="text-5xl md:text-8xl font-black text-white tracking-tighter uppercase italic leading-[0.8] mb-8">
                    Deployment <span class="text-emerald-500">Confirmed.</span>
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

                <div class="inline-flex items-center gap-4 bg-white/5 border border-white/10 rounded-full px-8 py-3">
                    <div
                        class="w-2 h-2 rounded-full {{ $status === 'paid' ? 'bg-emerald-500 animate-pulse' : ($status === 'confirmed' ? 'bg-blue-500 animate-pulse' : 'bg-amber-500 animate-pulse') }}">
                    </div>
                    <span class="text-white font-black uppercase tracking-widest text-[10px]">
                        Status:
                        {{ $status === 'paid' ? 'FUNDING SECURED' : ($status === 'confirmed' ? 'LOGISTICS ENGAGED' : 'PENDING UPLINK') }}
                    </span>
                </div>
            </div>

            <!-- Order Cards -->
            <div class="space-y-6 animate-fade-in-up">
                <div class="bg-[#111111] border border-white/5 rounded-[3rem] p-10 md:p-16 shadow-3xl">
                    <div class="flex items-center justify-between mb-12 border-b border-white/5 pb-8">
                        <h2 class="text-2xl font-black text-white italic uppercase tracking-tighter">Inventory Details.</h2>
                        <span class="text-white/20 font-black text-[10px] uppercase tracking-widest">{{ count($orders) }}
                            Active Segments</span>
                    </div>

                    @if(isset($orders) && count($orders))
                        <div class="space-y-4">
                            @foreach($orders as $o)
                                <div
                                    class="bg-black/40 border border-white/5 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 hover:bg-white/[0.02] transition-colors group">
                                    <div class="flex items-center gap-8">
                                        <div
                                            class="bg-white/5 w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <i data-feather="package" class="text-white/20 w-8 h-8"></i>
                                        </div>
                                        <div>
                                            <div
                                                class="text-emerald-500 font-black font-mono text-xl tracking-tighter mb-1 uppercase">
                                                {{ $o->order_number }}</div>
                                            <div class="text-white/40 font-black text-[10px] uppercase tracking-[0.2em]">
                                                {{ optional($o->product)->name ?? $o->jersey_type ?? 'RESERVED PRODUCT' }}
                                                @if($o->size)
                                                    <span class="text-white/20 ml-2">• SIZE {{ $o->size }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="px-6 py-2 bg-white/5 rounded-full text-[9px] font-black text-white uppercase tracking-widest border border-white/10 italic">
                                        {{ $o->status }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Info Uplink -->
                        <div class="mt-12 p-8 bg-emerald-500/5 border border-emerald-500/10 rounded-3xl">
                            <div class="flex items-center gap-4 mb-4 text-emerald-500">
                                <i data-feather="info" class="w-5 h-5"></i>
                                <span class="font-black uppercase tracking-[0.2em] text-xs">Security Briefing</span>
                            </div>
                            <p class="text-emerald-500/60 text-xs font-bold leading-relaxed uppercase tracking-widest">
                                @if($isStripe)
                                    PAYMENT TELEMETRY SECURED VIA STRIPE PROTOCOL. DISPATCH SEQUENCE INITIATED.
                                @else
                                    COD PROTOCOL ENGAGED. LOGISTICS TEAM WILL CONTACT YOUR TERMINAL FOR FINAL CLEARANCE.
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="py-20 text-center text-white/20 font-black uppercase tracking-widest text-sm italic">
                            No active transmissions detected.
                        </div>
                    @endif
                </div>

                <!-- Action Matrix -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-10">
                    <a href="{{ route('order.track') }}"
                        class="group bg-white rounded-[2rem] p-8 flex items-center justify-between transition-all hover:scale-[1.02] active:scale-95">
                        <div>
                            <span class="text-black/40 font-black text-[10px] uppercase tracking-widest block mb-1">Live
                                Tracking</span>
                            <span class="text-black font-black text-2xl italic uppercase tracking-tighter">Locate
                                Package.</span>
                        </div>
                        <div
                            class="w-12 h-12 bg-black rounded-xl flex items-center justify-center text-white group-hover:rotate-12 transition-transform">
                            <i data-feather="arrow-right"></i>
                        </div>
                    </a>

                    <a href="{{ route('products.index') }}"
                        class="group bg-[#111111] border border-white/5 rounded-[2rem] p-8 flex items-center justify-between transition-all hover:scale-[1.02] active:scale-95">
                        <div>
                            <span class="text-white/20 font-black text-[10px] uppercase tracking-widest block mb-1">New
                                Intel</span>
                            <span class="text-white font-black text-2xl italic uppercase tracking-tighter">Back to
                                Inventory.</span>
                        </div>
                        <div
                            class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-white group-hover:-rotate-12 transition-transform">
                            <i data-feather="shopping-bag"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Footer ID -->
            <p class="mt-20 text-center text-[9px] text-white/10 font-black uppercase tracking-[0.5em]">
                MAXUMAX DEPLOYMENT RECORD #{{ date('Ymd-His') }}
            </p>
        </div>
    </div>
@endsection