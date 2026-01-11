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
            font-size: 2rem;
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

        .order-item {
            background: var(--gray-50);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
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

        .order-item.full-width {
            grid-column: 1 / -1;
        }

        .custom-fields-list {
            background: var(--gray-50);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .custom-field-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--gray-200);
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

            $currentStatus = $preorder->status ?? 'pending';
            $label = $statusLabels[$currentStatus] ?? ucfirst($currentStatus);
            $style = $statusStyles[$currentStatus] ?? $statusStyles['pending'];
        @endphp
        
        <div style="display:inline-block; {{ $style }} border:1px solid; border-radius: 9999px; padding:0.5rem 1rem; font-weight:600; margin-bottom:1.5rem;">
            Status: {{ $label }}
        </div>

        <!-- Order Details Card -->
        <div class="order-card">
            <div class="order-number-bar">
                <div class="label">Order Number</div>
                <div class="number">{{ $preorder->order_number }}</div>
            </div>

            <div class="order-body">
                <h3 class="order-section-title">Order Summary</h3>

                <div class="order-grid">
                    @if($preorder->product)
                        <div class="order-item full-width">
                            <div class="label">Product</div>
                            <div class="value">{{ $preorder->product->name ?? $preorder->jersey_type }}</div>
                        </div>
                    @endif

                    <div class="order-item">
                        <div class="label">Jersey Type</div>
                        <div class="value">{{ $preorder->jersey_type ?? '-' }}</div>
                    </div>

                    <div class="order-item">
                        <div class="label">Size</div>
                        <div class="value">{{ $preorder->size ?? '-' }}</div>
                    </div>

                    <div class="order-item">
                        <div class="label">Quantity</div>
                        <div class="value">{{ $preorder->quantity }} pcs</div>
                    </div>

                    <div class="order-item">
                        <div class="label">Long Sleeve</div>
                        <div class="value">{{ $preorder->long_sleeve ? 'Yes' : 'No' }}</div>
                    </div>
                </div>

                @if($preorder->custom_fields && count($preorder->custom_fields) > 0)
                    <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--dark); margin: 1rem 0 0.75rem;">Customizations
                    </h4>
                    <div class="custom-fields-list">
                        @foreach($preorder->custom_fields as $field)
                            <div class="custom-field-item">
                                <span class="key">{{ $field['key'] ?? '-' }}</span>
                                <span class="value">{{ $field['value'] ?? '-' }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($preorder->notes)
                    <div class="order-item full-width" style="margin-top: 0.5rem;">
                        <div class="label">Special Requests</div>
                        <div class="value">{{ $preorder->notes }}</div>
                    </div>
                @endif
            </div>

            <div class="order-total-bar">
                <div>
                    <div class="label">Total Price</div>
                    <div class="pay-note">Pay on delivery</div>
                </div>
                <div class="price">{{ $preorder->currency ?? 'MYR' }} {{ number_format($preorder->total_amount, 2) }}</div>
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
                @if($preorder->email)
                    {{ $preorder->phone }} / {{ $preorder->email }}
                @else
                    {{ $preorder->phone }}
                @endif
            </div>
        </div>

        <!-- CTA -->
        <a href="/" class="btn-home">← Back to Home</a>
    </section>
@endsection
