@extends('layouts.app')

@section('page-title', 'Preorder #'.$preorder->order_number)

@section('content')
    <div style="max-width: 1200px;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('admin.preorders.index') }}"
                style="color: #6b7280; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;"
                title="Back">← Back to Preorders</a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem;">
            <!-- Main Details -->
            <div
                style="background: white; border: 1px solid #e5e7eb; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem;">
                        <div>
                            <p
                                style="font-size: 0.875rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0 0 0.5rem 0;">
                                Customer Name</p>
                            <p style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0;">
                                {{ $preorder->name }}</p>
                        </div>
                        <div>
                            <p
                                style="font-size: 0.875rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0 0 0.5rem 0;">
                                Status</p>
                            @if($preorder->status === 'paid')
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem; background: #d1fae5; color: #065f46;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></span>
                                    Paid
                                </span>
                            @elseif($preorder->status === 'confirmed')
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem; background: #e0e7ff; color: #3730a3;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #4f46e5;"></span>
                                    Confirmed
                                </span>
                            @elseif($preorder->status === 'refunded')
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

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; margin-bottom: 0.25rem;">Email</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0;">{{ $preorder->email ?? '—' }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; margin-bottom: 0.25rem;">Phone</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0;">{{ $preorder->phone ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0 0 1rem 0;">Order Details</h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Jersey Type</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; font-weight: 500;">
                                {{ $preorder->jersey_type }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Size</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; font-weight: 500;">
                                {{ $preorder->size }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Quantity</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; font-weight: 500;">
                                {{ $preorder->quantity }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Total Amount</p>
                            <p style="font-size: 0.95rem; color: #111827; margin: 0; font-weight: 600;">
                                {{ $preorder->currency ?? 'MYR' }} {{ number_format($preorder->total_amount, 2) }}</p>
                        </div>
                    </div>

                    @if($preorder->notes)
                        <div style="margin-top: 1rem;">
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0;">Notes</p>
                            <p
                                style="font-size: 0.95rem; color: #374151; margin: 0; padding: 0.75rem; background: #f9fafb; border-radius: 0.375rem;">
                                {{ $preorder->notes }}</p>
                        </div>
                    @endif

                    @if($preorder->custom_fields && count($preorder->custom_fields) > 0)
                        <div style="margin-top: 1.5rem;">
                            <h4 style="font-size: 0.95rem; font-weight: 600; color: #111827; margin: 0 0 0.75rem 0;">🏷️
                                Customizations</h4>
                            <div
                                style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.5rem; overflow: hidden;">
                                @foreach($preorder->custom_fields as $field)
                                    <div
                                        style="display: flex; justify-content: space-between; padding: 0.75rem 1rem; border-bottom: 1px solid #bbf7d0;">
                                        <span style="color: #065f46; font-weight: 500;">{{ $field['key'] ?? '-' }}</span>
                                        <span style="color: #111827; font-weight: 700;">{{ $field['value'] ?? '-' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div style="padding: 1.5rem;">
                    @if($preorder->status === 'pending')
                        <form action="{{ route('admin.preorders.confirm', $preorder) }}" method="POST" style="display: inline;" class="js-confirm" data-title="Confirm this preorder?" data-text="Status akan diubah menjadi confirmed.">
                            @csrf
                            <button type="submit"
                                style="padding: 0.75rem 1.25rem; background: #6366f1; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">Confirm</button>
                        </form>
                    @endif
                    @if($preorder->status === 'confirmed')
                        <form action="{{ route('admin.preorders.markPaid', $preorder) }}" method="POST" style="display: inline; margin-left: 0.5rem;" class="js-confirm" data-title="Mark as paid?" data-text="Status akan diubah menjadi paid.">
                            @csrf
                            <button type="submit"
                                style="padding: 0.75rem 1.25rem; background: #22c55e; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">✓ Mark as Paid</button>
                        </form>
                    @endif
                    <form action="{{ route('admin.preorders.destroy', $preorder) }}" method="POST"
                        style="display: inline; margin-left: 0.5rem;" class="js-delete" data-title="Delete this preorder?" data-text="Tindakan ini tidak dapat dibatalkan.">
                        @csrf @method('DELETE')
                        <button type="submit"
                            style="padding: 0.75rem 1.25rem; background: #ef4444; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">Delete</button>
                    </form>
                </div>

                @if($preorder->stripe_payment_intent_id && in_array($preorder->status, ['confirmed', 'paid']))
                    <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                        <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0 0 1rem 0;">Refund Management</h3>
                        
                        @if($preorder->refund_status === 'pending')
                            <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <p style="font-weight: 600; color: #92400e; margin: 0 0 0.5rem 0;">Refund Request Pending</p>
                                <p style="color: #78350f; font-size: 0.875rem; margin: 0 0 0.5rem 0;">Amount: {{ $preorder->currency }} {{ number_format($preorder->refund_amount, 2) }}</p>
                                @if($preorder->refund_reason)
                                    <p style="color: #78350f; font-size: 0.875rem; margin: 0 0 1rem 0;">Reason: {{ $preorder->refund_reason }}</p>
                                @endif
                                <div style="display: flex; gap: 0.5rem;">
                                    <form action="{{ route('admin.preorders.approveRefund', $preorder) }}" method="POST" style="display: inline;" class="js-confirm" data-title="Approve refund?" data-text="Refund akan diproses melalui Stripe.">
                                        @csrf
                                        <button type="submit" style="padding: 0.5rem 1rem; background: #22c55e; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Approve Refund</button>
                                    </form>
                                    <form action="{{ route('admin.preorders.rejectRefund', $preorder) }}" method="POST" style="display: inline;" class="js-confirm" data-title="Reject refund request?" data-text="Refund request akan ditolak.">
                                        @csrf
                                        <button type="submit" style="padding: 0.5rem 1rem; background: #ef4444; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @elseif($preorder->refund_status === 'approved' || $preorder->refund_status === 'completed')
                            <div style="background: #d1fae5; border: 1px solid #22c55e; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <p style="font-weight: 600; color: #065f46; margin: 0 0 0.5rem 0;">Refund Approved & Processed</p>
                                <p style="color: #047857; font-size: 0.875rem; margin: 0;">Refund ID: {{ $preorder->stripe_refund_id ?? 'N/A' }}</p>
                            </div>
                        @elseif($preorder->refund_status === 'rejected')
                            <div style="background: #fee2e2; border: 1px solid #ef4444; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <p style="font-weight: 600; color: #991b1b; margin: 0;">Refund Request Rejected</p>
                            </div>
                        @else
                            <form action="{{ route('admin.preorders.requestRefund', $preorder) }}" method="POST" id="refundForm" style="display: none;">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Refund Amount</label>
                                    <input type="number" name="refund_amount" step="0.01" min="0" max="{{ $preorder->total_amount }}" value="{{ $preorder->total_amount }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.95rem;">
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Reason</label>
                                    <textarea name="refund_reason" required rows="3" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.95rem; font-family: inherit;"></textarea>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button type="submit" style="padding: 0.5rem 1rem; background: #6366f1; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Submit Refund Request</button>
                                    <button type="button" onclick="document.getElementById('refundForm').style.display='none';" style="padding: 0.5rem 1rem; background: #6b7280; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Cancel</button>
                                </div>
                            </form>
                            <button type="button" onclick="document.getElementById('refundForm').style.display='block';" style="padding: 0.75rem 1.25rem; background: #f59e0b; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">Request Refund</button>
                        @endif
                    </div>
                @endif

                <!-- Shipping Management -->
                @if(in_array($preorder->status, ['confirmed', 'paid']))
                    <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                        <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0 0 1rem 0;">📦 Shipping Management</h3>
                        
                        @if($preorder->shipping_status)
                            <div style="margin-bottom: 1rem;">
                                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0;">Shipping Status</p>
                                <p style="font-weight: 600; color: #111827; margin: 0;">
                                    @if($preorder->shipping_status === 'packing')
                                        <span style="color: #f59e0b;">📦 Packing</span>
                                    @elseif($preorder->shipping_status === 'shipped')
                                        <span style="color: #3b82f6;">🚚 Shipped</span>
                                    @elseif($preorder->shipping_status === 'delivered')
                                        <span style="color: #22c55e;">✓ Delivered</span>
                                    @else
                                        <span style="color: #6b7280;">Pending</span>
                                    @endif
                                </p>
                            </div>
                            @if($preorder->tracking_number)
                                <div style="margin-bottom: 1rem;">
                                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0;">Tracking Number</p>
                                    <p style="font-weight: 600; color: #111827; margin: 0; font-family: monospace;">{{ $preorder->tracking_number }}</p>
                                </div>
                            @endif
                        @endif

                        @if(!$preorder->shipping_status || $preorder->shipping_status === 'pending')
                            <form action="{{ route('admin.preorders.markPacking', $preorder) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="padding: 0.75rem 1.25rem; background: #f59e0b; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">📦 Mark as Packing</button>
                            </form>
                        @elseif($preorder->shipping_status === 'packing')
                            <form action="{{ route('admin.preorders.markShipped', $preorder) }}" method="POST" id="shippedForm" style="display: none; margin-top: 1rem;">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Tracking Number *</label>
                                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $preorder->tracking_number) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.95rem; font-family: monospace;" placeholder="e.g., JNE1234567890">
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button type="submit" style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">🚚 Mark as Shipped</button>
                                    <button type="button" onclick="document.getElementById('shippedForm').style.display='none';" style="padding: 0.5rem 1rem; background: #6b7280; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">Cancel</button>
                                </div>
                            </form>
                            <button type="button" onclick="document.getElementById('shippedForm').style.display='block';" style="padding: 0.75rem 1.25rem; background: #3b82f6; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">🚚 Mark as Shipped</button>
                        @elseif($preorder->shipping_status === 'shipped')
                            <form action="{{ route('admin.preorders.markDelivered', $preorder) }}" method="POST" style="display: inline;" class="js-confirm" data-title="Mark as delivered?" data-text="Order akan ditandai sebagai delivered.">
                                @csrf
                                <button type="submit" style="padding: 0.75rem 1.25rem; background: #22c55e; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">✓ Mark as Delivered</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- History Sidebar -->
            <div
                style="background: white; border: 1px solid #e5e7eb; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; height: fit-content;">
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0;">Order History</h3>
                </div>
                <div style="padding: 1.5rem; max-height: 500px; overflow-y: auto;">
                    @if($preorder->histories->count())
                        <ul style="margin: 0; padding: 0; list-style: none;">
                            @foreach($preorder->histories as $h)
                                <li style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                                    <div style="font-weight: 600; color: #111827; margin-bottom: 0.25rem;">
                                        {{ ucfirst($h->new_status) }}</div>
                                    @if($h->note)
                                        <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">{{ $h->note }}</div>
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
