@extends('layouts.app')

@section('page-title','Order History')

@section('content')
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; color: #111827; margin: 0;">Order History</h1>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">Filter berdasarkan tipe order</p>
        </div>
        <form method="GET" action="{{ route('admin.orders.history') }}" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" placeholder="Search name/email/phone..." value="{{ request('search') }}" style="padding: 0.625rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.95rem;" />
            <button type="submit" style="background: #000; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">Search</button>
        </form>
    </div>

    <div style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <a href="{{ route('admin.orders.history', ['type' => 'all']) }}"
           style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:0.5rem; text-decoration:none; font-weight:600; background:#f3f4f6;color:#111827;">
            All ({{ $counts['all'] ?? 0 }}) @if(($type ?? 'all')==='all') <span style="margin-left:0.25rem; color:#111827;">•</span> @endif
        </a>
        <a href="{{ route('admin.orders.history', ['type' => 'preorder']) }}"
           style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:0.5rem; text-decoration:none; font-weight:600; background:#f3f4f6;color:#111827;">
            Preorder ({{ $counts['preorder'] ?? 0 }}) @if(($type ?? 'all')==='preorder') <span style="margin-left:0.25rem; color:#111827;">•</span> @endif
        </a>
        <a href="{{ route('admin.orders.history', ['type' => 'order']) }}"
           style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:0.5rem; text-decoration:none; font-weight:600; background:#f3f4f6;color:#111827;">
            Order ({{ $counts['order'] ?? 0 }}) @if(($type ?? 'all')==='order') <span style="margin-left:0.25rem; color:#111827;">•</span> @endif
        </a>
    </div>

    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">ID</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Customer</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Type</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Product</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Qty</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Last Update</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                        @php
                            $ptype = $o->product && $o->product->available_for_preorder ? 'Preorder' : 'Order';
                            $last = $o->histories->sortByDesc('created_at')->first();
                        @endphp
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 1rem;">#{{ $o->id }}</td>
                            <td style="padding: 1rem;">
                                <div style="font-weight:600; color:#111827;">{{ $o->name }}</div>
                                <div style="color:#6b7280; font-size:0.85rem;">{{ $o->email ?? '-' }} · {{ $o->phone ?? '-' }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                <span style="display:inline-block; background:#f3f4f6; color:#111827; padding:0.25rem 0.5rem; border-radius:0.375rem; font-weight:600; font-size:0.8rem;">{{ $ptype }}</span>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="font-weight:600; color:#111827;">{{ $o->product->name ?? '-' }}</div>
                                <div style="color:#6b7280; font-size:0.85rem;">{{ $o->jersey_type ?? '-' }}</div>
                            </td>
                            <td style="padding: 1rem;">{{ $o->quantity }}</td>
                            <td style="padding: 1rem;">
                                @if($o->status === 'paid')
                                    <span style="display:inline-block; background:#dcfce7; color:#166534; padding:0.25rem 0.5rem; border-radius:0.375rem; font-weight:600; font-size:0.8rem;">Paid</span>
                                @elseif($o->status === 'confirmed')
                                    <span style="display:inline-block; background:#e0e7ff; color:#3730a3; padding:0.25rem 0.5rem; border-radius:0.375rem; font-weight:600; font-size:0.8rem;">Confirmed</span>
                                @else
                                    <span style="display:inline-block; background:#fef9c3; color:#854d0e; padding:0.25rem 0.5rem; border-radius:0.375rem; font-weight:600; font-size:0.8rem;">Pending</span>
                                @endif
                            </td>
                            <td style="padding: 1rem;">
                                <div style="color:#6b7280; font-size:0.9rem;">{{ $last ? $last->created_at->format('M d, Y H:i') : $o->updated_at->format('M d, Y H:i') }}</div>
                                @if($last && $last->note)
                                    <div style="color:#9ca3af; font-size:0.8rem;">{{ $last->note }}</div>
                                @endif
                            </td>
                            <td style="padding: 1rem;">
                                <div style="display:flex; gap:0.5rem;">
                                    @if($o->status === 'pending')
                                        <form method="POST" action="{{ route('admin.preorders.confirm', $o->id) }}">
                                            @csrf
                                            <button type="submit" style="background: #6366f1; color: white; padding: 0.5rem 0.75rem; border: none; border-radius: 0.375rem; font-size: 0.85rem; font-weight: 600; cursor:pointer;">Confirm</button>
                                        </form>
                                    @elseif($o->status === 'confirmed')
                                        <form method="POST" action="{{ route('admin.preorders.markPaid', $o->id) }}">
                                            @csrf
                                            <button type="submit" style="background: #22c55e; color: white; padding: 0.5rem 0.75rem; border: none; border-radius: 0.375rem; font-size: 0.85rem; font-weight: 600; cursor:pointer;">Mark Paid</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.preorders.show', $o->id) }}" style="background: #3b82f6; color: white; padding: 0.5rem 0.75rem; border: none; border-radius: 0.375rem; font-size: 0.85rem; font-weight: 600; text-decoration: none;">View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 3rem 1rem; text-align: center; color: #6b7280;">
                                <p style="margin: 0; font-size: 1rem;">No orders found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: center;">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
