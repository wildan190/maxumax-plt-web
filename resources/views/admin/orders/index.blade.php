@extends('layouts.app')

@section('page-title', 'Order Management')

@section('content')
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">Manage and monitor all product orders</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" placeholder="Search by name/email/order number..." value="{{ request('search') }}" style="padding: 0.625rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.95rem;" />
                <button type="submit" style="background: #000; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">Search</button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
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

    <!-- Table -->
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
        @if($orders->count() > 0)
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
                        @forelse($orders as $order)
                            <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                                <td style="padding: 1rem; color: #111827; font-weight: 600;">
                                    {{ $order->order_number }}
                                </td>
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
                                                <button type="submit" style="background: #6366f1; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">Confirm</button>
                                            </form>
                                        @elseif($order->status === 'confirmed')
                                            <form method="POST" action="{{ route('admin.orders.markPaid', $order) }}" style="display: inline;" class="js-confirm" data-title="Mark as paid?" data-text="Status akan diubah menjadi paid.">
                                                @csrf
                                                <button type="submit" style="background: #22c55e; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">Mark Paid</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.orders.show', $order) }}" style="background: #3b82f6; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">View</a>
                                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="display: inline;" class="js-delete" data-title="Delete this order?" data-text="Tindakan ini tidak dapat dibatalkan.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="padding: 3rem 1rem; text-align: center; color: #6b7280;">
                                    <p style="margin: 0; font-size: 1rem;">No orders found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: center;">
                {{ $orders->links() }}
            </div>
        @else
            <div style="padding: 3rem; text-align: center;">
                <p style="color: #6b7280; font-size: 1rem; margin: 0;">Tidak ada order ditemukan</p>
            </div>
        @endif
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
