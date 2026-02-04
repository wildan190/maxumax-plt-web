@extends('layouts.app')

@section('page-title', 'Order #'.$order->order_number)

@section('content')
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .page-container {
                max-width: 100% !important;
                padding: 0 1rem;
            }
            
            .back-nav {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.75rem !important;
            }
            
            .back-nav a {
                width: 100%;
                text-align: center;
                justify-content: center !important;
            }
            
            .main-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            .detail-grid-2col {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            .action-buttons {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            
            .action-buttons form,
            .action-buttons button {
                width: 100% !important;
            }
            
            .action-buttons button {
                justify-content: center;
            }
            
            .refund-actions,
            .shipping-actions {
                flex-direction: column !important;
            }
            
            .refund-actions button,
            .shipping-actions button {
                width: 100% !important;
            }
            
            .card {
                border-radius: 0.5rem !important;
            }
            
            .card-padding {
                padding: 1rem !important;
            }
            
            .history-sidebar {
                order: 2;
            }
            
            .main-content {
                order: 1;
            }
        }
        
        /* Smooth transitions */
        .card {
            transition: all 0.2s ease;
        }
        
        /* Better touch targets */
        @media (max-width: 768px) {
            button, a {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
            }
        }
    </style>

    <div class="page-container" style="max-width: 1200px;">
        <div class="back-nav" style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
            <a href="{{ route('admin.orders.index') }}"
                style="color: #6b7280; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;"
                title="Back">← Back to Orders</a>
            <a href="{{ route('admin.orders.printShow', $order) }}"
               style="background: #111827; color: white; padding: 0.625rem 1rem; border: none; border-radius: 0.5rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
               <span>🖨️</span> Print View
            </a>
        </div>

        <div class="main-grid" style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem;">
            <!-- Main Details -->
            <div class="card main-content"
                style="background: white; border: 1px solid #e5e7eb; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                
                <!-- Customer Info -->
                <div class="card-padding" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <div class="detail-grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem;">
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0 0 0.5rem 0;">
                                Customer Name</p>
                            <p style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0; word-wrap: break-word;">
                                {{ $order->name }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0 0 0.5rem 0;">
                                Status</p>
                            @if($order->status === 'paid')
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem; background: #d1fae5; color: #065f46;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></span>
                                    Paid
                                </span>
                            @elseif($order->status === 'confirmed')
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem; background: #e0e7ff; color: #3730a3;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #4f46e5;"></span>
                                    Confirmed
                                </span>
                            @elseif($order->status === 'refunded')
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem; background: #fee2e2; color: #991b1b;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444;"></span>
                                    Refunded
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem; background: #fef3c7; color: #92400e;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b;"></span>
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; margin-bottom: 0.25rem;">Email</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; word-wrap: break-word;">{{ $order->email ?? '—' }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; margin-bottom: 0.25rem;">Phone</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; word-wrap: break-word;">{{ $order->phone ?? '—' }}</p>
                        </div>
                    </div>
                    
                    @if($order->address)
                        <div style="margin-top: 1rem;">
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; margin-bottom: 0.25rem;">Delivery Address</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; word-wrap: break-word;">{{ $order->address }}</p>
                        </div>
                    @endif
                </div>

                <!-- Order Details -->
                <div class="card-padding" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0 0 1rem 0;">Order Details</h3>

                    <div class="detail-grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Product</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; font-weight: 500; word-wrap: break-word;">
                                {{ optional($order->product)->name ?? $order->jersey_type }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Size</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; font-weight: 500;">
                                {{ $order->size }}
                                @if($order->long_sleeve)
                                    <span style="background: #f0f9ff; color: #0369a1; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem; display: inline-block; margin-top: 0.25rem;">+ Long Sleeve</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Quantity</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; font-weight: 500;">
                                {{ $order->quantity }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Total Amount</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; font-weight: 600;">
                                {{ $order->currency ?? 'MYR' }} {{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>

                    @if($order->notes)
                        <div style="margin-top: 1rem;">
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Notes</p>
                            <p style="font-size: 0.95rem; color: #374151; margin: 0; padding: 0.75rem; background: #f9fafb; border-radius: 0.375rem; word-wrap: break-word;">
                                {{ $order->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="card-padding action-buttons" style="padding: 1.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @if($order->status === 'pending')
                        <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" style="display: inline-block;" class="js-confirm" data-title="Confirm this order?" data-text="Status akan diubah menjadi confirmed.">
                            @csrf
                            <button type="submit"
                                style="padding: 0.75rem 1.25rem; background: #6366f1; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: background 0.2s; white-space: nowrap;">Confirm</button>
                        </form>
                    @endif
                    @if($order->status === 'confirmed')
                        <form action="{{ route('admin.orders.markPaid', $order) }}" method="POST" style="display: inline-block;" class="js-confirm" data-title="Mark as paid?" data-text="Status akan diubah menjadi paid.">
                            @csrf
                            <button type="submit"
                                style="padding: 0.75rem 1.25rem; background: #22c55e; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: background 0.2s; white-space: nowrap;">✓ Mark as Paid</button>
                        </form>
                    @endif
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                        style="display: inline-block;" class="js-delete" data-title="Delete this order?" data-text="Tindakan ini tidak dapat dibatalkan.">
                        @csrf @method('DELETE')
                        <button type="submit"
                            style="padding: 0.75rem 1.25rem; background: #ef4444; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: background 0.2s; white-space: nowrap;">Delete</button>
                    </form>
                </div>

                <!-- Refund Management -->
                @if($order->stripe_payment_intent_id && in_array($order->status, ['confirmed', 'paid']))
                    <div class="card-padding" style="padding: 1.5rem; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                        <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0 0 1rem 0;">Refund Management</h3>
                        
                        @if($order->refund_status === 'pending')
                            <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <p style="font-weight: 600; color: #92400e; margin: 0 0 0.5rem 0;">Refund Request Pending</p>
                                <p style="color: #78350f; font-size: 0.875rem; margin: 0 0 0.5rem 0; word-wrap: break-word;">Amount: {{ $order->currency }} {{ number_format($order->refund_amount, 2) }}</p>
                                @if($order->refund_reason)
                                    <p style="color: #78350f; font-size: 0.875rem; margin: 0 0 1rem 0; word-wrap: break-word;">Reason: {{ $order->refund_reason }}</p>
                                @endif
                                <div class="refund-actions" style="display: flex; gap: 0.5rem;">
                                    <form action="{{ route('admin.orders.approveRefund', $order) }}" method="POST" style="flex: 1;" class="js-confirm" data-title="Approve refund?" data-text="Refund akan diproses melalui Stripe.">
                                        @csrf
                                        <button type="submit" style="width: 100%; padding: 0.625rem 1rem; background: #22c55e; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Approve Refund</button>
                                    </form>
                                    <form action="{{ route('admin.orders.rejectRefund', $order) }}" method="POST" style="flex: 1;" class="js-confirm" data-title="Reject refund request?" data-text="Refund request akan ditolak.">
                                        @csrf
                                        <button type="submit" style="width: 100%; padding: 0.625rem 1rem; background: #ef4444; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @elseif($order->refund_status === 'approved' || $order->refund_status === 'completed')
                            <div style="background: #d1fae5; border: 1px solid #22c55e; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <p style="font-weight: 600; color: #065f46; margin: 0 0 0.5rem 0;">Refund Approved & Processed</p>
                                <p style="color: #047857; font-size: 0.875rem; margin: 0; word-wrap: break-word;">Refund ID: {{ $order->stripe_refund_id ?? 'N/A' }}</p>
                            </div>
                        @elseif($order->refund_status === 'rejected')
                            <div style="background: #fee2e2; border: 1px solid #ef4444; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <p style="font-weight: 600; color: #991b1b; margin: 0;">Refund Request Rejected</p>
                            </div>
                        @else
                            <form action="{{ route('admin.orders.requestRefund', $order) }}" method="POST" id="refundForm" style="display: none;">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Refund Amount</label>
                                    <input type="number" name="refund_amount" step="0.01" min="0" max="{{ $order->total_amount }}" value="{{ $order->total_amount }}" required style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.95rem;">
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Reason</label>
                                    <textarea name="refund_reason" required rows="3" style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.95rem; font-family: inherit; resize: vertical;"></textarea>
                                </div>
                                <div class="refund-actions" style="display: flex; gap: 0.5rem;">
                                    <button type="submit" style="flex: 1; padding: 0.625rem 1rem; background: #6366f1; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Submit Request</button>
                                    <button type="button" onclick="document.getElementById('refundForm').style.display='none';" style="flex: 1; padding: 0.625rem 1rem; background: #6b7280; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Cancel</button>
                                </div>
                            </form>
                            <button type="button" onclick="document.getElementById('refundForm').style.display='block';" style="width: 100%; padding: 0.75rem 1.25rem; background: #f59e0b; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">Request Refund</button>
                        @endif
                    </div>
                @endif

                <!-- Shipping Management -->
                @if(in_array($order->status, ['confirmed', 'paid']))
                    <div class="card-padding" style="padding: 1.5rem; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                        <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0 0 1rem 0;">📦 Shipping Management</h3>
                        <div style="margin-bottom: 1rem;">
                            <a href="{{ route('admin.orders.shipping', $order) }}"
                               style="display:inline-flex; align-items:center; gap:0.5rem; background:#111827; color:white; padding:0.625rem 1rem; border:none; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                               Manage Shipping (Check Rates & Book)
                            </a>
                        </div>
                        
                        @if($order->shipping_status)
                            <div style="margin-bottom: 1rem;">
                                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0;">Shipping Status</p>
                                <p style="font-weight: 600; color: #111827; margin: 0;">
                                    @if($order->shipping_status === 'packing')
                                        <span style="color: #f59e0b;">📦 Packing</span>
                                    @elseif($order->shipping_status === 'shipped')
                                        <span style="color: #3b82f6;">🚚 Shipped</span>
                                    @elseif($order->shipping_status === 'delivered')
                                        <span style="color: #22c55e;">✓ Delivered</span>
                                    @else
                                        <span style="color: #6b7280;">Pending</span>
                                    @endif
                                </p>
                            </div>
                            @if($order->tracking_number)
                                <div style="margin-bottom: 1rem;">
                                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0;">Tracking Number</p>
                                    <p style="font-weight: 600; color: #111827; margin: 0; font-family: monospace; word-wrap: break-word;">{{ $order->tracking_number }}</p>
                                </div>
                            @endif
                        @endif

                        @if(!$order->shipping_status || $order->shipping_status === 'pending')
                            <form action="{{ route('admin.orders.markPacking', $order) }}" method="POST" style="width: 100%;">
                                @csrf
                                <button type="submit" style="width: 100%; padding: 0.75rem 1.25rem; background: #f59e0b; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">📦 Mark as Packing</button>
                            </form>
                        @elseif($order->shipping_status === 'packing')
                            <form action="{{ route('admin.orders.markShipped', $order) }}" method="POST" id="shippedForm" style="display: none; margin-top: 1rem;">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Tracking Number *</label>
                                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" required style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.95rem; font-family: monospace;" placeholder="e.g., JNE1234567890">
                                </div>
                                <div class="shipping-actions" style="display: flex; gap: 0.5rem;">
                                    <button type="submit" style="flex: 1; padding: 0.625rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">🚚 Mark Shipped</button>
                                    <button type="button" onclick="document.getElementById('shippedForm').style.display='none'; document.getElementById('shippedToggleBtn').style.display='block';" style="flex: 1; padding: 0.625rem 1rem; background: #6b7280; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Cancel</button>
                                </div>
                            </form>
                            <button type="button" id="shippedToggleBtn" onclick="this.style.display='none'; document.getElementById('shippedForm').style.display='block';" style="width: 100%; padding: 0.75rem 1.25rem; background: #3b82f6; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">🚚 Mark as Shipped</button>
                        @elseif($order->shipping_status === 'shipped')
                            <form action="{{ route('admin.orders.markDelivered', $order) }}" method="POST" style="width: 100%;" class="js-confirm" data-title="Mark as delivered?" data-text="Order akan ditandai sebagai delivered.">
                                @csrf
                                <button type="submit" style="width: 100%; padding: 0.75rem 1.25rem; background: #22c55e; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">✓ Mark as Delivered</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- History Sidebar -->
            <div class="card history-sidebar"
                style="background: white; border: 1px solid #e5e7eb; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; height: fit-content;">
                <div class="card-padding" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0;">Order History</h3>
                </div>
                <div class="card-padding" style="padding: 1.5rem; max-height: 500px; overflow-y: auto;">
                    @if($order->histories->count())
                        <ul style="margin: 0; padding: 0; list-style: none;">
                            @foreach($order->histories as $h)
                                <li style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                                    <div style="font-weight: 600; color: #111827; margin-bottom: 0.25rem;">
                                        {{ ucfirst($h->new_status) }}</div>
                                    @if($h->note)
                                        <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem; word-wrap: break-word;">{{ $h->note }}</div>
                                    @endif
                                    <div style="color: #9ca3af; font-size: 0.8rem;">{{ $h->created_at->format('M d, Y H:i') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color: #6b7280; font-size: 0.95rem; margin: 0;">No history records.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('form.js-confirm').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const title = form.getAttribute('data-title') || 'Are you sure?';
                const text = form.getAttribute('data-text') || '';
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        document.querySelectorAll('form.js-delete').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const title = form.getAttribute('data-title') || 'Delete item?';
                const text = form.getAttribute('data-text') || 'This action cannot be undone.';
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
