@extends('layouts.app')

@section('page-title', 'Order Management')

@section('content')
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column !important;
                gap: 1rem !important;
            }
            
            .search-container {
                flex-direction: column !important;
                width: 100% !important;
            }
            
            .search-container form {
                width: 100% !important;
                flex-direction: column !important;
            }
            
            .search-container input {
                width: 100% !important;
            }
            
            .summary-grid {
                grid-template-columns: repeat(2, 1fr) !important;
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
        .order-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .order-card.expanded {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .order-header {
            padding: 1rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9fafb;
        }
        
        .order-header:active {
            background: #f3f4f6;
        }
        
        .order-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .order-body.show {
            max-height: 1000px;
        }
        
        .order-details {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .detail-value {
            color: #111827;
            font-size: 0.95rem;
            text-align: right;
            max-width: 60%;
            word-wrap: break-word;
        }
        
        .order-actions {
            padding: 1rem;
            background: #f9fafb;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .chevron {
            transition: transform 0.3s ease;
        }
        
        .chevron.rotate {
            transform: rotate(180deg);
        }
    </style>

    <div class="header-container" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">Manage and monitor all product orders</p>
        </div>
        <div class="search-container" style="display: flex; gap: 1rem;">
            <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" style="padding: 0.625rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.95rem;" />
                <button type="submit" style="background: #000; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; white-space: nowrap;">Search</button>
            </form>
            <a href="{{ route('admin.orders.print', request()->query()) }}" style="background: #111827; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;">Print View</a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border-left: 4px solid #000; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 0.5rem 0; font-weight: 600; text-transform: uppercase;">Total Orders</p>
            <p style="font-size: 1.875rem; font-weight: 800; color: #000; margin: 0;">{{ $counts['total'] }}</p>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border-left: 4px solid #f97316; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 0.5rem 0; font-weight: 600; text-transform: uppercase;">Pending</p>
            <p style="font-size: 1.875rem; font-weight: 800; color: #f97316; margin: 0;">{{ $counts['pending'] }}</p>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border-left: 4px solid #6366f1; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 0.5rem 0; font-weight: 600; text-transform: uppercase;">Confirmed</p>
            <p style="font-size: 1.875rem; font-weight: 800; color: #6366f1; margin: 0;">{{ $counts['confirmed'] }}</p>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border-left: 4px solid #22c55e; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 0.5rem 0; font-weight: 600; text-transform: uppercase;">Paid</p>
            <p style="font-size: 1.875rem; font-weight: 800; color: #22c55e; margin: 0;">{{ $counts['paid'] }}</p>
        </div>
    </div>

    @if($orders->count() > 0)
        <!-- Desktop Table View -->
        <div class="desktop-table" style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Order Number</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Nama</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Email</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Phone</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Product</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Size</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Qty</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Total</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Status</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                                <td style="padding: 1rem; color: #111827; font-weight: 600;">{{ $order->order_number }}</td>
                                <td style="padding: 1rem; color: #111827;">{{ $order->name }}</td>
                                <td style="padding: 1rem; color: #6b7280; font-size: 0.95rem;">{{ $order->email ?? '—' }}</td>
                                <td style="padding: 1rem; color: #6b7280; font-size: 0.95rem;">{{ $order->phone }}</td>
                                <td style="padding: 1rem; color: #111827; font-size: 0.95rem;">{{ optional($order->product)->name ?? $order->jersey_type }}</td>
                                <td style="padding: 1rem; color: #6b7280; font-size: 0.95rem;">
                                    {{ $order->size }}
                                    @if($order->long_sleeve)
                                        <span style="background: #f0f9ff; color: #0369a1; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem;">+LS</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem; color: #111827; font-weight: 600;">{{ $order->quantity }}</td>
                                <td style="padding: 1rem; color: #111827; font-weight: 700;">{{ $order->currency ?? 'MYR' }} {{ number_format($order->total_amount, 2) }}</td>
                                <td style="padding: 1rem;">
                                    @if($order->status === 'pending')
                                        <span style="background: #fef3c7; color: #92400e; padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600;">Pending</span>
                                    @elseif($order->status === 'confirmed')
                                        <span style="background: #e0e7ff; color: #3730a3; padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600;">Confirmed</span>
                                    @elseif($order->status === 'paid')
                                        <span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600;">Paid</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        @if($order->status === 'pending')
                                            <form method="POST" action="{{ route('admin.orders.confirm', $order) }}" style="display: inline;" class="js-confirm" data-title="Confirm this order?" data-text="Status akan diubah menjadi confirmed.">
                                                @csrf
                                                <button type="submit" style="background: #6366f1; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer;">Confirm</button>
                                            </form>
                                        @elseif($order->status === 'confirmed')
                                            <form method="POST" action="{{ route('admin.orders.markPaid', $order) }}" style="display: inline;" class="js-confirm" data-title="Mark as paid?" data-text="Status akan diubah menjadi paid.">
                                                @csrf
                                                <button type="submit" style="background: #22c55e; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer;">Mark Paid</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.orders.show', $order) }}" style="background: #3b82f6; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; text-decoration: none; display: inline-block;">View</a>
                                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="display: inline;" class="js-delete" data-title="Delete this order?" data-text="Tindakan ini tidak dapat dibatalkan.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile List View -->
        <div class="mobile-list">
            @foreach($orders as $order)
                <div class="order-card" data-order-id="{{ $order->id }}">
                    <div class="order-header" onclick="toggleOrder({{ $order->id }})">
                        <div>
                            <div style="font-weight: 700; color: #111827; margin-bottom: 0.25rem;">{{ $order->order_number }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $order->name }}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            @if($order->status === 'pending')
                                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600;">Pending</span>
                            @elseif($order->status === 'confirmed')
                                <span style="background: #e0e7ff; color: #3730a3; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600;">Confirmed</span>
                            @elseif($order->status === 'paid')
                                <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600;">Paid</span>
                            @endif
                            <svg class="chevron" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 7.5L10 12.5L15 7.5" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="order-body">
                        <div class="order-details">
                            <div class="detail-row">
                                <span class="detail-label">Email</span>
                                <span class="detail-value">{{ $order->email ?? '—' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Phone</span>
                                <span class="detail-value">{{ $order->phone }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Product</span>
                                <span class="detail-value">{{ optional($order->product)->name ?? $order->jersey_type }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Size</span>
                                <span class="detail-value">
                                    {{ $order->size }}
                                    @if($order->long_sleeve)
                                        <span style="background: #f0f9ff; color: #0369a1; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; margin-left: 0.25rem;">+LS</span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Quantity</span>
                                <span class="detail-value" style="font-weight: 600;">{{ $order->quantity }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Total</span>
                                <span class="detail-value" style="font-weight: 700; color: #000;">{{ $order->currency ?? 'MYR' }} {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                        <div class="order-actions">
                            @if($order->status === 'pending')
                                <form method="POST" action="{{ route('admin.orders.confirm', $order) }}" style="flex: 1;" class="js-confirm" data-title="Confirm this order?" data-text="Status akan diubah menjadi confirmed.">
                                    @csrf
                                    <button type="submit" style="width: 100%; background: #6366f1; color: white; padding: 0.5rem; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; cursor: pointer;">Confirm</button>
                                </form>
                            @elseif($order->status === 'confirmed')
                                <form method="POST" action="{{ route('admin.orders.markPaid', $order) }}" style="flex: 1;" class="js-confirm" data-title="Mark as paid?" data-text="Status akan diubah menjadi paid.">
                                    @csrf
                                    <button type="submit" style="width: 100%; background: #22c55e; color: white; padding: 0.5rem; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; cursor: pointer;">Mark Paid</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.orders.show', $order) }}" style="flex: 1; background: #3b82f6; color: white; padding: 0.5rem; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; display: block; text-align: center;">View</a>
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="flex: 1;" class="js-delete" data-title="Delete this order?" data-text="Tindakan ini tidak dapat dibatalkan.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width: 100%; background: #ef4444; color: white; padding: 0.5rem; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; cursor: pointer;">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($orders->hasPages())
            <div style="padding: 1.5rem 0; display: flex; justify-content: center;">
                <nav aria-label="Pagination" style="display:flex; gap:0.5rem; align-items:center;">
                    @if ($orders->onFirstPage())
                        <span style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #9ca3af; background:#f9fafb; border-radius: 0.375rem; font-size: 0.875rem;">« Prev</span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">« Prev</a>
                    @endif

                    @php
                        $start = max(1, $orders->currentPage() - 2);
                        $end = min($orders->lastPage(), $orders->currentPage() + 2);
                    @endphp
                    @if ($start > 1)
                        <a href="{{ $orders->url(1) }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">1</a>
                        @if ($start > 2)
                            <span style="padding: 0.5rem 0.75rem; color: #9ca3af; font-size: 0.875rem;">…</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $orders->currentPage())
                            <span style="padding: 0.5rem 0.75rem; border: 1px solid #111827; color: #fff; background:#111827; border-radius: 0.375rem; font-size: 0.875rem; font-weight:700;">{{ $page }}</span>
                        @else
                            <a href="{{ $orders->url($page) }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($end < $orders->lastPage())
                        @if ($end < $orders->lastPage() - 1)
                            <span style="padding: 0.5rem 0.75rem; color: #9ca3af; font-size: 0.875rem;">…</span>
                        @endif
                        <a href="{{ $orders->url($orders->lastPage()) }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">{{ $orders->lastPage() }}</a>
                    @endif

                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #111827; background:#fff; border-radius: 0.375rem; font-size: 0.875rem; text-decoration:none;">Next »</a>
                    @else
                        <span style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; color: #9ca3af; background:#f9fafb; border-radius: 0.375rem; font-size: 0.875rem;">Next »</span>
                    @endif
                </nav>
            </div>
        @endif
    @else
        <div style="background: white; border-radius: 0.75rem; padding: 3rem; text-align: center;">
            <p style="color: #6b7280; font-size: 1rem; margin: 0;">Tidak ada order ditemukan</p>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle collapse function for mobile
        function toggleOrder(orderId) {
            const card = document.querySelector(`[data-order-id="${orderId}"]`);
            const body = card.querySelector('.order-body');
            const chevron = card.querySelector('.chevron');
            
            body.classList.toggle('show');
            chevron.classList.toggle('rotate');
            card.classList.toggle('expanded');
        }

        // Confirm forms
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

        // Delete forms
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
