@extends('layouts.app')

@section('title', 'Complaint Details')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-700 text-white px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold">Complaint #{{ $complaint->id }}</h1>
                    <p class="text-sm text-gray-300 mt-1">Order: {{ $complaint->preorder->order_number }}</p>
                </div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-500',
                        'approved' => 'bg-green-500',
                        'rejected' => 'bg-red-500',
                        'completed' => 'bg-blue-500',
                    ];
                @endphp
                <span
                    class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$complaint->status] ?? 'bg-gray-500' }}">
                    {{ ucfirst($complaint->status) }}
                </span>
            </div>

            <div class="p-6">
                <!-- Complaint Info -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Type</h3>
                        <p class="text-gray-900 capitalize">{{ $complaint->type }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Amount</h3>
                        <p class="text-gray-900">{{ $complaint->preorder->currency }}
                            {{ number_format($complaint->preorder->total_amount, 2) }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Submitted</h3>
                        <p class="text-gray-900">{{ $complaint->created_at->format('F j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Expires</h3>
                        <p class="text-gray-900">{{ $complaint->expires_at->format('F j, Y') }}</p>
                    </div>
                </div>

                <!-- Customer Reason -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Customer's Reason</h3>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        {{ $complaint->reason }}
                    </div>
                </div>

                <!-- Admin Actions -->
                @if($complaint->status === 'pending')
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Actions</h3>

                        <!-- Approve Form -->
                        <form method="POST" action="{{ route('admin.complaints.approve', $complaint) }}" class="mb-4">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                            <textarea name="admin_notes" rows="3" class="w-full border-gray-300 rounded-lg mb-3"
                                placeholder="Internal notes about this decision..."></textarea>
                            <button type="submit" onclick="return confirm('Approve this {{ $complaint->type }} request?')"
                                class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700">
                                Approve {{ ucfirst($complaint->type) }}
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form method="POST" action="{{ route('admin.complaints.reject', $complaint) }}">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                            <textarea name="rejection_reason" rows="3" required class="w-full border-gray-300 rounded-lg mb-3"
                                placeholder="Explain why this complaint is being rejected..."></textarea>
                            <button type="submit" onclick="return confirm('Reject this complaint?')"
                                class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700">
                                Reject Complaint
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Confirm Return Receipt -->
                @if($complaint->canConfirmReturn())
                    <div class="border-t pt-6 bg-blue-50 -mx-6 px-6 pb-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Process Return</h3>
                        <p class="text-sm text-blue-700 mb-4">
                            The replacement order <strong>#{{ $complaint->replacement_order_number }}</strong> is currently
                            <strong>Pending</strong>.
                            Confirm receipt of the returned item to activate the replacement order.
                        </p>
                        <form method="POST" action="{{ route('admin.complaints.confirm-return', $complaint) }}">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Confirm that you have received the returned item? This will activate the replacement order.')"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Confirm Return Received
                            </button>
                        </form>
                    </div>
                @endif

                @if($complaint->admin_notes)
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-blue-900 mb-1">Admin Notes</h3>
                        <p class="text-blue-800">{{ $complaint->admin_notes }}</p>
                    </div>
                @endif

                @if($complaint->rejection_reason)
                    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-red-900 mb-1">Rejection Reason</h3>
                        <p class="text-red-800">{{ $complaint->rejection_reason }}</p>
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t">
                <a href="{{ route('admin.complaints.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Back to Complaints List
                </a>
            </div>
        </div>
    </div>
@endsection