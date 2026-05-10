@extends('layouts.app')

@section('page-title', 'Preorder Details')
@section('page-subtitle', 'Detailed view of preorder status and customer information.')

@section('content')
<div class="space-y-6">
    <!-- Back Action -->
    <div>
        <a href="{{ route('admin.preorders.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors gap-2">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            Back to Preorders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status & Summary -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Preorder Summary</h3>
                    @php
                        $statusBadge = match($preorder->status) {
                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'confirmed' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                            'paid', 'completed', 'shipped', 'delivered' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'cancelled', 'rejected', 'refunded' => 'bg-rose-100 text-rose-700 border-rose-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusBadge }}">
                        {{ $preorder->status }}
                    </span>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                <i data-feather="user" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Customer</p>
                                <p class="font-bold text-slate-900">{{ $preorder->name }}</p>
                                <p class="text-sm text-slate-500">{{ $preorder->phone }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                <i data-feather="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Shipping Address</p>
                                <p class="text-sm text-slate-700 leading-relaxed">{{ $preorder->address }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                <i data-feather="calendar" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Preorder Date</p>
                                <p class="font-bold text-slate-900">{{ $preorder->created_at->format('M d, Y') }}</p>
                                <p class="text-sm text-slate-500">{{ $preorder->created_at->format('H:i A') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                <i data-feather="credit-card" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Payment</p>
                                <p class="text-sm text-slate-700 font-bold">Method: {{ strtoupper($preorder->payment_method ?? 'Stripe') }}</p>
                                <p class="text-xs text-slate-500">Intent: {{ $preorder->stripe_payment_intent_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Product Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Item Description</th>
                                <th class="px-6 py-4 text-center">Qty</th>
                                <th class="px-6 py-4 text-right">Price</th>
                                <th class="px-6 py-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900">{{ $preorder->jersey_type }}</span>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded uppercase">Size: {{ $preorder->size }}</span>
                                            @if($preorder->long_sleeve)
                                                <span class="text-[9px] font-black bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded uppercase">Long Sleeve</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-900">{{ $preorder->quantity }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xs text-slate-400">{{ $preorder->currency ?? 'MYR' }}</span>
                                    <span class="font-medium text-slate-900">{{ number_format($preorder->total_amount / $preorder->quantity, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xs text-slate-400">{{ $preorder->currency ?? 'MYR' }}</span>
                                    <span class="font-black text-slate-900">{{ number_format($preorder->total_amount, 2) }}</span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-slate-50/30">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-500 uppercase text-[10px] tracking-widest">Grand Total</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xs text-indigo-400 font-bold mr-1">{{ $preorder->currency ?? 'MYR' }}</span>
                                    <span class="text-xl font-black text-indigo-600">{{ number_format($preorder->total_amount, 2) }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="space-y-6">
            <!-- Action Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($preorder->status === 'pending')
                        <form action="{{ route('admin.preorders.confirm', $preorder) }}" method="POST" class="js-confirm" data-title="Confirm this preorder?" data-text="Status will be changed to confirmed.">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 gap-2">
                                <i data-feather="check-circle" class="w-4 h-4"></i>
                                Confirm Preorder
                            </button>
                        </form>
                    @endif

                    @if($preorder->status === 'confirmed')
                        <form action="{{ route('admin.preorders.markPaid', $preorder) }}" method="POST" class="js-confirm" data-title="Mark as paid?" data-text="Status will be changed to paid.">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 gap-2">
                                <i data-feather="dollar-sign" class="w-4 h-4"></i>
                                Mark as Paid
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.preorders.shipping', $preorder) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10 gap-2">
                        <i data-feather="truck" class="w-4 h-4"></i>
                        Shipping Rates
                    </a>

                    <form action="{{ route('admin.preorders.destroy', $preorder) }}" method="POST" class="js-delete" data-title="Delete this preorder?" data-text="This action cannot be undone.">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-white border border-rose-100 text-rose-600 text-sm font-bold rounded-xl hover:bg-rose-50 transition-all gap-2">
                            <i data-feather="trash-2" class="w-4 h-4"></i>
                            Delete Preorder
                        </button>
                    </form>
                </div>
            </div>

            <!-- Shipping Status -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Shipping Status</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase">Current Stage</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                            {{ $preorder->shipping_status ?? 'Pending' }}
                        </span>
                    </div>

                    @if($preorder->shipping_status === 'shipped')
                        <form action="{{ route('admin.preorders.markDelivered', $preorder) }}" method="POST" class="js-confirm" data-title="Mark as delivered?" data-text="Order will be marked as delivered.">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-emerald-500 text-white text-sm font-bold rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 gap-2">
                                <i data-feather="package" class="w-4 h-4"></i>
                                Mark Delivered
                            </button>
                        </form>
                    @endif

                    @if($preorder->tracking_no)
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tracking Number</p>
                            <p class="font-mono text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-2 rounded-xl flex items-center justify-between">
                                {{ $preorder->tracking_no }}
                                <button onclick="navigator.clipboard.writeText('{{ $preorder->tracking_no }}')" class="p-1 hover:bg-indigo-100 rounded transition-colors">
                                    <i data-feather="copy" class="w-3 h-3"></i>
                                </button>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Refund Management (If applicable) -->
            @if($preorder->stripe_payment_intent_id && in_array($preorder->status, ['confirmed', 'paid']))
                <div class="bg-rose-50 rounded-3xl shadow-sm border border-rose-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-rose-100 bg-rose-100/20">
                        <h3 class="text-sm font-bold text-rose-900 uppercase tracking-wider">Refund Management</h3>
                    </div>
                    <div class="p-6">
                        @if($preorder->refund_status === 'pending')
                            <div class="p-4 bg-white/60 border border-rose-200 rounded-2xl space-y-3">
                                <p class="text-xs font-bold text-rose-800">Refund Request Pending</p>
                                <p class="text-lg font-black text-rose-900">{{ $preorder->currency ?? 'MYR' }} {{ number_format($preorder->refund_amount, 2) }}</p>
                                @if($preorder->refund_reason)
                                    <p class="text-xs text-rose-600 italic">"{{ $preorder->refund_reason }}"</p>
                                @endif
                                <div class="grid grid-cols-2 gap-2 pt-2">
                                    <form action="{{ route('admin.preorders.approveRefund', $preorder) }}" method="POST" class="js-confirm" data-title="Approve refund?" data-text="Refund will be processed via Stripe.">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-emerald-600 text-white text-[10px] font-black uppercase rounded-lg hover:bg-emerald-700 transition-colors">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.preorders.rejectRefund', $preorder) }}" method="POST" class="js-confirm" data-title="Reject refund request?" data-text="Refund request will be rejected.">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-rose-600 text-white text-[10px] font-black uppercase rounded-lg hover:bg-rose-700 transition-colors">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-slate-500 font-medium">No pending refund requests.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Shared Confirmation Handler
        const handleAction = (selector, color) => {
            document.querySelectorAll(selector).forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: this.dataset.title,
                        text: this.dataset.text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: color,
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Confirm Action',
                        borderRadius: '1.5rem'
                    }).then((result) => {
                        if (result.isConfirmed) this.submit();
                    });
                });
            });
        };

        handleAction('.js-confirm', '#4f46e5');
        handleAction('.js-delete', '#ef4444');
    });
</script>
@endsection
