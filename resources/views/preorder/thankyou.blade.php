@extends('layouts.public')

@section('title', 'Thank You — Pre-order Jersey')

@push('styles')
    <style>
        :root {
            --primary: #000000;
            --primary-dark: #1f2937;
            --dark: #0f172a;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-500: #64748b;
            --success: #10b981;
            --success-light: #ecfdf5;
            --success-border: #a7f3d0;
            --success-dark: #065f46;
        }

        .thankyou-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 4rem 2rem;
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: #fff;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .thankyou-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin: 0 0 0.5rem;
        }

        .thankyou-subtitle {
            font-size: 1.125rem;
            color: var(--gray-500);
            margin: 0 0 2.5rem;
        }

        .order-card {
            background: #fff;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            text-align: left;
        }

        .order-number-bar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            padding: 1.5rem;
            text-align: center;
        }

        .order-number-bar .label {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }

        .order-number-bar .number {
            font-size: 1.5rem;
            /* Reduced size to fit long/multiple numbers if needed */
            font-weight: 800;
        }

        .order-body {
            padding: 1.5rem;
        }

        .order-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0 0 1rem;
        }

        .order-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .order-item-card {
            background: var(--gray-50);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
        }

        .order-item .label {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-bottom: 0.25rem;
        }

        .order-item .value {
            font-weight: 600;
            color: var(--dark);
        }

        .custom-fields-list {
            background: #fff;
            padding: 0.75rem;
            border-radius: 0.25rem;
            border: 1px dashed var(--gray-200);
            margin-top: 0.5rem;
        }

        .custom-field-item {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.85rem;
        }

        .custom-field-item:last-child {
            border-bottom: none;
        }

        .custom-field-item .key {
            color: var(--gray-500);
            font-weight: 500;
        }

        .custom-field-item .value {
            font-weight: 700;
            color: var(--dark);
        }

        .order-total-bar {
            background: var(--gray-50);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--gray-200);
        }

        .order-total-bar .label {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark);
        }

        .order-total-bar .price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .order-total-bar .pay-note {
            font-size: 0.85rem;
            color: var(--gray-500);
        }

        .next-steps-card {
            background: var(--success-light);
            border: 1px solid var(--success-border);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .next-steps-card h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--success-dark);
            margin: 0 0 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .next-steps-card ol {
            margin: 0;
            padding-left: 1.5rem;
            color: var(--success-dark);
        }

        .next-steps-card li {
            margin-bottom: 0.5rem;
        }

        .contact-card {
            background: var(--gray-50);
            border-radius: 1rem;
            padding: 1.25rem;
            margin-bottom: 2rem;
        }

        .contact-card .label {
            font-size: 0.9rem;
            color: var(--gray-500);
            margin-bottom: 0.5rem;
        }

        .contact-card .value {
            font-weight: 600;
            color: var(--dark);
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%);
            color: #fff;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 600px) {
            .order-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="thankyou-container">
        <!-- Success Header -->
        <div class="success-icon">✓</div>
        <h1 class="thankyou-title">Thank You!</h1>
        <p class="thankyou-subtitle">Your pre-order has been successfully received</p>

        @php
            // Assume homogeneous status for the group info or take first
            // Note: $preorders IS A COLLECTION
            $firstOrder = $preorders->first();

            $statusLabels = [
                'pending' => 'Pending Admin Confirmation',
                'confirmed' => 'Order Confirmed',
                'paid' => 'Payment Successful',
                'packing' => 'Packing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
                'refunded' => 'Refunded',
            ];

            $statusStyles = [
                'pending' => 'background:#fff7ed; color:#9a3412; border-color:#fed7aa;',
                'confirmed' => 'background:#ecfdf5; color:#065f46; border-color:#a7f3d0;',
                'paid' => 'background:#ecfdf5; color:#065f46; border-color:#a7f3d0;',
                'packing' => 'background:#eff6ff; color:#1e40af; border-color:#bfdbfe;',
                'shipped' => 'background:#eff6ff; color:#1e40af; border-color:#bfdbfe;',
                'delivered' => 'background:#f0fdf4; color:#166534; border-color:#bbf7d0;',
                'cancelled' => 'background:#fef2f2; color:#991b1b; border-color:#fecaca;',
                'refunded' => 'background:#fef2f2; color:#991b1b; border-color:#fecaca;',
            ];

            $currentStatus = $firstOrder->status ?? 'pending';
            $label = $statusLabels[$currentStatus] ?? ucfirst($currentStatus);
            $style = $statusStyles[$currentStatus] ?? $statusStyles['pending'];

            $grandTotal = $preorders->sum('total_amount');
            $currency = $firstOrder->currency ?? session('currency', 'MYR');
        @endphp

        <div
            style="display:inline-block; {{ $style }} border:1px solid; border-radius: 9999px; padding:0.5rem 1rem; font-weight:600; margin-bottom:1.5rem;">
            Status: {{ $label }}
        </div>

        <!-- Order Details Card -->
        <div class="order-card">
            <div class="order-number-bar">
                <div class="label">Order Reference</div>
                <div class="number">
                    @if($preorders->count() > 1)
                        {{ $firstOrder->order_number }} + {{ $preorders->count() - 1 }} others
                    @else
                        {{ $firstOrder->order_number }}
                    @endif
                </div>
            </div>

            <div class="order-body">
                <h3 class="order-section-title">Order Summary ({{ $preorders->sum('quantity') }} Items)</h3>

                <div class="items-list">
                    @foreach($preorders as $preorder)
                        @if(!empty($preorder->items) && is_array($preorder->items) && count($preorder->items) > 0)
                            @foreach($preorder->items as $index => $item)
                                <div class="order-item-card">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                                        <div style="font-weight:700;">
                                            {{ $preorder->product->name ?? $preorder->jersey_type }}
                                            <span
                                                style="font-weight:400; color:var(--gray-500)">({{ $item['variant_name'] ?? 'Var' }})</span>
                                        </div>
                                        <div style="font-weight:700;">{{ $currency }}
                                            {{ number_format($item['total_price'] ?? ($item['line_total'] ?? 0), 2) }}
                                        </div>
                                    </div>

                                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; font-size:0.9rem;">
                                        <div><span style="color:var(--gray-500)">Order #:</span> <span
                                                style="font-family:monospace">{{ $preorder->order_number }}</span></div>
                                        <div><span style="color:var(--gray-500)">Type:</span> {{ $preorder->jersey_type ?? '-' }}</div>
                                        <div><span style="color:var(--gray-500)">Size:</span>
                                            <strong>{{ $item['variant_name'] ?? '-' }}</strong>
                                        </div>
                                        <div><span style="color:var(--gray-500)">Qty:</span> {{ $item['quantity'] ?? 1 }}</div>
                                        <div><span style="color:var(--gray-500)">Sleeve:</span>
                                            {{ $preorder->long_sleeve ? 'Long' : 'Short' }}</div>
                                    </div>

                                    @if($index === 0 && $preorder->custom_fields && count($preorder->custom_fields) > 0)
                                        <div class="custom-fields-list">
                                            <div style="font-size:0.8rem; color:var(--gray-500); margin-bottom:0.25rem;">Customization:
                                            </div>
                                            @foreach($preorder->custom_fields as $field)
                                                <div class="custom-field-item">
                                                    <span class="key">{{ $field['key'] ?? '-' }}</span>
                                                    <span class="value">{{ $field['value'] ?? '-' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            {{-- Legacy/Single Item Display --}}
                            <div class="order-item-card">
                                <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                                    <div style="font-weight:700;">{{ $preorder->product->name ?? $preorder->jersey_type }}</div>
                                    <div style="font-weight:700;">{{ $currency }} {{ number_format($preorder->total_amount, 2) }}
                                    </div>
                                </div>

                                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; font-size:0.9rem;">
                                    <div><span style="color:var(--gray-500)">Order #:</span> <span
                                            style="font-family:monospace">{{ $preorder->order_number }}</span></div>
                                    <div><span style="color:var(--gray-500)">Type:</span> {{ $preorder->jersey_type ?? '-' }}</div>
                                    <div><span style="color:var(--gray-500)">Size:</span>
                                        <strong>{{ $preorder->size ?? '-' }}</strong>
                                    </div>
                                    <div><span style="color:var(--gray-500)">Qty:</span> {{ $preorder->quantity }}</div>
                                    <div><span style="color:var(--gray-500)">Sleeve:</span>
                                        {{ $preorder->long_sleeve ? 'Long' : 'Short' }}</div>
                                </div>

                                @if($preorder->custom_fields && count($preorder->custom_fields) > 0)
                                    <div class="custom-fields-list">
                                        @foreach($preorder->custom_fields as $field)
                                            <div class="custom-field-item">
                                                <span class="key">{{ $field['key'] ?? '-' }}</span>
                                                <span class="value">{{ $field['value'] ?? '-' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>

                @if($firstOrder->notes)
                    <div class="order-item full-width"
                        style="margin-top: 1rem; background: var(--gray-50); padding: 1rem; border-radius:0.5rem;">
                        <div class="label" style="font-weight:600; margin-bottom:0.25rem;">Special Requests</div>
                        <div class="value">{{ $firstOrder->notes }}</div>
                    </div>
                @endif

                @if($firstOrder->shipping_cost > 0)
                    <div style="margin-top: 1rem; border-top: 1px dashed var(--gray-200); padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <div style="color: var(--gray-500);">
                                Shipping 
                                @if($firstOrder->shipping_courier_name)
                                    <span style="font-size: 0.85rem; background: var(--gray-100); padding: 2px 6px; border-radius: 4px; margin-left: 5px;">
                                        {{ $firstOrder->shipping_courier_name }} - {{ $firstOrder->shipping_service_name }}
                                    </span>
                                @endif
                            </div>
                            <div style="font-weight: 600;">{{ $currency }} {{ number_format($firstOrder->shipping_cost, 2) }}</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="order-total-bar">
                <div>
                    <div class="label">Total Price</div>
                    <div class="pay-note">
                        @if($firstOrder->status == 'paid')
                            Paid via Stripe
                        @elseif($firstOrder->status == 'pending')
                            Pending Payment/Confirmation
                        @else
                            {{ ucfirst($firstOrder->status) }}
                        @endif
                    </div>
                </div>
                <div class="price">{{ $currency }} {{ number_format($grandTotal, 2) }}</div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="next-steps-card">
            <h3>📦 What Happens Next</h3>
            <ol>
                <li>We will contact you via WhatsApp/Email to confirm your order</li>
                <li>Your jerseys will be prepared according to your specifications</li>
                <li>We will visit Brunei in late January 2026</li>
                <li>Pay and receive your jerseys when we meet</li>
            </ol>
        </div>

        <!-- Contact Info -->
        <div class="contact-card">
            <div class="label">Contact for this order</div>
            <div class="value">
                @if($firstOrder->email)
                    {{ $firstOrder->phone }} / {{ $firstOrder->email }}
                @else
                    {{ $firstOrder->phone }}
                @endif
            </div>
        </div>

        <!-- CTA -->
        <a href="/" class="btn-home">← Back to Home</a>
    </section>
@endsection