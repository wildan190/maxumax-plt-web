@extends('layouts.app')

@section('page-title', 'Order History')

@section('content')

    {{-- Header --}}
    <div style="
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
        <form method="GET" action="{{ route('admin.orders.history') }}" style="
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
    <div style="margin-bottom: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
        @foreach(['all' => 'All', 'preorder' => 'Preorder', 'order' => 'Order'] as $key => $label)
            <a href="{{ route('admin.orders.history', ['type' => $key]) }}" style="
                        padding: 0.5rem 0.75rem;
                        border-radius: 0.5rem;
                        font-weight: 600;
                        text-decoration: none;
                        background: #f3f4f6;
                        color: #111827;
                        white-space: nowrap;
                   ">
                {{ $label }} ({{ $counts[$key] ?? 0 }})
                @if(($type ?? 'all') === $key)
                    <span style="margin-left:0.25rem;">•</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Table Card --}}
    <div style="
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        width: 100%;
    ">

        {{-- Horizontal Scroll Wrapper --}}
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
                                <span
                                    style="background:#f3f4f6; padding:0.25rem 0.5rem; border-radius:0.375rem; font-size:0.8rem; font-weight:600;">
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
                                <span
                                    style="background:{{ $bg }}; color:{{ $fg }}; padding:0.25rem 0.5rem; border-radius:0.375rem; font-size:0.8rem; font-weight:600;">
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

@endsection