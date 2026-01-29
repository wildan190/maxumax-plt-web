@extends('layouts.app')

@section('page-title', 'Order History')

@section('content')

    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            
            .search-form {
                max-width: 100% !important;
            }
            
            .filter-tabs {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .filter-tabs::-webkit-scrollbar {
                display: none;
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
        
        /* Mobile List Styles */
        .history-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .history-card.expanded {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .history-header {
            padding: 1rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: #f9fafb;
        }
        
        .history-header:active {
            background: #f3f4f6;
        }
        
        .history-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .history-body.show {
            max-height: 1000px;
        }
        
        .history-details {
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
            flex-shrink: 0;
        }
        
        .detail-value {
            color: #111827;
            font-size: 0.95rem;
            text-align: right;
            max-width: 65%;
            word-wrap: break-word;
        }
        
        .history-actions {
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

    {{-- Header --}}
    <div class="header-container" style="
        margin-bottom: 2rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    ">
        <div>
            <p style="color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.95rem;">
                Filter berdasarkan tipe order
            </p>
        </div>

        {{-- Search Form --}}
        <form method="GET" action="{{ route('admin.orders.history') }}" class="search-form" style="
                  display: flex;
                  gap: 0.5rem;
                  flex-wrap: nowrap;
                  width: 100%;
                  max-width: 420px;
              ">
            <input type="text" name="search" placeholder="Search name/email/phone..." value="{{ request('search') }}" style="
                    padding: 0.625rem 1rem;
                    border: 1px solid #e5e7eb;
                    border-radius: 0.5rem;
                    font-size: 0.95rem;
                    flex: 1;
                    min-width: 0;
                " />
            <button type="submit" style="
                        background: #000;
                        color: white;
                        padding: 0.625rem 1.25rem;
                        border: none;
                        border-radius: 0.5rem;
                        font-weight: 600;
                        cursor: pointer;
                        white-space: nowrap;
                    ">
                Search
            </button>
        </form>
    </div>

    {{-- Filter Tabs --}}
    <div class="filter-tabs" style="margin-bottom: 1rem; display: flex; gap: 0.5rem; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 0.5rem;">
        @foreach(['all' => 'All', 'preorder' => 'Preorder', 'order' => 'Order'] as $key => $label)
            <a href="{{ route('admin.orders.history', ['type' => $key]) }}" style="
                        padding: 0.5rem 0.75rem;
                        border-radius: 0.5rem;
                        font-weight: 600;
                        text-decoration: none;
                        background: {{ ($type ?? 'all') === $key ? '#000' : '#f3f4f6' }};
                        color: {{ ($type ?? 'all') === $key ? 'white' : '#111827' }};
                        white-space: nowrap;
                        transition: all 0.2s;
                   ">
                {{ $label }} ({{ $counts[$key] ?? 0 }})
            </a>
        @endforeach
    </div>

    {{-- Desktop Table View --}}
    <div class="desktop-table" style="
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        width: 100%;
    ">
        <div style="overflow-x: auto; width: 100%;">
            <table style="
                width: 100%;
                min-width: 1100px;
                border-collapse: collapse;
            ">
                <thead>
                    <tr style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                        @foreach(['Order', 'Customer', 'Type', 'Product', 'Qty', 'Status', 'Last Update', 'Actions'] as $head)
                            <th style="
                                    padding: 1rem;
                                    text-align: left;
                                    font-weight: 600;
                                    font-size: 0.75rem;
                                    text-transform: uppercase;
                                    color: #6b7280;
                                    white-space: nowrap;
                                ">
                                {{ $head }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $o)
                        @php
                            $ptype = $o->product && $o->product->available_for_preorder ? 'Preorder' : 'Order';
                            $last = $o->histories->sortByDesc('created_at')->first();
                        @endphp

                        <tr style="border-bottom:1px solid #e5e7eb;">
                            <td style="padding:1rem; white-space:nowrap;">#{{ $o->order_number }}</td>

                            <td style="padding:1rem;">
                                <div style="font-weight:600;">{{ $o->name }}</div>
                                <div style="font-size:0.85rem; color:#6b7280;">
                                    {{ $o->email ?? '-' }} · {{ $o->phone ?? '-' }}
                                </div>
                            </td>

                            <td style="padding:1rem;">
                                <span style="background:#f3f4f6; padding:0.25rem 0.5rem; border-radius:0.375rem; font-size:0.8rem; font-weight:600;">
                                    {{ $ptype }}
                                </span>
                            </td>

                            <td style="padding:1rem;">
                                <div style="font-weight:600;">{{ $o->product->name ?? '-' }}</div>
                                <div style="font-size:0.85rem; color:#6b7280;">{{ $o->jersey_type ?? '-' }}</div>
                            </td>

                            <td style="padding:1rem; white-space:nowrap;">{{ $o->quantity }}</td>

                            <td style="padding:1rem;">
                                @php
                                    $statusMap = [
                                        'paid' => ['#dcfce7', '#166534', 'Paid'],
                                        'confirmed' => ['#e0e7ff', '#3730a3', 'Confirmed'],
                                        'pending' => ['#fef9c3', '#854d0e', 'Pending'],
                                    ];
                                    [$bg, $fg, $label] = $statusMap[$o->status] ?? $statusMap['pending'];
                                @endphp
                                <span style="background:{{ $bg }}; color:{{ $fg }}; padding:0.25rem 0.5rem; border-radius:0.375rem; font-size:0.8rem; font-weight:600;">
                                    {{ $label }}
                                </span>
                            </td>

                            <td style="padding:1rem; font-size:0.85rem; color:#6b7280; white-space:nowrap;">
                                {{ $last ? $last->created_at->format('M d, Y H:i') : $o->updated_at->format('M d, Y H:i') }}
                            </td>

                            <td style="padding:1rem; white-space:nowrap;">
                                <a href="{{ route('admin.preorders.show', $o) }}"
                                    style="background:#3b82f6; color:white; padding:0.5rem 0.75rem; border-radius:0.375rem; font-size:0.85rem; font-weight:600; text-decoration:none;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding:3rem; text-align:center; color:#6b7280;">
                                No orders found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="padding:1.5rem; border-top:1px solid #e5e7eb; display:flex; justify-content:center;">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- Mobile List View --}}
    <div class="mobile-list">
        @forelse($orders as $o)
            @php
                $ptype = $o->product && $o->product->available_for_preorder ? 'Preorder' : 'Order';
                $last = $o->histories->sortByDesc('created_at')->first();
                $statusMap = [
                    'paid' => ['#dcfce7', '#166534', 'Paid'],
                    'confirmed' => ['#e0e7ff', '#3730a3', 'Confirmed'],
                    'pending' => ['#fef9c3', '#854d0e', 'Pending'],
                ];
                [$bg, $fg, $statusLabel] = $statusMap[$o->status] ?? $statusMap['pending'];
            @endphp

            <div class="history-card" data-history-id="{{ $o->id }}">
                <div class="history-header" onclick="toggleHistory({{ $o->id }})">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.25rem; font-size: 0.95rem;">
                            #{{ $o->order_number }}
                        </div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">
                            {{ $o->name }}
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                            <span style="background:#f3f4f6; padding:0.25rem 0.5rem; border-radius:0.375rem; font-size:0.75rem; font-weight:600; color:#111827;">
                                {{ $ptype }}
                            </span>
                            <span style="background:{{ $bg }}; color:{{ $fg }}; padding:0.25rem 0.5rem; border-radius:0.375rem; font-size:0.75rem; font-weight:600;">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; margin-left: 0.5rem;">
                        <svg class="chevron" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div class="history-body">
                    <div class="history-details">
                        <div class="detail-row">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">{{ $o->email ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value">{{ $o->phone ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Product</span>
                            <span class="detail-value">
                                <div style="font-weight: 600;">{{ $o->product->name ?? '-' }}</div>
                                @if($o->jersey_type)
                                    <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.25rem;">{{ $o->jersey_type }}</div>
                                @endif
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Quantity</span>
                            <span class="detail-value" style="font-weight: 600;">{{ $o->quantity }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Last Update</span>
                            <span class="detail-value" style="font-size: 0.85rem; color: #6b7280;">
                                {{ $last ? $last->created_at->format('M d, Y H:i') : $o->updated_at->format('M d, Y H:i') }}
                            </span>
                        </div>
                    </div>

                    <div class="history-actions">
                        <a href="{{ route('admin.preorders.show', $o) }}" style="
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
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div style="background: white; border-radius: 0.75rem; padding: 3rem; text-align: center;">
                <p style="color: #6b7280; font-size: 1rem; margin: 0;">No orders found</p>
            </div>
        @endforelse

        {{-- Mobile Pagination --}}
        @if($orders->count() > 0)
            <div style="padding: 1.5rem 0; display: flex; justify-content: center;">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <script>
        // Toggle collapse function for mobile
        function toggleHistory(historyId) {
            const card = document.querySelector(`[data-history-id="${historyId}"]`);
            const body = card.querySelector('.history-body');
            const chevron = card.querySelector('.chevron');
            
            body.classList.toggle('show');
            chevron.classList.toggle('rotate');
            card.classList.toggle('expanded');
        }
    </script>

@endsection