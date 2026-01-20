@extends('layouts.public')

@section('title', 'Track Order')

@section('content')
    <section class="max-w-3xl mx-auto px-4 py-12 min-h-[60vh]">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">Track Your Order</h1>
            <p class="text-gray-500 text-lg">Enter your order ID to check the current status.</p>
        </div>

        <div class="max-w-xl mx-auto mb-12">
            <form method="GET" action="{{ route('order.track') }}" class="flex gap-2 relative">
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i data-feather="search" class="w-5 h-5"></i>
                    </span>
                    <input type="text" name="order" value="{{ $orderInput }}" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition-all shadow-sm text-gray-800 placeholder-gray-400 font-medium"
                        placeholder="e.g. MM-ABC12345" required />
                </div>
                <button type="submit" class="bg-black text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition-colors shadow-md whitespace-nowrap">
                    Track
                </button>
            </form>
            
            @if($error)
                <div class="mt-4 bg-red-50 text-red-700 px-4 py-3 rounded-xl border border-red-100 flex items-center gap-2 text-sm font-medium animate-fade-in-up">
                    <i data-feather="alert-circle" class="w-4 h-4"></i>
                    {{ $error }}
                </div>
            @endif
        </div>

        @if($preorder)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-fade-in-up">
                <!-- Card Header -->
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Order Number</div>
                        <div class="text-xl font-black text-gray-900 font-mono tracking-tight">{{ $preorder->order_number }}</div>
                    </div>
                    @php
                        $statusClasses = [
                            'paid' => 'bg-green-100 text-green-700 border-green-200',
                            'confirmed' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'refunded' => 'bg-red-100 text-red-700 border-red-200',
                            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        ];
                        $statusClass = $statusClasses[$preorder->status] ?? $statusClasses['pending'];
                    @endphp
                    <div class="px-3 py-1 rounded-full border text-xs font-bold uppercase tracking-wide {{ $statusClass }}">
                        {{ ucfirst($preorder->status) }}
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Product Info -->
                        <div class="space-y-4">
                             <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Product</label>
                                <div class="font-bold text-gray-900 text-lg leading-tight">
                                    {{ optional($preorder->product)->name ?? ($preorder->jersey_type ?? '-') }}
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Quantity</label>
                                    <div class="font-semibold text-gray-800">{{ $preorder->quantity }} pcs</div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total</label>
                                    <div class="font-semibold text-gray-800">{{ $preorder->currency ?? 'MYR' }} {{ number_format($preorder->total_amount, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                             <div class="flex items-start gap-3 mb-3">
                                <i data-feather="user" class="w-4 h-4 text-gray-400 mt-1"></i>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $preorder->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $preorder->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $preorder->phone }}</div>
                                </div>
                             </div>
                             @if($preorder->address)
                                <div class="flex items-start gap-3 border-t border-gray-200 pt-3 mt-2">
                                    <i data-feather="map-pin" class="w-4 h-4 text-gray-400 mt-1"></i>
                                    <div class="text-sm text-gray-600 leading-relaxed">{{ $preorder->address }}</div>
                                </div>
                             @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    @if($preorder->shipping_status || ($preorder->histories && $preorder->histories->count() > 0))
                        <div class="border-t border-gray-100 pt-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <i data-feather="truck" class="w-5 h-5 text-gray-400"></i>
                                Order History
                            </h3>
                            
                            <div class="relative pl-4 space-y-8 before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-[2px] before:bg-gray-100">
                                
                                {{-- Histories Loop --}}
                                @if($preorder->histories && $preorder->histories->count() > 0)
                                    @foreach($preorder->histories->sortByDesc('created_at') as $history)
                                        <div class="relative pl-8">
                                            <div class="absolute left-0 top-1 w-10 h-10 bg-white border-4 border-gray-100 rounded-full flex items-center justify-center z-10">
                                                <div class="w-2.5 h-2.5 bg-gray-400 rounded-full"></div>
                                            </div>
                                            <div class="pt-1">
                                                <div class="font-semibold text-gray-800">{{ $history->note ?? 'Status Updated' }}</div>
                                                <div class="text-sm text-gray-400 mt-0.5">{{ $history->created_at->format('d M Y, H:i') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                {{-- Initial Order Confirmed (Fallback if no history but order exists) --}}
                                @if(!$preorder->histories || $preorder->histories->count() == 0)
                                    <div class="relative pl-8">
                                        <div class="absolute left-0 top-1 w-10 h-10 bg-white border-4 border-green-100 rounded-full flex items-center justify-center z-10">
                                            <div class="w-2.5 h-2.5 bg-green-500 rounded-full"></div>
                                        </div>
                                        <div class="pt-1">
                                            <div class="font-semibold text-gray-800">Order Confirmed</div>
                                            <div class="text-sm text-gray-400 mt-0.5">{{ $preorder->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                    </div>
                                @endif
                                
                            </div>
                        </div>
                    @endif



                    <!-- Complaint Section -->
                    @php
                        // Use helper from model to get delivery timestamp
                        $deliveryTime = $preorder->getDeliveryTimestamp();
                        
                        // Can file complaint if delivered within 7 days
                        $canFileComplaint = $deliveryTime && now()->subDays(7)->isBefore(\Carbon\Carbon::parse($deliveryTime));
                        
                        // Check for existing complaint
                        $existingComplaint = $preorder->complaints?->whereIn('status', ['pending', 'approved'])->first();
                    @endphp

                    @if($canFileComplaint || $existingComplaint)
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-feather="alert-circle" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                                <div class="flex-1">
                                    @if($existingComplaint)
                                        <h4 class="font-semibold text-blue-900 mb-1">Active Complaint</h4>
                                        <p class="text-sm text-blue-700 mb-3">You have an active complaint for this order.</p>
                                        <a href="{{ route('complaints.show', ['complaint' => $existingComplaint->id]) }}" 
                                           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                                            View Complaint Status →
                                        </a>
                                    @else
                                        <h4 class="font-semibold text-blue-900 mb-1">Need Help?</h4>
                                        <p class="text-sm text-blue-700 mb-3">
                                            If there's an issue with your order, you can file a complaint for a refund or replacement.
                                            @if($deliveryTime)
                                                <span class="font-semibold">(Expires {{ \Carbon\Carbon::parse($deliveryTime)->addDays(7)->diffForHumans() }})</span>
                                            @endif
                                        </p>
                                        <a href="{{ route('complaints.create', ['preorder' => $preorder->id]) }}" 
                                           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                                            <i data-feather="file-text" class="w-4 h-4 mr-2"></i>
                                            File a Complaint
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap gap-3 justify-end">
                         @if($preorder->shipping_status === 'shipped')
                            <form action="{{ route('preorder.markDelivered', $preorder) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Confirm order received?')" 
                                    class="bg-green-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-green-700 transition-colors shadow-sm flex items-center gap-2">
                                    <i data-feather="check-circle" class="w-4 h-4"></i>
                                    Confirm Received
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('preorder.thankyou', ['uuid' => $preorder->uuid]) }}" 
                           class="bg-gray-900 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-black transition-colors shadow-sm flex items-center gap-2">
                            View Invoice
                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
    </style>
@endsection
