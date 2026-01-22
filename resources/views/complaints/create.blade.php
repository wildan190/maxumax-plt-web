@extends('layouts.public')

@section('title', 'File Complaint')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">File a Complaint</h1>
            <p class="text-gray-600 mb-6">Order: <span class="font-mono font-semibold">{{ $preorder->order_number }}</span>
            </p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i data-feather="alert-triangle" class="text-yellow-600 mr-2 mt-0.5"
                        style="width:20px;height:20px;"></i>
                    <div>
                        <p class="text-sm text-yellow-800 font-semibold">Complaint Window Expires:</p>
                        <p class="text-sm text-yellow-700">{{ $expiresAt->format('F j, Y \a\t g:i A') }}
                            ({{ $expiresAt->diffForHumans() }})</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('complaints.store') }}">
                @csrf
                <input type="hidden" name="preorder_id" value="{{ $preorder->id }}">

                <!-- Order Details Summary -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Order Details</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600">Product:</span>
                            <span class="font-medium">{{ $preorder->product->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total:</span>
                            <span class="font-medium">{{ $preorder->currency }}
                                {{ number_format($preorder->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Complaint Type -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">What would you like? *</label>
                    <div class="space-y-3">
                        <label
                            class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="type" value="refund" class="mt-1 mr-3" required>
                            <div>
                                <div class="font-semibold text-gray-900">Full Refund</div>
                                <div class="text-sm text-gray-600">Receive your money back via the original payment method
                                </div>
                            </div>
                        </label>
                        <label
                            class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="type" value="replacement" class="mt-1 mr-3" required>
                            <div>
                                <div class="font-semibold text-gray-900">Item Replacement</div>
                                <div class="text-sm text-gray-600">Receive a new replacement item at no charge</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mb-6">
                    <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">Reason for Complaint
                        *</label>
                    <textarea name="reason" id="reason" rows="5"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Please describe the issue with your order in detail (minimum 10 characters)..."
                        required minlength="10" maxlength="1000">{{ old('reason') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Be specific to help us resolve your issue faster</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <a href="{{ route('order.track', ['order' => $preorder->order_number]) }}"
                        class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold text-center hover:bg-gray-200 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-1 bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                        Submit Complaint
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection