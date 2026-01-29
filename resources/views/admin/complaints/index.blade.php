@extends('layouts.app')

@section('title', 'Complaints Management')

@section('content')
<style>
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr !important;
        }
        
        .desktop-table {
            display: none !important;
        }
        
        .mobile-list {
            display: block !important;
        }
    }
    
    @media (min-width: 769px) {
        .mobile-list {
            display: none !important;
        }
    }
    
    /* Mobile Card Styles */
    .complaint-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .complaint-card.expanded {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .complaint-header {
        padding: 1rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: #f9fafb;
    }
    
    .complaint-header:active {
        background: #f3f4f6;
    }
    
    .complaint-body {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .complaint-body.show {
        max-height: 500px;
    }
    
    .complaint-details {
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
        align-items: flex-start;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        flex-shrink: 0;
    }
    
    .detail-value {
        color: #111827;
        font-size: 0.95rem;
        text-align: right;
        max-width: 65%;
        word-wrap: break-word;
    }
    
    .complaint-actions {
        padding: 1rem;
        background: #f9fafb;
    }
    
    .chevron {
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }
    
    .chevron.rotate {
        transform: rotate(180deg);
    }
</style>

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Complaints</h1>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="filter-grid grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full border-gray-300 rounded-lg">
                    <option value="">All Types</option>
                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                    <option value="replacement" {{ request('type') == 'replacement' ? 'selected' : '' }}>Replacement</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800">
                    Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Desktop Table -->
    <div class="desktop-table bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($complaints as $complaint)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $complaint->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                            {{ $complaint->preorder->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ $complaint->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $colors[$complaint->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $complaint->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.complaints.show', $complaint) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                View Details →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No complaints found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile List -->
    <div class="mobile-list">
        @forelse($complaints as $complaint)
            @php
                $colors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'approved' => 'bg-green-100 text-green-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    'completed' => 'bg-blue-100 text-blue-800',
                ];
            @endphp
            
            <div class="complaint-card" data-complaint-id="{{ $complaint->id }}">
                <div class="complaint-header" onclick="toggleComplaint({{ $complaint->id }})">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.25rem; font-size: 0.95rem;">
                            Complaint #{{ $complaint->id }}
                        </div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem; font-family: monospace;">
                            Order: {{ $complaint->preorder->order_number }}
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                            <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600; text-transform: capitalize;">
                                {{ $complaint->type }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $colors[$complaint->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; margin-left: 0.5rem;">
                        <svg class="chevron" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div class="complaint-body">
                    <div class="complaint-details">
                        <div class="detail-row">
                            <span class="detail-label">Submitted</span>
                            <span class="detail-value">{{ $complaint->created_at->format('M j, Y H:i') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Type</span>
                            <span class="detail-value" style="text-transform: capitalize;">{{ $complaint->type }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status</span>
                            <span class="detail-value">
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $colors[$complaint->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </span>
                        </div>
                        @if($complaint->reason)
                            <div class="detail-row">
                                <span class="detail-label">Reason</span>
                                <span class="detail-value">{{ Str::limit($complaint->reason, 50) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="complaint-actions">
                        <a href="{{ route('admin.complaints.show', $complaint) }}" style="
                            width: 100%;
                            background: #3b82f6;
                            color: white;
                            padding: 0.625rem;
                            border: none;
                            border-radius: 0.5rem;
                            font-size: 0.875rem;
                            font-weight: 600;
                            text-decoration: none;
                            display: block;
                            text-align: center;
                        ">
                            View Details →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div style="background: white; border-radius: 0.75rem; padding: 3rem; text-align: center;">
                <p style="color: #6b7280; font-size: 1rem; margin: 0;">No complaints found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $complaints->links() }}
    </div>
</div>

<script>
    // Toggle complaint card
    function toggleComplaint(complaintId) {
        const card = document.querySelector(`[data-complaint-id="${complaintId}"]`);
        const body = card.querySelector('.complaint-body');
        const chevron = card.querySelector('.chevron');
        
        body.classList.toggle('show');
        chevron.classList.toggle('rotate');
        card.classList.toggle('expanded');
    }
</script>
@endsection