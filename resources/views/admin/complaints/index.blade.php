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

<div style="max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0;">Complaints</h1>
    </div>

    <!-- Filters -->
    <form method="GET" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
        <div class="filter-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Status</label>
                <select name="status" style="width: 100%; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem;">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Type</label>
                <select name="type" style="width: 100%; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem;">
                    <option value="">All Types</option>
                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                    <option value="replacement" {{ request('type') == 'replacement' ? 'selected' : '' }}>Replacement</option>
                </select>
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" style="width: 100%; background: #000000; color: #ffffff; padding: 0.5rem 1rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
                    Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Desktop Table -->
    <div class="desktop-table" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
        <table style="width: 100%;">
            <thead style="background: #f9fafb;">
                <tr>
                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">ID</th>
                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Order</th>
                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Type</th>
                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Status</th>
                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Submitted</th>
                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody style="background: #ffffff;">
                @forelse($complaints as $complaint)
                    <tr>
                        <td style="padding: 0.75rem 1.5rem; white-space: nowrap; font-size: 0.875rem; font-weight: 600; color: #111827; border-top: 1px solid #e5e7eb;">#{{ $complaint->id }}</td>
                        <td style="padding: 0.75rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #111827; font-family: monospace; border-top: 1px solid #e5e7eb;">
                            {{ $complaint->preorder->order_number }}
                        </td>
                        <td style="padding: 0.75rem 1.5rem; white-space: nowrap; font-size: 0.875rem; text-transform: capitalize; border-top: 1px solid #e5e7eb;">{{ $complaint->type }}</td>
                        <td style="padding: 0.75rem 1.5rem; white-space: nowrap; border-top: 1px solid #e5e7eb;">
                            @if($complaint->status === 'pending')
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #fef3c7; color: #92400e; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @elseif($complaint->status === 'approved')
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #d1fae5; color: #065f46; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @elseif($complaint->status === 'rejected')
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #fee2e2; color: #991b1b; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @elseif($complaint->status === 'completed')
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #dbeafe; color: #1e3a8a; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @else
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #f3f4f6; color: #111827; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #6b7280; border-top: 1px solid #e5e7eb;">
                            {{ $complaint->created_at->format('M j, Y') }}
                        </td>
                        <td style="padding: 0.75rem 1.5rem; white-space: nowrap; font-size: 0.875rem; border-top: 1px solid #e5e7eb;">
                            <a href="{{ route('admin.complaints.show', $complaint) }}" 
                               style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                View Details →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem 1.5rem; text-align: center; color: #6b7280;">
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
            
            <div class="complaint-card" data-complaint-id="{{ $complaint->id }}">
                <div class="complaint-header">
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
                            @if($complaint->status === 'pending')
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #fef3c7; color: #92400e; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @elseif($complaint->status === 'approved')
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #d1fae5; color: #065f46; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @elseif($complaint->status === 'rejected')
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #fee2e2; color: #991b1b; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @elseif($complaint->status === 'completed')
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #dbeafe; color: #1e3a8a; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @else
                                <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #f3f4f6; color: #111827; display: inline-block;">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            @endif
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
                                @if($complaint->status === 'pending')
                                    <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #fef3c7; color: #92400e; display: inline-block;">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                @elseif($complaint->status === 'approved')
                                    <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #d1fae5; color: #065f46; display: inline-block;">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                @elseif($complaint->status === 'rejected')
                                    <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #fee2e2; color: #991b1b; display: inline-block;">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                @elseif($complaint->status === 'completed')
                                    <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #dbeafe; color: #1e3a8a; display: inline-block;">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                @else
                                    <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; background: #f3f4f6; color: #111827; display: inline-block;">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                @endif
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
    @if ($complaints->hasPages())
        <div style="display:flex; justify-content:center; margin-top: 1.5rem;">
            <nav aria-label="Pagination" style="display:flex; gap:0.5rem; align-items:center;">
                @if ($complaints->onFirstPage())
                    <span style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #9ca3af; background:#f9fafb; border-radius: 0.375rem; font-size: 0.875rem;">« Prev</span>
                @else
                    <a href="{{ $complaints->previousPageUrl() }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">« Prev</a>
                @endif

                @php
                    $start = max(1, $complaints->currentPage() - 2);
                    $end = min($complaints->lastPage(), $complaints->currentPage() + 2);
                @endphp
                @if ($start > 1)
                    <a href="{{ $complaints->url(1) }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">1</a>
                    @if ($start > 2)
                        <span style="padding: 0.5rem 0.75rem; color: #9ca3af; font-size: 0.875rem;">…</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $complaints->currentPage())
                        <span style="padding: 0.5rem 0.75rem; border: 1px solid #111827; color: #fff; background:#111827; border-radius: 0.375rem; font-size: 0.875rem; font-weight:700;">{{ $page }}</span>
                    @else
                        <a href="{{ $complaints->url($page) }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $complaints->lastPage())
                    @if ($end < $complaints->lastPage() - 1)
                        <span style="padding: 0.5rem 0.75rem; color: #9ca3af; font-size: 0.875rem;">…</span>
                    @endif
                    <a href="{{ $complaints->url($complaints->lastPage()) }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">{{ $complaints->lastPage() }}</a>
                @endif

                @if ($complaints->hasMorePages())
                    <a href="{{ $complaints->nextPageUrl() }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">Next »</a>
                @else
                    <span style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #9ca3af; background:#f9fafb; border-radius: 0.375rem; font-size: 0.875rem;">Next »</span>
                @endif
            </nav>
        </div>
    @endif
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

    document.querySelectorAll('.complaint-header').forEach(function(el){
        el.addEventListener('click', function(){
            var card = el.closest('.complaint-card');
            var cid = card ? card.getAttribute('data-complaint-id') : null;
            if (cid) toggleComplaint(cid);
        });
    });
</script>
@endsection
