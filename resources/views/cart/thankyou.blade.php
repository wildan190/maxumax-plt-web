@extends('layouts.public')

@section('title', 'Order Confirmed - MaxuMax')

@push('styles')
    <style>
        .thankyou-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 3rem 1rem;
        }
        
        .success-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .thankyou-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 0.5rem;
        }
        
        .thankyou-subtitle {
            color: #64748b;
            font-size: 1.125rem;
            margin: 0 0 1.5rem;
        }
        
        .status-badge {
            display: inline-block;
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #fed7aa;
            border-radius: 9999px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .order-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .order-card-title {
            font-weight: 800;
            color: #0f172a;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .order-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .order-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: all 0.2s;
        }
        
        .order-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .order-number {
            font-weight: 700;
            color: #111827;
            font-size: 1.125rem;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }
        
        .order-product {
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .order-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .order-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #111827;
            color: #fff;
        }
        
        .btn-primary:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .btn-secondary {
            background: #fff;
            color: #111827;
            border: 1px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        
        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-top: 2rem;
        }
        
        .info-box-title {
            font-weight: 700;
            color: #1e40af;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-box-text {
            color: #1e3a8a;
            font-size: 0.875rem;
            line-height: 1.6;
            margin: 0;
        }
        
        .empty-orders {
            text-align: center;
            padding: 3rem 2rem;
            color: #64748b;
        }
        
        @media (max-width: 640px) {
            .thankyou-container {
                padding: 2rem 1rem;
            }
            
            .thankyou-title {
                font-size: 2rem;
            }
            
            .order-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            
            .order-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <section class="thankyou-container">
        <div class="success-header">
            <div class="success-icon">✓</div>
            <h1 class="thankyou-title">Order Confirmed!</h1>
            <p class="thankyou-subtitle">Your order has been successfully received</p>
            @php
                $status = 'pending';
                $isStripe = false;
                if(isset($orders) && count($orders) > 0) {
                    $firstOrder = $orders[0];
                    $status = $firstOrder->status;
                    if($firstOrder->stripe_payment_intent_id) {
                        $isStripe = true;
                    }
                }
            @endphp
            
            @if($status === 'paid')
                <span class="status-badge" style="background: #dcfce7; color: #166534; border-color: #bbf7d0;">Status: Payment Successful</span>
            @elseif($status === 'confirmed')
                <span class="status-badge" style="background: #e0f2fe; color: #075985; border-color: #bae6fd;">Status: Confirmed</span>
            @else
                <span class="status-badge">Status: Pending Confirmation</span>
            @endif
        </div>
        
        <div class="order-card">
            <h2 class="order-card-title">Order Details</h2>
            
            @if(isset($orders) && count($orders))
                <div class="order-list">
                    @foreach($orders as $o)
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-number">{{ $o->order_number }}</div>
                                <div class="order-product">{{ optional($o->product)->name ?? $o->jersey_type ?? 'Product' }}</div>
                            </div>
                            <div class="order-status">
                                <span style="font-size: 0.8rem; padding: 0.25rem 0.5rem; border-radius: 4px; background: #f3f4f6; color: #4b5563;">
                                    {{ ucfirst($o->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="info-box">
                    <div class="info-box-title">
                        <i data-feather="info"></i>
                        Important Information
                    </div>
                    <p class="info-box-text">
                        Please save your order number(s) above for tracking. 
                        @if($isStripe)
                            Payment has been securely processed via Stripe. We will process your order shortly.
                        @else
                            For COD orders, payment will be collected upon delivery. You will receive a confirmation call or message from us soon.
                        @endif
                    </p>
                </div>
            @else
                <div class="empty-orders">
                    <p>No orders were created.</p>
                </div>
            @endif
        </div>
        
        <div class="order-actions">
            <a href="{{ route('order.track') }}" class="btn btn-primary">
                <i data-feather="search"></i>
                Track Your Order
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i data-feather="shopping-bag"></i>
                Continue Shopping
            </a>
        </div>
    </section>
@endsection
