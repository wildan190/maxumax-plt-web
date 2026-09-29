<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }} — {{ config('app.name') }}</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            font-size: 13px;
            line-height: 1.5;
        }

        .page {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* ── Header ── */
        .header {
            background: #0f172a;
            color: #fff;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header-brand { font-size: 1.25rem; font-weight: 800; letter-spacing: -0.02em; }
        .header-brand span { color: #6366f1; }
        .header-meta { text-align: right; }
        .header-meta .order-num { font-size: 1rem; font-weight: 700; font-family: monospace; }
        .header-meta .order-date { font-size: 0.75rem; color: #94a3b8; margin-top: 2px; }

        /* ── Status badge ── */
        .status-bar {
            padding: 0.6rem 2rem;
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .badge-pending   { background:#fef3c7; color:#92400e; }
        .badge-confirmed { background:#e0e7ff; color:#3730a3; }
        .badge-paid      { background:#d1fae5; color:#065f46; }
        .badge-shipped, .badge-delivered, .badge-completed { background:#ccfbf1; color:#134e4a; }
        .badge-cancelled, .badge-refunded { background:#fee2e2; color:#991b1b; }
        .badge-default   { background:#f1f5f9; color:#475569; }

        /* ── Body ── */
        .body { padding: 1.75rem 2rem; }

        /* ── Section ── */
        .section { margin-bottom: 1.5rem; }
        .section-title {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            margin-bottom: 0.75rem;
            padding-bottom: 0.4rem;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ── Grid ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }

        .field-label { font-size: 0.65rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; }
        .field-value { font-weight: 600; color: #0f172a; margin-top: 2px; }
        .field-value.mono { font-family: monospace; }
        .field-value.normal { font-weight: 400; color: #475569; }

        /* ── Items table ── */
        table { width: 100%; border-collapse: collapse; margin-top: 0.25rem; }
        thead tr { background: #f8fafc; }
        thead th {
            padding: 0.5rem 0.75rem;
            text-align: left;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            border-bottom: 1px solid #e2e8f0;
        }
        thead th.right { text-align: right; }
        thead th.center { text-align: center; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody td { padding: 0.6rem 0.75rem; color: #334155; }
        tbody td.right { text-align: right; }
        tbody td.center { text-align: center; font-weight: 700; }
        tbody td.mono { font-family: monospace; font-size: 0.8rem; }
        tfoot tr { background: #f8fafc; }
        tfoot td { padding: 0.5rem 0.75rem; }
        tfoot td.right { text-align: right; }

        .item-name { font-weight: 700; color: #0f172a; }
        .item-meta { font-size: 0.7rem; color: #64748b; margin-top: 2px; }
        .tag {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            background: #f1f5f9;
            color: #475569;
            margin-right: 3px;
        }
        .tag-blue { background: #eff6ff; color: #1e40af; }
        .tag-amber { background: #fffbeb; color: #92400e; }

        .total-row td { font-weight: 700; color: #0f172a; }
        .grand-total td {
            font-weight: 800;
            font-size: 1rem;
            color: #4f46e5;
            border-top: 2px solid #e2e8f0;
            padding-top: 0.75rem;
        }
        .currency { font-size: 0.7rem; color: #94a3b8; margin-right: 2px; font-weight: 600; }

        /* ── History ── */
        .history-item {
            display: flex;
            gap: 0.75rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .history-item:last-child { border-bottom: none; }
        .history-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #6366f1;
            flex-shrink: 0;
            margin-top: 5px;
        }
        .history-time { font-size: 0.7rem; color: #94a3b8; }
        .history-note { font-size: 0.75rem; color: #334155; }

        /* ── Footer ── */
        .print-footer {
            text-align: center;
            font-size: 0.65rem;
            color: #94a3b8;
            padding: 1rem 2rem 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        /* ── Print button (screen only) ── */
        .print-actions {
            max-width: 800px;
            margin: 1rem auto 0;
            padding: 0 0 2rem;
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1.1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-dark { background: #0f172a; color: #fff; }
        .btn-outline { background: #fff; color: #475569; border: 1px solid #e2e8f0; }

        /* ── Print media ── */
        @media print {
            body { background: #fff; }
            .page { box-shadow: none; border-radius: 0; margin: 0; max-width: 100%; }
            .print-actions { display: none !important; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

{{-- Print action buttons (hidden when printing) --}}
<div class="print-actions">
    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline">← Back to Detail</a>
    <button onclick="window.print()" class="btn btn-dark">🖨 Print / Save as PDF</button>
</div>

<div class="page">

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="header-brand">{{ config('app.name') }}<span>.</span></div>
            <div style="font-size:0.75rem; color:#94a3b8; margin-top:4px;">Order Invoice</div>
        </div>
        <div class="header-meta">
            <div class="order-num">#{{ $order->order_number }}</div>
            <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
    </div>

    {{-- Status bar --}}
    @php
        $badgeClass = match($order->status) {
            'pending'   => 'badge-pending',
            'confirmed' => 'badge-confirmed',
            'paid'      => 'badge-paid',
            'shipped', 'delivered', 'completed' => 'badge-shipped',
            'cancelled', 'refunded' => 'badge-cancelled',
            default     => 'badge-default',
        };
    @endphp
    <div class="status-bar">
        Order Status:
        <span class="badge {{ $badgeClass }}">{{ $order->status }}</span>
        @if($order->shipping_status)
            &nbsp;·&nbsp; Shipping:
            <span class="badge badge-default">{{ $order->shipping_status }}</span>
        @endif
    </div>

    <div class="body">

        {{-- Customer & Order Info --}}
        <div class="section">
            <div class="section-title">Customer & Order Information</div>
            <div class="grid-3">
                <div>
                    <div class="field-label">Customer Name</div>
                    <div class="field-value">{{ $order->name }}</div>
                </div>
                <div>
                    <div class="field-label">Email</div>
                    <div class="field-value normal">{{ $order->email ?? '—' }}</div>
                </div>
                <div>
                    <div class="field-label">Phone</div>
                    <div class="field-value normal">{{ $order->phone ?? '—' }}</div>
                </div>
                <div>
                    <div class="field-label">Order Number</div>
                    <div class="field-value mono">{{ $order->order_number }}</div>
                </div>
                <div>
                    <div class="field-label">Order Date</div>
                    <div class="field-value normal">{{ $order->created_at->format('d M Y') }}</div>
                </div>
                <div>
                    <div class="field-label">Currency</div>
                    <div class="field-value">{{ $order->currency ?? 'MYR' }}</div>
                </div>
            </div>
            @if($order->address)
            <div style="margin-top:0.75rem;">
                <div class="field-label">Shipping Address</div>
                <div class="field-value normal" style="margin-top:3px;">{{ $order->address }}</div>
            </div>
            @endif
        </div>

        {{-- Product Items --}}
        <div class="section">
            <div class="section-title">Product Items</div>
            @php
                $itemsList = !empty($order->items) && is_array($order->items) ? $order->items : [];
                $shippingCost = (float) ($order->shipping_cost ?? 0);
                $itemsSubtotal = 0;
            @endphp
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="center">Qty</th>
                        <th class="right">Unit Price</th>
                        <th class="right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($itemsList) > 0)
                        @foreach($itemsList as $item)
                            @php
                                $qty = (int) ($item['quantity'] ?? 1);
                                $unitPrice = (float) ($item['unit_price'] ?? ($item['total_price'] ?? 0) / max(1, $qty));
                                $lineTotal = (float) ($item['total_price'] ?? ($unitPrice * $qty));
                                $itemsSubtotal += $lineTotal;
                            @endphp
                            <tr>
                                <td>
                                    <div class="item-name">{{ optional($order->product)->name ?? 'Item' }}</div>
                                    <div class="item-meta">
                                        @if(!empty($item['variant_name']))
                                            <span class="tag">{{ $item['variant_name'] }}</span>
                                        @endif
                                        @if(!empty($item['long_sleeve']))
                                            <span class="tag tag-blue">Long Sleeve</span>
                                        @endif
                                        @if(!empty($item['custom_fields']))
                                            @foreach($item['custom_fields'] as $cf)
                                                @if(!empty($cf['key']) || !empty($cf['value']))
                                                    <span class="tag tag-amber">{{ $cf['key'] ?? '' }}: {{ $cf['value'] ?? '' }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                                <td class="center">{{ $qty }}</td>
                                <td class="right"><span class="currency">{{ $order->currency ?? 'MYR' }}</span>{{ number_format($unitPrice, 2) }}</td>
                                <td class="right"><span class="currency">{{ $order->currency ?? 'MYR' }}</span>{{ number_format($lineTotal, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        @php
                            $unitPrice = $order->unit_price > 0 ? (float) $order->unit_price : max(0, ((float)$order->total_amount - $shippingCost) / max(1, (int)$order->quantity));
                            $itemsSubtotal = $unitPrice * (int)$order->quantity;
                        @endphp
                        <tr>
                            <td>
                                <div class="item-name">{{ optional($order->product)->name ?? $order->jersey_type }}</div>
                                <div class="item-meta">
                                    @if($order->size) <span class="tag">Size: {{ $order->size }}</span> @endif
                                    @if($order->long_sleeve) <span class="tag tag-blue">Long Sleeve</span> @endif
                                </div>
                            </td>
                            <td class="center">{{ $order->quantity }}</td>
                            <td class="right"><span class="currency">{{ $order->currency ?? 'MYR' }}</span>{{ number_format($unitPrice, 2) }}</td>
                            <td class="right"><span class="currency">{{ $order->currency ?? 'MYR' }}</span>{{ number_format($itemsSubtotal, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="right" style="color:#64748b; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;">Items Subtotal</td>
                        <td class="right" style="font-weight:700;"><span class="currency">{{ $order->currency ?? 'MYR' }}</span>{{ number_format($itemsSubtotal, 2) }}</td>
                    </tr>
                    @if($shippingCost > 0)
                    <tr>
                        <td colspan="3" class="right" style="color:#64748b; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;">
                            Shipping{{ $order->shipping_courier_name ? ' (' . $order->shipping_courier_name . ')' : '' }}
                        </td>
                        <td class="right" style="font-weight:700;"><span class="currency">{{ $order->currency ?? 'MYR' }}</span>{{ number_format($shippingCost, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="grand-total">
                        <td colspan="3" class="right">Total</td>
                        <td class="right"><span class="currency" style="color:#6366f1;">{{ $order->currency ?? 'MYR' }}</span>{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Shipping Info --}}
        @if($order->shipping_courier_name || $order->tracking_number)
        <div class="section">
            <div class="section-title">Shipping Information</div>
            <div class="grid-3">
                @if($order->shipping_courier_name)
                <div>
                    <div class="field-label">Courier</div>
                    <div class="field-value">{{ $order->shipping_courier_name }}</div>
                </div>
                @endif
                @if($order->shipping_service_name)
                <div>
                    <div class="field-label">Service</div>
                    <div class="field-value normal">{{ $order->shipping_service_name }}</div>
                </div>
                @endif
                @if($order->tracking_number)
                <div>
                    <div class="field-label">Tracking Number</div>
                    <div class="field-value mono">{{ $order->tracking_number }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Payment Info --}}
        @if($order->stripe_payment_intent_id)
        <div class="section">
            <div class="section-title">Payment Information</div>
            <div class="grid-2">
                <div>
                    <div class="field-label">Method</div>
                    <div class="field-value">{{ strtoupper($order->payment_method ?? 'Stripe') }}</div>
                </div>
                <div>
                    <div class="field-label">Payment Intent</div>
                    <div class="field-value mono" style="font-size:0.7rem; word-break:break-all;">{{ $order->stripe_payment_intent_id }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Order History --}}
        @if($order->histories && $order->histories->count() > 0)
        <div class="section">
            <div class="section-title">Order History</div>
            @foreach($order->histories->sortByDesc('created_at') as $h)
            <div class="history-item">
                <div class="history-dot"></div>
                <div>
                    <div class="history-note">{{ $h->note ?? ($h->old_status . ' → ' . $h->new_status) }}</div>
                    <div class="history-time">{{ $h->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>{{-- end body --}}

    <div class="print-footer">
        Generated by {{ config('app.name') }} · {{ now()->format('d M Y, H:i') }} · Order #{{ $order->order_number }}
    </div>

</div>{{-- end page --}}

<script>
    // Auto-trigger browser print dialog on load
    window.addEventListener('load', function () {
        window.print();
    });
</script>
</body>
</html>
