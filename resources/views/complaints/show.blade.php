@extends('layouts.public')

@section('title', 'Complaint Status')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6">
                {{ session('info') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-700 text-white px-6 py-4">
                <h1 class="text-xl font-bold">Complaint Status</h1>
                <p class="text-sm text-gray-300 mt-1">Complaint ID: #{{ $complaint->id }}</p>
            </div>

            <!-- Status Badge -->
            <div class="px-6 py-4 border-b">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'approved' => 'bg-green-100 text-green-800 border-green-300',
                        'rejected' => 'bg-red-100 text-red-800 border-red-300',
                        'completed' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'expired' => 'bg-gray-100 text-gray-800 border-gray-300',
                    ];
                    $statusColor = $statusColors[$complaint->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border {{ $statusColor }}">
                    {{ ucfirst($complaint->status) }}
                </span>
            </div>

            <!-- Details -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Order Number</h3>
                        <p class="font-mono text-gray-900">{{ $complaint->preorder->order_number }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Complaint Type</h3>
                        <p class="text-gray-900 capitalize">{{ $complaint->type }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Submitted</h3>
                        <p class="text-gray-900">{{ $complaint->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Expires</h3>
                        <p class="text-gray-900">{{ $complaint->expires_at->format('F j, Y') }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Your Reason</h3>
                    <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $complaint->reason }}</p>
                </div>

                @if($complaint->status === 'rejected' && $complaint->rejection_reason)
                    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-red-800 mb-2">Rejection Reason</h3>
                        <p class="text-red-700">{{ $complaint->rejection_reason }}</p>
                    </div>
                @endif

                @if($complaint->status === 'approved' && $complaint->type === 'replacement' && $complaint->replacement_order_number)
                    <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-green-800 mb-2">Replacement Order Processing</h3>
                        <p class="text-green-700 mb-3">Your replacement order number: <span
                                class="font-mono font-semibold">{{ $complaint->replacement_order_number }}</span></p>

                        <div class="bg-white border border-green-200 rounded p-3 text-sm">
                            @if($complaint->return_status === 'waiting_return')
                                <p class="font-semibold text-gray-800 mb-1">Step 1: Return the Original Item</p>
                                <p class="text-gray-600">Please send the original item back to our warehouse. Once we receive it,
                                    your replacement order will be activated.</p>
                                <div class="mt-2 text-xs text-gray-500 italic">Status: Waiting for your return shipment</div>
                            @elseif($complaint->return_status === 'received')
                                <p class="font-semibold text-green-700 mb-1">✓ Return Received</p>
                                <p class="text-green-600">We have received your return. Your replacement order is now active and
                                    will be shipped soon.</p>
                                <div class="mt-2 text-xs text-green-500 italic">Status: Completed</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t flex gap-3">
                <a href="{{ route('order.track', ['order' => $complaint->preorder->order_number]) }}"
                    class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold text-center hover:bg-gray-200 transition text-sm">
                    View Order
                </a>
                @if($complaint->canBeCancelled())
                    <form method="POST" action="{{ route('complaints.cancel', $complaint) }}" class="flex-1">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to cancel this complaint?')"
                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition text-sm">
                            Cancel Complaint
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection