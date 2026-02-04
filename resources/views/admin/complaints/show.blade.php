@extends('layouts.app')

@section('title', 'Complaint Details')

@section('content')
    <div style="max-width: 900px; margin: 0 auto; padding: 1.5rem 1rem;">
        @if(session('success'))
            <div style="background: #ecfdf5; border: 1px solid #86efac; color: #166534; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #7f1d1d; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                {{ session('error') }}
            </div>
        @endif

        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.06); overflow: hidden;">
            <div style="background: linear-gradient(to right, #111827, #374151); color: #ffffff; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h1 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Complaint #{{ $complaint->id }}</h1>
                    <p style="font-size: 0.875rem; color: #d1d5db; margin: 0.25rem 0 0;">Order: {{ $complaint->preorder->order_number }}</p>
                </div>
                @if($complaint->status === 'pending')
                    <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; background: #f59e0b; color: #ffffff;">
                        {{ ucfirst($complaint->status) }}
                    </span>
                @elseif($complaint->status === 'approved')
                    <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; background: #10b981; color: #ffffff;">
                        {{ ucfirst($complaint->status) }}
                    </span>
                @elseif($complaint->status === 'rejected')
                    <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; background: #ef4444; color: #ffffff;">
                        {{ ucfirst($complaint->status) }}
                    </span>
                @elseif($complaint->status === 'completed')
                    <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; background: #3b82f6; color: #ffffff;">
                        {{ ucfirst($complaint->status) }}
                    </span>
                @else
                    <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; background: #6b7280; color: #ffffff;">
                        {{ ucfirst($complaint->status) }}
                    </span>
                @endif
            </div>

            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.25rem;">Type</h3>
                        <p style="color: #111827; text-transform: capitalize; margin: 0;">{{ $complaint->type }}</p>
                    </div>
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.25rem;">Amount</h3>
                        <p style="color: #111827; margin: 0;">{{ $complaint->preorder->currency }}
                            {{ number_format($complaint->preorder->total_amount, 2) }}
                        </p>
                    </div>
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.25rem;">Submitted</h3>
                        <p style="color: #111827; margin: 0;">{{ $complaint->created_at->format('F j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.25rem;">Expires</h3>
                        <p style="color: #111827; margin: 0;">{{ $complaint->expires_at->format('F j, Y') }}</p>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h3 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.5rem;">Customer's Reason</h3>
                    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
                        {{ $complaint->reason }}
                    </div>
                </div>

                @if($complaint->status === 'pending')
                    <div style="border-top: 1px solid #e5e7eb; padding-top: 1.5rem;">
                        <h3 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 1rem;">Admin Actions</h3>

                        <form method="POST" action="{{ route('admin.complaints.approve', $complaint) }}" style="margin-bottom: 1rem;">
                            @csrf
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.5rem;">Admin Notes (Optional)</label>
                            <textarea name="admin_notes" rows="3" style="width: 100%; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.75rem; font-family: inherit; margin-bottom: 0.75rem;"
                                placeholder="Internal notes about this decision..."></textarea>
                            <button type="submit" onclick="return confirm('Approve this {{ $complaint->type }} request?')"
                                style="background: #16a34a; color: #ffffff; padding: 0.5rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
                                Approve {{ ucfirst($complaint->type) }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.complaints.reject', $complaint) }}">
                            @csrf
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.5rem;">Rejection Reason *</label>
                            <textarea name="rejection_reason" rows="3" required style="width: 100%; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.75rem; font-family: inherit; margin-bottom: 0.75rem;"
                                placeholder="Explain why this complaint is being rejected..."></textarea>
                            <button type="submit" onclick="return confirm('Reject this complaint?')"
                                style="background: #dc2626; color: #ffffff; padding: 0.5rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
                                Reject Complaint
                            </button>
                        </form>
                    </div>
                @endif

                @if($complaint->canConfirmReturn())
                    <div style="border-top: 1px solid #e5e7eb; padding: 1.5rem; background: #eff6ff; border-radius: 0.5rem;">
                        <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e40af; margin: 0 0 0.5rem;">Process Return</h3>
                        <p style="font-size: 0.875rem; color: #1d4ed8; margin: 0 0 1rem;">
                            The replacement order <strong>#{{ $complaint->replacement_order_number }}</strong> is currently
                            <strong>Pending</strong>.
                            Confirm receipt of the returned item to activate the replacement order.
                        </p>
                        <form method="POST" action="{{ route('admin.complaints.confirm-return', $complaint) }}">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Confirm that you have received the returned item? This will activate the replacement order.')"
                                style="background: #2563eb; color: #ffffff; padding: 0.5rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Confirm Return Received
                            </button>
                        </form>
                    </div>
                @endif

                @if($complaint->admin_notes)
                    <div style="margin-top: 1.5rem; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.5rem; padding: 1rem;">
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #1e40af; margin: 0 0 0.25rem;">Admin Notes</h3>
                        <p style="color: #1e3a8a; margin: 0;">{{ $complaint->admin_notes }}</p>
                    </div>
                @endif

                @if($complaint->rejection_reason)
                    <div style="margin-top: 1.5rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem; padding: 1rem;">
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #7f1d1d; margin: 0 0 0.25rem;">Rejection Reason</h3>
                        <p style="color: #7f1d1d; margin: 0;">{{ $complaint->rejection_reason }}</p>
                    </div>
                @endif
            </div>

            <div style="padding: 1rem 1.5rem; background: #f9fafb; border-top: 1px solid #e5e7eb;">
                <a href="{{ route('admin.complaints.index') }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                    ← Back to Complaints List
                </a>
            </div>
        </div>
    </div>
@endsection
