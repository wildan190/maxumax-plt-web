@extends('layouts.public')

@section('title', 'Track Order')

@push('styles')
    <style>
        :root {
            --dark: #0f172a;
            --gray-500: #64748b;
        }
        .track-container { max-width: 800px; margin: 0 auto; padding: 3rem 1rem; }
        .track-title { font-size: 2rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem; }
        .track-sub { color: var(--gray-500); margin-bottom: 1.5rem; }
        .track-form { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .track-form input { flex: 1; border: 1px solid #cbd5e1; border-radius: 0.5rem; padding: 0.5rem 0.75rem; }
        .track-form button { background: #000; color: #fff; border: none; border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 600; }
        .alert { display:inline-block; border-radius: 0.75rem; padding: 0.5rem 0.75rem; font-weight: 600; margin-top: 1rem; }
        .alert-error { background:#fff7ed; color:#9a3412; border:1px solid #fed7aa; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1rem; margin-top: 1rem; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .label { font-size: 0.8rem; color: var(--gray-500); }
        .value { font-weight: 600; color: var(--dark); }
        .status { font-size: 0.875rem; padding: 0.25rem 0.5rem; border-radius: 9999px; border: 1px solid; }
    </style>
@endpush

@section('content')
    <section class="track-container">
        <h1 class="track-title">Track Order</h1>
        <p class="track-sub">Enter your order number to check status without logging in.</p>

        <form method="GET" action="{{ route('order.track') }}" class="track-form">
            <input type="text" name="order" value="{{ $orderInput }}" placeholder="Example: MM-ABCDEFGH" required />
            <button type="submit">Track</button>
        </form>

        @if($error)
            <div class="alert alert-error">{{ $error }}</div>
        @endif

        @if($preorder)
            <div class="card">
                <div class="flex items-center justify-between" style="display:flex; align-items:center; justify-content:space-between;">
                    <div class="value">{{ $preorder->order_number }}</div>
                    <div class="status" style="
                        @if($preorder->status === 'paid') background:#ecfdf5; color:#065f46; border-color:#a7f3d0;
                        @elseif($preorder->status === 'confirmed') background:#eff6ff; color:#1e40af; border-color:#bfdbfe;
                        @elseif($preorder->status === 'refunded') background:#fee2e2; color:#991b1b; border-color:#fecaca;
                        @else background:#fefce8; color:#854d0e; border-color:#fde68a; @endif
                    ">
                        {{ ucfirst($preorder->status) }}
                    </div>
                </div>
                <div class="row" style="margin-top:0.75rem;">
                    <div>
                        <div class="label">Product</div>
                        <div class="value">{{ optional($preorder->product)->name ?? ($preorder->jersey_type ?? '-') }}</div>
                    </div>
                    <div>
                        <div class="label">Quantity</div>
                        <div class="value">{{ $preorder->quantity }} pcs</div>
                    </div>
                    <div>
                        <div class="label">Total</div>
                        <div class="value">{{ $preorder->currency ?? 'MYR' }} {{ number_format($preorder->total_amount, 2) }}</div>
                    </div>
                    <div>
                        <div class="label">Contact</div>
                        <div class="value">{{ $preorder->phone }}{{ $preorder->email ? ' / ' . $preorder->email : '' }}</div>
                    </div>
                </div>

                <!-- Shipping Status & Tracking -->
                @if($preorder->shipping_status)
                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                        <h3 style="font-size: 1rem; font-weight: 600; color: var(--dark); margin: 0 0 0.75rem 0;">📦 Shipping Status</h3>
                        <!-- Status Timeline -->
                        <div style="position: relative; padding-left: 1.5rem;">
                            <!-- Pending/Confirmed -->
                            <div style="position: relative; margin-bottom: 1rem;">
                                <div style="position: absolute; left: -1.5rem; top: 0.25rem; width: 12px; height: 12px; border-radius: 50%; background: #22c55e; border: 2px solid white; box-shadow: 0 0 0 2px #22c55e;"></div>
                                <div>
                                    <div style="font-weight: 600; color: var(--dark);">Order Confirmed</div>
                                    <div style="font-size: 0.875rem; color: var(--gray-500);">{{ $preorder->created_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                            
                            @if($preorder->shipping_status == 'shipped' || $preorder->shipping_status == 'delivered')
                            <!-- Shipped -->
                            <div style="position: relative; margin-bottom: 1rem;">
                                <div style="position: absolute; left: -1.5rem; top: 0.25rem; width: 12px; height: 12px; border-radius: 50%; background: #22c55e; border: 2px solid white; box-shadow: 0 0 0 2px #22c55e;"></div>
                                <div style="position: absolute; left: -1.2rem; top: -1rem; width: 2px; height: 1.25rem; background: #22c55e;"></div>
                                <div>
                                    <div style="font-weight: 600; color: var(--dark);">Shipped</div>
                                    <div style="font-size: 0.875rem; color: var(--gray-500);">Your order is on the way</div>
                                </div>
                            </div>
                            @endif

                            @if($preorder->shipping_status == 'delivered')
                            <!-- Delivered -->
                            <div style="position: relative; margin-bottom: 1rem;">
                                <div style="position: absolute; left: -1.5rem; top: 0.25rem; width: 12px; height: 12px; border-radius: 50%; background: #22c55e; border: 2px solid white; box-shadow: 0 0 0 2px #22c55e;"></div>
                                <div style="position: absolute; left: -1.2rem; top: -1rem; width: 2px; height: 1.25rem; background: #22c55e;"></div>
                                <div>
                                    <div style="font-weight: 600; color: var(--dark);">Delivered</div>
                                    <div style="font-size: 0.875rem; color: var(--gray-500);">Package received</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div style="margin-top: 1.5rem;">
                            @if($preorder->shipping_status === 'shipped')
                                <form action="{{ route('preorder.markDelivered', $preorder) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin barang sudah diterima?')" style="background-color: #22c55e; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500; border: none; cursor: pointer;">
                                        ✅ Konfirmasi Barang Diterima
                                    </button>
                                </form>
                            @endif

                            @if($preorder->shipping_status === 'delivered')
                                @if($preorder->refund_status)
                                    <div style="padding: 1rem; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.375rem;">
                                        <div style="font-weight: 600; color: var(--dark); margin-bottom: 0.5rem;">Status Refund: {{ ucfirst($preorder->refund_status) }}</div>
                                        @if($preorder->refund_reason)
                                            <div style="font-size: 0.875rem; color: var(--gray-600);">Alasan: {{ $preorder->refund_reason }}</div>
                                        @endif
                                    </div>
                                @elseif($preorder->stripe_payment_intent_id)
                                    <button onclick="document.getElementById('refundModal').style.display='block'" style="background-color: #ef4444; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500; border: none; cursor: pointer;">
                                        ↩️ Request Refund
                                    </button>

                                    <!-- Refund Modal -->
                                    <div id="refundModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                                        <div style="background: white; width: 90%; max-width: 500px; margin: 100px auto; padding: 1.5rem; border-radius: 0.5rem; position: relative;">
                                            <h3 style="margin-top: 0; margin-bottom: 1rem;">Request Refund</h3>
                                            <form action="{{ route('preorder.requestRefund', $preorder) }}" method="POST">
                                                @csrf
                                                <div style="margin-bottom: 1rem;">
                                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Alasan Refund</label>
                                                    <textarea name="reason" required style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.375rem; min-height: 100px;" placeholder="Jelaskan alasan pengajuan refund..."></textarea>
                                                </div>
                                                <div style="text-align: right;">
                                                    <button type="button" onclick="document.getElementById('refundModal').style.display='none'" style="margin-right: 0.5rem; padding: 0.5rem 1rem; border: 1px solid #e2e8f0; background: white; border-radius: 0.375rem; cursor: pointer;">Batal</button>
                                                    <button type="submit" style="background-color: #ef4444; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; border: none; cursor: pointer;">Kirim Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Order History -->
                @if($preorder->histories && $preorder->histories->count() > 0)
                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                        <h3 style="font-size: 1rem; font-weight: 600; color: var(--dark); margin: 0 0 0.75rem 0;">📋 Order History</h3>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            @foreach($preorder->histories->sortByDesc('created_at') as $history)
                                <div style="padding: 0.75rem; background: #f9fafb; border-radius: 0.5rem; font-size: 0.875rem;">
                                    <div style="font-weight: 600; color: var(--dark);">{{ $history->note ?? 'Status updated' }}</div>
                                    <div style="color: var(--gray-500); font-size: 0.8rem; margin-top: 0.25rem;">{{ $history->created_at->format('d M Y, H:i') }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div style="margin-top:1.5rem;">
                    <a href="{{ route('preorder.thankyou', ['uuid' => $preorder->uuid]) }}" class="inline-block bg-black text-white px-4 py-2 rounded-md font-semibold text-sm">Lihat detail</a>
                </div>
            </div>
        @endif
    </section>
@endsection
