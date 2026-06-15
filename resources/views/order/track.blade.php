@extends('layouts.public')

@section('title', 'Track Order - Maxumax')

@section('content')
    <section class="min-h-screen bg-white py-12 md:py-24 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <span class="text-[#155EEF] font-black uppercase tracking-[0.3em] text-[10px] mb-4 inline-block">Order
                    Status</span>
                <h1 class="text-2xl md:text-5xl font-black text-[#111111] tracking-tighter uppercase italic leading-none mb-4">
                    Track <span class="text-[#999999]">Shipment.</span></h1>
                <p class="text-[#666666] font-medium text-sm md:text-base max-w-xl mx-auto">Stay updated as your order makes its way to you.</p>
            </div>

            <!-- Search Form -->
            <div class="bg-[#F7F7F5] rounded-2xl p-6 md:p-8 border border-[#E8E8E3] shadow-sm mb-8">
                <form method="GET" action="{{ route('order.track') }}" class="flex flex-col md:flex-row gap-3">
                    <div class="relative flex-grow">
                        <i data-feather="hash" class="absolute left-4 top-1/2 -translate-y-1/2 text-[#999999]"
                            style="width:18px;height:18px;"></i>
                        <input type="text" name="order" value="{{ $orderInput }}"
                            class="w-full bg-white border border-[#E8E8E3] rounded-xl pl-12 pr-5 py-3.5 text-[#111111] font-black placeholder-[#999999] focus:outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10 transition-all uppercase tracking-widest text-sm"
                            placeholder="ORDER ID (E.G. MM-XXXXX)" required />
                    </div>
                    <button type="submit"
                        class="bg-[#155EEF] text-white px-10 py-3.5 rounded-xl font-black uppercase tracking-[0.2em] hover:bg-[#0d46b3] hover:scale-105 active:scale-95 transition-all shadow-lg shadow-[#155EEF]/20">
                        Search
                    </button>
                </form>

                @if($error)
                    <div
                        class="mt-6 p-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl flex items-center gap-3 font-bold text-sm">
                        <i data-feather="alert-octagon" style="width:18px;height:18px;"></i>
                        {{ $error }}
                    </div>
                @endif
            </div>

            @if($preorder)
                <div class="space-y-5 animate-fade-in">
                    <!-- Status Header Card -->
                    <div
                        class="bg-white rounded-2xl p-6 md:p-8 text-[#111111] border border-[#E8E8E3] shadow-sm">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div>
                                <span
                                    class="text-[#999999] font-black text-[10px] uppercase tracking-[0.3em] block mb-2">Identification</span>
                                <h2 class="text-2xl font-black font-mono tracking-tighter">{{ $preorder->order_number }}</h2>
                            </div>

                            @php
                                $statusColors = [
                                    'paid' => 'bg-emerald-500',
                                    'confirmed' => 'bg-[#155EEF]',
                                    'refunded' => 'bg-rose-500',
                                    'pending' => 'bg-amber-500',
                                ];
                                $color = $statusColors[$preorder->status] ?? 'bg-slate-500';
                            @endphp

                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full {{ $color }} animate-pulse"></div>
                                <div
                                    class="px-4 py-1.5 rounded-full border border-[#E8E8E3] font-black uppercase tracking-widest text-[10px]">
                                    {{ $preorder->status }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-2xl p-6 border border-[#E8E8E3] shadow-sm">
                            <h3 class="text-[10px] font-black text-[#999999] uppercase tracking-[0.3em] mb-5">Order Details</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-end pb-3 border-b border-[#E8E8E3]">
                                    <span class="text-[#999999] font-bold text-[11px] uppercase tracking-widest">Product</span>
                                    <span
                                        class="text-[#111111] font-black text-right text-sm">{{ optional($preorder->product)->name ?? ($preorder->category ?? 'Individual Item') }}</span>
                                </div>
                                @if($preorder->size)
                                    <div class="flex justify-between items-end pb-3 border-b border-[#E8E8E3]">
                                        <span class="text-[#999999] font-bold text-[11px] uppercase tracking-widest">Variant</span>
                                        <span class="text-[#111111] font-black text-right text-sm">
                                            {{ $preorder->size ?? optional($preorder->variant)->name ?? '-' }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex justify-between items-end pb-3 border-b border-[#E8E8E3]">
                                    <span class="text-[#999999] font-bold text-[11px] uppercase tracking-widest">Quantity</span>
                                    <span class="text-[#111111] font-black text-sm">{{ $preorder->quantity }} Units</span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <span class="text-[#999999] font-bold text-[11px] uppercase tracking-widest">Total
                                        Investment</span>
                                    <span class="text-[#111111] font-black text-lg italic">{{ $preorder->currency ?? 'MYR' }}
                                        {{ number_format($preorder->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 border border-[#E8E8E3] shadow-sm">
                            <h3 class="text-[10px] font-black text-[#999999] uppercase tracking-[0.3em] mb-5">Deployment Info
                            </h3>
                            <div class="flex items-start gap-3 mb-4">
                                <div
                                    class="w-9 h-9 bg-[#F7F7F5] rounded-lg flex items-center justify-center text-[#999999] flex-shrink-0">
                                    <i data-feather="user" style="width:16px;height:16px;"></i>
                                </div>
                                <div>
                                    <p class="text-[#111111] font-black truncate max-w-[200px] text-sm">{{ $preorder->name }}</p>
                                    <p class="text-[#999999] font-medium text-[11px]">{{ $preorder->phone }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 mb-5">
                                <div
                                    class="w-9 h-9 bg-[#F7F7F5] rounded-lg flex items-center justify-center text-[#999999] flex-shrink-0">
                                    <i data-feather="map-pin" style="width:16px;height:16px;"></i>
                                </div>
                                <p class="text-[#666666] font-medium text-[11px] leading-relaxed">
                                    {{ $preorder->address ?? 'Location data encrypted' }}
                                </p>
                            </div>
                            <div class="grid grid-cols-1 gap-3">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-9 h-9 bg-[#F7F7F5] rounded-lg flex items-center justify-center text-[#999999] flex-shrink-0">
                                        <i data-feather="truck" style="width:16px;height:16px;"></i>
                                    </div>
                                    <div>
                                        <p class="text-[#111111] font-black text-sm">{{ $preorder->shipping_courier_name ?? 'Shipping' }}
                                        </p>
                                        <p class="text-[#999999] font-medium text-[11px]">
                                            {{ $preorder->shipping_service_name ?? 'Standard' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[#999999] font-bold text-[11px] uppercase tracking-widest">Shipping Cost</span>
                                    @if(($preorder->shipping_cost ?? 0) > 0)
                                        <span class="text-[#111111] font-black text-sm">{{ $preorder->currency ?? 'MYR' }}
                                            {{ number_format($preorder->shipping_cost, 2) }}</span>
                                    @else
                                        <span class="text-[#999999] font-medium text-[11px]">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white rounded-2xl p-6 md:p-8 border border-[#E8E8E3] shadow-sm">
                        <h3
                            class="text-[10px] font-black text-[#999999] uppercase tracking-[0.3em] mb-6 flex items-center gap-3">
                            Logistics Timeline
                            <div class="h-px bg-[#E8E8E3] flex-grow"></div>
                        </h3>

                        @if(!empty($preorder->tracking_number))
                            <div class="mb-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[#999999] font-bold text-[11px] uppercase tracking-widest">Tracking</span>
                                        <span class="text-[#111111] font-black text-sm">{{ $preorder->tracking_number }}</span>
                                    </div>
                                    @if(!empty($tracking) && isset($tracking['api_status']) && $tracking['api_status'] === 'Success')
                                                        @php
                                                            $trackItem = $tracking['result'][0] ?? [];
                                                            $currentStatus = $trackItem['status'] ?? 'In Transit';
                                                        @endphp
                                        <div
                                                            class="px-3 py-1.5 rounded-full bg-[#F7F7F5] border border-[#E8E8E3] text-[#111111] font-black text-[10px] uppercase tracking-widest">
                                                            {{ $currentStatus }}
                                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div
                            class="relative space-y-6 before:absolute before:left-[14px] before:top-1 before:bottom-1 before:w-[2px] before:bg-[#E8E8E3]">
                            {{-- History Loop --}}
                            @if($preorder->histories && $preorder->histories->count() > 0)
                                @foreach($preorder->histories->sortByDesc('created_at') as $index => $history)
                                    <div class="relative pl-10 group">
                                        <div
                                            class="absolute left-0 top-0.5 w-8 h-8 bg-white border border-[#E8E8E3] rounded-full flex items-center justify-center z-10 transition-all group-hover:border-[#155EEF] group-hover:scale-110">
                                            <div
                                                class="w-2.5 h-2.5 {{ $index === 0 ? 'bg-[#155EEF] shadow-lg shadow-[#155EEF]/20' : 'bg-[#E8E8E3]' }} rounded-full">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-[#111111] font-black italic uppercase tracking-tight text-base mb-0.5">
                                                {{ $history->note ?? 'STATUS UPDATED' }}
                                            </div>
                                            <div class="text-[#999999] font-bold text-[10px] uppercase tracking-widest">
                                                {{ $history->created_at->format('d M Y, H:i') }} UTC+8
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Initial State --}}
                            <div class="relative pl-10 group">
                                <div
                                    class="absolute left-0 top-0.5 w-8 h-8 bg-white border border-[#E8E8E3] rounded-full flex items-center justify-center z-10 transition-all group-hover:border-emerald-500">
                                    <div
                                        class="w-2.5 h-2.5 {{ !$preorder->histories || $preorder->histories->count() === 0 ? 'bg-emerald-500 shadow-lg shadow-emerald-500/20' : 'bg-[#E8E8E3]' }} rounded-full">
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[#111111] font-black italic uppercase tracking-tight text-base mb-0.5">ORDER
                                        CONFIRMED</div>
                                    <div class="text-[#999999] font-bold text-[10px] uppercase tracking-widest">
                                        {{ $preorder->created_at->format('d M Y, H:i') }} UTC+8
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complaint Status (If exists) -->
                    @php $activeComplaint = $preorder->complaints->sortByDesc('created_at')->first(); @endphp
                    @if($activeComplaint)
                        <div
                            class="bg-amber-50 border border-amber-200 p-6 rounded-2xl shadow-sm animate-fade-in">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span class="text-amber-600 font-black uppercase tracking-[0.3em] text-[10px]">Active Complaint
                                    Deployment</span>
                                <div
                                    class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    {{ $activeComplaint->status }}
                                </div>
                            </div>
                            <div class="space-y-3">
                                <p class="text-[#111111] font-black text-base italic leading-tight">
                                    "{{ Str::limit($activeComplaint->reason, 150) }}"</p>
                                <div class="flex items-center gap-4 pt-1">
                                    <a href="{{ route('complaints.show', $activeComplaint->id) }}"
                                        class="text-[#999999] font-black uppercase tracking-widest text-[10px] flex items-center gap-1.5 hover:gap-2.5 transition-all hover:text-[#111111]">
                                        View Case Details
                                        <i data-feather="arrow-right" style="width:12px;height:12px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Footer Actions -->
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-2">
                        <p class="text-[9px] text-[#999999] font-black uppercase tracking-widest">Last Synced:
                            {{ now()->format('H:i:s') }} Global Relay
                        </p>
                        <div class="flex flex-wrap gap-2 w-full md:w-auto justify-center md:justify-end">
                            @if($preorder->shipping_status === 'shipped')
                                <form action="{{ route('preorder.markDelivered', $preorder->order_number) }}" method="POST"
                                    id="receivedForm" class="hidden">
                                    @csrf
                                </form>
                                <button type="button" onclick="confirmReceived()"
                                    class="bg-[#155EEF] text-white px-5 py-3 rounded-xl font-black uppercase tracking-widest text-[11px] hover:scale-105 transition-all shadow-md flex items-center justify-center gap-2">
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
                                        class="bg-amber-500 text-white px-5 py-3 rounded-xl font-black uppercase tracking-widest text-[11px] hover:scale-105 transition-all shadow-md flex items-center justify-center gap-2">
                                        <i data-feather="alert-circle" style="width:14px;height:14px;"></i>
                                        File Complaint
                                    </a>
                                @endif
                            @endif

                            <a href="{{ route('preorder.thankyou', ['uuid' => $preorder->uuid]) }}"
                                class="bg-white text-[#111111] border border-[#E8E8E3] px-5 py-3 rounded-xl font-black uppercase tracking-widest text-[11px] hover:bg-[#F7F7F5] transition-all flex items-center justify-center gap-2">
                                <i data-feather="file-text" style="width:14px;height:14px;"></i>
                                Invoice
                            </a>
                            <a href="https://wa.me/xxxxxxxx" target="_blank"
                                class="bg-emerald-500 text-white px-5 py-3 rounded-xl font-black uppercase tracking-widest text-[11px] hover:scale-105 transition-all shadow-md flex items-center justify-center gap-2">
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
                confirmButtonColor: '#155EEF',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'YA, SUDAH TERIMA',
                cancelButtonText: 'BATAL'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('receivedForm').submit();
                }
            })
        }

        const sessionStatus = "{{ session('status') }}";
        if (sessionStatus) {
            Swal.fire({
                icon: 'success',
                title: 'BERHASIL',
                text: sessionStatus,
                confirmButtonColor: '#155EEF'
            });
        }

        const sessionError = "{{ session('error') }}";
        if (sessionError) {
            Swal.fire({
                icon: 'error',
                title: 'GAGAL',
                text: sessionError,
                confirmButtonColor: '#ef4444'
            });
        }
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
