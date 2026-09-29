@extends('layouts.app')

@section('page-title', 'Order #' . $order->order_number)
@section('page-subtitle', 'Detailed view of customer purchase and payment status.')

@section('content')
<div class="space-y-6">
    <!-- Back Action -->
    <div>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors gap-2">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status & Summary -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Order Summary</h3>
                    @php
                        $statusBadge = match($order->status) {
                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'confirmed' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                            'paid' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'pickup' => 'bg-sky-100 text-sky-700 border-sky-200',
                            'delivered', 'completed', 'shipped' => 'bg-teal-100 text-teal-700 border-teal-200',
                            'cancelled', 'rejected', 'refunded' => 'bg-rose-100 text-rose-700 border-rose-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusBadge }}">
                        {{ $order->status }}
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
                                <p class="font-bold text-slate-900">{{ $order->name }}</p>
                                <p class="text-sm text-slate-500">{{ $order->email ?? 'No email provided' }}</p>
                                <p class="text-sm text-slate-500">{{ $order->phone }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                <i data-feather="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Shipping Address</p>
                                <p class="text-sm text-slate-700 leading-relaxed">{{ $order->address }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                <i data-feather="calendar" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Order Date</p>
                                <p class="font-bold text-slate-900">{{ $order->created_at->format('M d, Y') }}</p>
                                <p class="text-sm text-slate-500">{{ $order->created_at->format('H:i A') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                <i data-feather="credit-card" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Payment</p>
                                <p class="text-sm text-slate-700 font-bold">Method: {{ strtoupper($order->payment_method ?? 'Stripe') }}</p>
                                <p class="text-xs text-slate-500">Intent: {{ $order->stripe_payment_intent_id ?? 'N/A' }}</p>
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
                            @php
                                $itemsList = !empty($order->items) && is_array($order->items) ? $order->items : [];
                                $shippingCost = (float) ($order->shipping_cost ?? 0);
                                $itemsSubtotal = 0;
                            @endphp

                            @if(count($itemsList) > 0)
                                @foreach($itemsList as $item)
                                    @php
                                        $qty = (int) ($item['quantity'] ?? 1);
                                        $itemUnitPrice = (float) ($item['unit_price'] ?? ($item['total_price'] ?? 0) / max(1, $qty));
                                        $itemLineTotal = (float) ($item['total_price'] ?? ($itemUnitPrice * $qty));
                                        $itemsSubtotal += $itemLineTotal;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-900">{{ optional($order->product)->name ?? 'Item' }}</span>
                                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                                    @if(!empty($item['variant_name']))
                                                        <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded uppercase">
                                                            {{ $item['variant_name'] }}
                                                        </span>
                                                    @endif
                                                    @if(!empty($item['long_sleeve']))
                                                        <span class="text-[9px] font-black bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded uppercase">Long Sleeve</span>
                                                    @endif
                                                    @if(!empty($item['custom_fields']))
                                                        @foreach($item['custom_fields'] as $cfName => $cfVal)
                                                            @if(!empty($cfVal))
                                                                <span class="text-[9px] font-semibold bg-amber-50 text-amber-700 px-1.5 py-0.5 rounded">
                                                                    {{ ucfirst($cfName) }}: {{ is_array($cfVal) ? implode(', ', $cfVal) : $cfVal }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-slate-900">{{ $qty }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-xs text-slate-400">{{ $order->currency ?? 'MYR' }}</span>
                                            <span class="font-medium text-slate-900">{{ number_format($itemUnitPrice, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-xs text-slate-400">{{ $order->currency ?? 'MYR' }}</span>
                                            <span class="font-black text-slate-900">{{ number_format($itemLineTotal, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $unitPrice = $order->unit_price > 0 ? (float) $order->unit_price : max(0, ((float) $order->total_amount - $shippingCost) / max(1, (int) $order->quantity));
                                    $itemsSubtotal = $unitPrice * (int) $order->quantity;
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900">{{ optional($order->product)->name ?? $order->jersey_type }}</span>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded uppercase">Size: {{ $order->size }}</span>
                                                @if($order->long_sleeve)
                                                    <span class="text-[9px] font-black bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded uppercase">Long Sleeve</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-900">{{ $order->quantity }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs text-slate-400">{{ $order->currency ?? 'MYR' }}</span>
                                        <span class="font-medium text-slate-900">{{ number_format($unitPrice, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs text-slate-400">{{ $order->currency ?? 'MYR' }}</span>
                                        <span class="font-black text-slate-900">{{ number_format($itemsSubtotal, 2) }}</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-slate-50/60 divide-y divide-slate-100">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right font-bold text-slate-500 uppercase text-[10px] tracking-widest">Items Subtotal</td>
                                <td class="px-6 py-3 text-right">
                                    <span class="text-xs text-slate-400 font-bold mr-1">{{ $order->currency ?? 'MYR' }}</span>
                                    <span class="font-bold text-slate-800">{{ number_format($itemsSubtotal, 2) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right font-bold text-slate-500 uppercase text-[10px] tracking-widest">
                                    Postage / Shipping
                                    @if($order->shipping_courier_name)
                                        <span class="text-[10px] font-normal lowercase text-slate-400">({{ $order->shipping_courier_name }})</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right">
                                    @if($shippingCost > 0)
                                        <span class="text-xs text-slate-400 font-bold mr-1">{{ $order->currency ?? 'MYR' }}</span>
                                        <span class="font-bold text-slate-800">{{ number_format($shippingCost, 2) }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">FREE / RM 0.00</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="bg-slate-100/50">
                                <td colspan="3" class="px-6 py-4 text-right font-black text-slate-800 uppercase text-[11px] tracking-widest">Grand Total</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xs text-indigo-500 font-bold mr-1">{{ $order->currency ?? 'MYR' }}</span>
                                    <span class="text-xl font-black text-indigo-600">{{ number_format($order->total_amount, 2) }}</span>
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
                    @if($order->status === 'pending')
                        <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" class="js-confirm" data-title="Confirm this order?" data-text="Status will be changed to confirmed.">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 gap-2">
                                <i data-feather="check-circle" class="w-4 h-4"></i>
                                Confirm Order
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'confirmed')
                        <form action="{{ route('admin.orders.markPaid', $order) }}" method="POST" class="js-confirm" data-title="Mark as paid?" data-text="Status will be changed to paid.">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 gap-2">
                                <i data-feather="dollar-sign" class="w-4 h-4"></i>
                                Mark as Paid
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.orders.shipping', $order) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10 gap-2">
                        <i data-feather="truck" class="w-4 h-4"></i>
                        Shipping Rates
                    </a>

                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="js-delete" data-title="Delete this order?" data-text="This action cannot be undone.">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-white border border-rose-100 text-rose-600 text-sm font-bold rounded-xl hover:bg-rose-50 transition-all gap-2">
                            <i data-feather="trash-2" class="w-4 h-4"></i>
                            Delete Order
                        </button>
                    </form>
                </div>
            </div>

            <!-- Manual Status Edit Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Manual Status Update</h3>
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Admin Control</span>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="space-y-4 js-confirm" data-title="Update Order Status?" data-text="Status order dan pengiriman akan diperbarui secara manual.">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Order Status</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pickup" {{ $order->status === 'pickup' ? 'selected' : '' }}>Pickup (Ready / Picked Up)</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Shipping Status</label>
                            <select name="shipping_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                <option value="pending" {{ ($order->shipping_status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="packing" {{ ($order->shipping_status ?? '') === 'packing' ? 'selected' : '' }}>Packing</option>
                                <option value="shipped" {{ ($order->shipping_status ?? '') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="pickup" {{ ($order->shipping_status ?? '') === 'pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                <option value="delivered" {{ ($order->shipping_status ?? '') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="returned" {{ ($order->shipping_status ?? '') === 'returned' ? 'selected' : '' }}>Returned</option>
                                <option value="cancelled" {{ ($order->shipping_status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tracking Number</label>
                            <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="e.g. EP-MY-12345678"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-mono text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Admin Notes (Optional)</label>
                            <textarea name="notes" rows="2" placeholder="Catatan perubahan manual..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">{{ old('notes', $order->notes) }}</textarea>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 gap-2">
                            <i data-feather="save" class="w-4 h-4"></i>
                            Save Status Changes
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
                            {{ $order->shipping_status ?? 'Pending' }}
                        </span>
                    </div>

                    @if($order->shipping_status === 'shipped')
                        <form action="{{ route('admin.orders.markDelivered', $order) }}" method="POST" class="js-confirm" data-title="Mark as delivered?" data-text="Order will be marked as delivered.">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-emerald-500 text-white text-sm font-bold rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 gap-2">
                                <i data-feather="package" class="w-4 h-4"></i>
                                Mark Delivered
                            </button>
                        </form>
                    @endif

                    @if($order->tracking_no)
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tracking Number</p>
                            <p class="font-mono text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-2 rounded-xl flex items-center justify-between">
                                {{ $order->tracking_no }}
                                <button onclick="navigator.clipboard.writeText('{{ $order->tracking_no }}')" class="p-1 hover:bg-indigo-100 rounded transition-colors">
                                    <i data-feather="copy" class="w-3 h-3"></i>
                                </button>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Refund Management (If applicable) -->
            @if($order->stripe_payment_intent_id && in_array($order->status, ['confirmed', 'paid']))
                <div class="bg-rose-50 rounded-3xl shadow-sm border border-rose-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-rose-100 bg-rose-100/20">
                        <h3 class="text-sm font-bold text-rose-900 uppercase tracking-wider">Refund Management</h3>
                    </div>
                    <div class="p-6">
                        @if($order->refund_status === 'pending')
                            <div class="p-4 bg-white/60 border border-rose-200 rounded-2xl space-y-3">
                                <p class="text-xs font-bold text-rose-800">Refund Request Pending</p>
                                <p class="text-lg font-black text-rose-900">{{ $order->currency }} {{ number_format($order->refund_amount, 2) }}</p>
                                @if($order->refund_reason)
                                    <p class="text-xs text-rose-600 italic">"{{ $order->refund_reason }}"</p>
                                @endif
                                <div class="grid grid-cols-2 gap-2 pt-2">
                                    <form action="{{ route('admin.orders.approveRefund', $order) }}" method="POST" class="js-confirm" data-title="Approve refund?" data-text="Refund will be processed via Stripe.">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-emerald-600 text-white text-[10px] font-black uppercase rounded-lg hover:bg-emerald-700 transition-colors">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.orders.rejectRefund', $order) }}" method="POST" class="js-confirm" data-title="Reject refund request?" data-text="Refund request will be rejected.">
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
