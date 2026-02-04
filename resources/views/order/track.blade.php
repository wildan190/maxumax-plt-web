@extends('layouts.public')

@section('title', 'Track Order - Maxumax')

@section('content')
    <section class="min-h-screen bg-slate-50 py-24 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-blue-600 font-black uppercase tracking-[0.3em] text-sm mb-4 inline-block">Order
                    Logistics</span>
                <h1
                    class="text-4xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase italic leading-none mb-6">
                    Track <span class="text-blue-600">Shipment.</span></h1>
                <p class="text-slate-500 font-medium text-lg max-w-xl mx-auto">Identify the current deployment status of
                    your Maxumax performance gear.</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-[3rem] p-8 md:p-12 border border-slate-100 shadow-2xl shadow-slate-200/50 mb-12">
                <form method="GET" action="{{ route('order.track') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-grow">
                        <i data-feather="hash" class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400"
                            style="width:20px;height:20px;"></i>
                        <input type="text" name="order" value="{{ $orderInput }}"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-14 pr-6 py-5 text-slate-900 font-black placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-all uppercase tracking-widest"
                            placeholder="ORDER ID (E.G. MM-XXXXX)" required />
                    </div>
                    <button type="submit"
                        class="bg-slate-900 text-white px-12 py-5 rounded-2xl font-black uppercase tracking-[0.2em] hover:bg-blue-600 hover:scale-105 active:scale-95 transition-all shadow-xl shadow-slate-900/10">
                        Search
                    </button>
                </form>

                @if($error)
                    <div
                        class="mt-8 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3 font-bold text-sm">
                        <i data-feather="alert-octagon" style="width:18px;height:18px;"></i>
                        {{ $error }}
                    </div>
                @endif
            </div>

            @if($preorder)
                <div class="space-y-8 animate-fade-in">
                    <!-- Status Header Card -->
                    <div
                        class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-slate-900/40 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2">
                        </div>

                        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                            <div>
                                <span
                                    class="text-blue-400 font-black text-xs uppercase tracking-[0.3em] block mb-2">Identification</span>
                                <h2 class="text-3xl font-black font-mono tracking-tighter">{{ $preorder->order_number }}</h2>
                            </div>

                            @php
                                $statusColors = [
                                    'paid' => 'bg-emerald-500',
                                    'confirmed' => 'bg-blue-500',
                                    'refunded' => 'bg-red-500',
                                    'pending' => 'bg-amber-500',
                                ];
                                $color = $statusColors[$preorder->status] ?? 'bg-slate-500';
                            @endphp

                            <div class="flex items-center gap-4">
                                <div class="text-right hidden md:block">
                                    <span
                                        class="text-slate-500 font-black text-[10px] uppercase tracking-widest block mb-1">Status</span>
                                    <span
                                        class="text-white font-black uppercase tracking-widest text-sm">{{ $preorder->status }}</span>
                                </div>
                                <div class="w-4 h-4 rounded-full {{ $color }} animate-pulse"></div>
                                <div
                                    class="px-6 py-2 rounded-full border border-white/10 font-black uppercase tracking-widest text-xs">
                                    {{ $preorder->status }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-xl shadow-slate-200/40">
                            <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.3em] mb-8">Order Details</h3>
                            <div class="space-y-6">
                                <div class="flex justify-between items-end pb-4 border-b border-slate-50">
                                    <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Product</span>
                                    <span
                                        class="text-slate-900 font-black text-right">{{ optional($preorder->product)->name ?? ($preorder->category ?? 'Individual Item') }}</span>
                                </div>
                                @php
                                    $variantSku = optional($preorder->variant)->sku;
                                @endphp
                                @if($preorder->size || $variantSku)
                                    <div class="flex justify-between items-end pb-4 border-b border-slate-50">
                                        <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Variant</span>
                                        <span class="text-slate-900 font-black text-right">
                                            {{ $preorder->size ?? optional($preorder->variant)->name ?? '-' }}
                                            @if($variantSku)
                                                <span class="text-slate-500 font-bold text-xs uppercase tracking-widest ml-2">SKU {{ $variantSku }}</span>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                                <div class="flex justify-between items-end pb-4 border-b border-slate-50">
                                    <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Quantity</span>
                                    <span class="text-slate-900 font-black">{{ $preorder->quantity }} Units</span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Total
                                        Investment</span>
                                    <span class="text-slate-900 font-black text-xl italic">{{ $preorder->currency ?? 'MYR' }}
                                        {{ number_format($preorder->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-xl shadow-slate-200/40">
                            <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.3em] mb-8">Deployment Info</h3>
                            <div class="flex items-start gap-4 mb-6">
                                <div
                                    class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 flex-shrink-0">
                                    <i data-feather="user" style="width:18px;height:18px;"></i>
                                </div>
                                <div>
                                    <p class="text-slate-900 font-black truncate max-w-[200px]">{{ $preorder->name }}</p>
                                    <p class="text-slate-500 font-medium text-xs">{{ $preorder->phone }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 flex-shrink-0">
                                    <i data-feather="map-pin" style="width:18px;height:18px;"></i>
                                </div>
                                <p class="text-slate-500 font-medium text-xs leading-relaxed">
                                    {{ $preorder->address ?? 'Location data encrypted' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white rounded-[2.5rem] p-12 border border-slate-100 shadow-xl shadow-slate-200/40">
                        <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.3em] mb-12 flex items-center gap-4">
                            Logistics Timeline
                            <div class="h-px bg-slate-100 flex-grow"></div>
                        </h3>

                        @if(!empty($preorder->tracking_number))
                            <div class="mb-12">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Tracking</span>
                                        <span class="text-slate-900 font-black">{{ $preorder->tracking_number }}</span>
                                    </div>
                                    @if(!empty($tracking) && isset($tracking['api_status']) && $tracking['api_status']==='Success')
                                        @php
                                            $trackItem = $tracking['result'][0] ?? [];
                                            $currentStatus = $trackItem['status'] ?? 'In Transit';
                                        @endphp
                                        <div class="px-4 py-2 rounded-full bg-slate-50 border border-slate-100 text-slate-700 font-black text-xs uppercase tracking-widest">
                                            {{ $currentStatus }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div
                            class="relative space-y-12 before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-50">
                            {{-- History Loop --}}
                            @if($preorder->histories && $preorder->histories->count() > 0)
                                @foreach($preorder->histories->sortByDesc('created_at') as $index => $history)
                                    <div class="relative pl-12 group">
                                        <div
                                            class="absolute left-0 top-1 w-10 h-10 bg-white border border-slate-100 rounded-full flex items-center justify-center z-10 transition-all group-hover:border-blue-600 group-hover:scale-110">
                                            <div
                                                class="w-3 h-3 {{ $index === 0 ? 'bg-blue-600 shadow-lg shadow-blue-600/40' : 'bg-slate-200' }} rounded-full">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-slate-900 font-black italic uppercase tracking-tight text-lg mb-1">
                                                {{ $history->note ?? 'STATUS UPDATED' }}
                                            </div>
                                            <div class="text-slate-400 font-bold text-xs uppercase tracking-widest">
                                                {{ $history->created_at->format('d M Y, H:i') }} UTC+8
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Initial State --}}
                            <div class="relative pl-12 group">
                                <div
                                    class="absolute left-0 top-1 w-10 h-10 bg-white border border-slate-100 rounded-full flex items-center justify-center z-10 transition-all group-hover:border-emerald-500">
                                    <div
                                        class="w-3 h-3 {{ !$preorder->histories || $preorder->histories->count() === 0 ? 'bg-emerald-500 shadow-lg shadow-emerald-500/40' : 'bg-slate-200' }} rounded-full">
                                    </div>
                                </div>
                                <div>
                                    <div class="text-slate-900 font-black italic uppercase tracking-tight text-lg mb-1">ORDER
                                        CONFIRMED</div>
                                    <div class="text-slate-400 font-bold text-xs uppercase tracking-widest">
                                        {{ $preorder->created_at->format('d M Y, H:i') }} UTC+8
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complaint Status (If exists) -->
                    @php $activeComplaint = $preorder->complaints->sortByDesc('created_at')->first(); @endphp
                    @if($activeComplaint)
                        <div class="bg-amber-50 border border-amber-100 p-10 rounded-[2.5rem] shadow-xl shadow-amber-900/5 animate-fade-in mt-8">
                            <div class="flex flex-wrap items-center gap-4 mb-6">
                                <span class="text-amber-600 font-black uppercase tracking-[0.3em] text-[10px]">Active Complaint Deployment</span>
                                <div class="px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-xs font-black uppercase tracking-widest">
                                    {{ $activeComplaint->status }}
                                </div>
                            </div>
                            <div class="space-y-4">
                                <p class="text-slate-900 font-black text-xl italic leading-tight">"{{ Str::limit($activeComplaint->reason, 150) }}"</p>
                                <div class="flex items-center gap-6 pt-2">
                                    <a href="{{ route('complaints.show', $activeComplaint->id) }}" class="text-blue-600 font-black uppercase tracking-widest text-xs flex items-center gap-2 hover:gap-3 transition-all">
                                        View Case Details
                                        <i data-feather="arrow-right" style="width:14px;height:14px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Footer Actions -->
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-12">
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Last Synced:
                            {{ now()->format('H:i:s') }} Global Relay
                        </p>
                        <div class="flex flex-wrap gap-4 w-full md:w-auto justify-center md:justify-end">
                            @if($preorder->shipping_status === 'shipped')
                                <form action="{{ route('preorder.markDelivered', $preorder->order_number) }}" method="POST"
                                    id="receivedForm" class="hidden">
                                    @csrf
                                </form>
                                <button type="button" onclick="confirmReceived()"
                                    class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:scale-105 transition-all shadow-xl shadow-blue-600/20 flex items-center justify-center gap-2">
                                    <i data-feather="check-circle" style="width:14px;height:14px;"></i>
                                    Confirm Receipt
                                </button>
                            @endif

                            @if($preorder->shipping_status === 'delivered')
                                @php
                                    $deliveryTime = $preorder->getDeliveryTimestamp();
                                    $canComplain = false;
                                    if ($deliveryTime) {
                                        $expiresAt = \Carbon\Carbon::parse($deliveryTime)->addDays(7);
                                        $canComplain = now()->isBefore($expiresAt);
                                    }
                                    $hasActive = $activeComplaint && in_array($activeComplaint->status, ['pending', 'approved']);
                                @endphp

                                @if($canComplain && !$hasActive)
                                    <a href="{{ route('complaints.create', $preorder->order_number) }}"
                                        class="bg-amber-500 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:scale-105 transition-all shadow-xl shadow-amber-500/20 flex items-center justify-center gap-2">
                                        <i data-feather="alert-circle" style="width:14px;height:14px;"></i>
                                        File Complaint
                                    </a>
                                @endif
                            @endif

                            <a href="{{ route('preorder.thankyou', ['uuid' => $preorder->uuid]) }}"
                                class="bg-slate-100 text-slate-900 px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                                <i data-feather="file-text" style="width:14px;height:14px;"></i>
                                Invoice
                            </a>
                            <a href="https://wa.me/xxxxxxxx" target="_blank"
                                class="bg-emerald-500 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:scale-105 transition-all shadow-xl shadow-emerald-500/20 flex items-center justify-center gap-2">
                                <i data-feather="message-circle" style="width:14px;height:14px;"></i>
                                Support
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmReceived() {
            Swal.fire({
                title: 'SUDAH TERIMA BARANG?',
                text: "Konfirmasi jika Anda sudah menerima gear Maxumax dengan kondisi baik.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'YA, SUDAH TERIMA',
                cancelButtonText: 'BATAL'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('receivedForm').submit();
                }
            })
        }

        @if(session('status'))
            Swal.fire({
                icon: 'success',
                title: 'BERHASIL',
                text: "{{ session('status') }}",
                confirmButtonColor: '#2563eb'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'GAGAL',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444'
            });
        @endif
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
@endsection
