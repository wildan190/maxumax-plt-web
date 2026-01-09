@extends('layouts.public')

@section('title', 'Shopping Cart - MaxuMax')

@push('styles')
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .cart-header {
            margin-bottom: 2rem;
        }
        
        .cart-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 0.5rem;
        }
        
        .cart-subtitle {
            color: #64748b;
            font-size: 1rem;
            margin: 0;
        }
        
        .cart-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 1.5rem;
        }
        
        .cart-items-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 1rem;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .cart-thumb {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
        }
        
        .cart-item-info {
            flex: 1;
        }
        
        .cart-item-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
        }
        
        .cart-item-meta {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }
        
        .cart-item-price {
            font-weight: 700;
            color: #111827;
            font-size: 1.125rem;
        }
        
        .cart-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-end;
        }
        
        .cart-qty-update {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .cart-qty-input {
            width: 70px;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            text-align: center;
            font-weight: 600;
        }
        
        .btn-update {
            padding: 0.5rem 1rem;
            background: #111827;
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn-update:hover {
            background: #1e293b;
        }
        
        .btn-remove {
            padding: 0.5rem 1rem;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn-remove:hover {
            background: #dc2626;
        }
        
        .cart-summary-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 2rem;
            height: fit-content;
        }
        
        .summary-title {
            font-weight: 800;
            color: #0f172a;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            border-top: 2px solid #e2e8f0;
            margin-top: 0.5rem;
            padding-top: 1rem;
        }
        
        .summary-label {
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .summary-value {
            font-weight: 700;
            color: #111827;
        }
        
        .summary-total {
            font-size: 1.5rem;
            color: #0f172a;
        }
        
        .payment-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .payment-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        
        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .payment-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .payment-option.active {
            border-color: #111827;
            background: #f8fafc;
        }
        
        .payment-option.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f1f5f9;
        }
        
        .payment-option input[type="radio"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .payment-option.disabled input[type="radio"] {
            cursor: not-allowed;
        }
        
        .payment-label {
            flex: 1;
            font-weight: 600;
            color: #0f172a;
        }
        
        .payment-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
        
        .badge-available {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-coming {
            background: #fef3c7;
            color: #92400e;
        }
        
        .checkout-form {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #0f172a;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.1);
        }
        
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            min-height: 80px;
            resize: vertical;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.1);
        }
        
        .btn-checkout {
            width: 100%;
            padding: 1rem;
            background: #111827;
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-checkout:hover:not(:disabled) {
            background: #1e293b;
            transform: translateY(-1px);
        }
        
        .btn-checkout:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
        }
        
        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-cart-text {
            color: #64748b;
            font-size: 1.125rem;
            margin-bottom: 1.5rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        @media (max-width: 968px) {
            .cart-grid {
                grid-template-columns: 1fr;
            }
            
            .cart-summary-card {
                position: static;
            }
            
            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 0.75rem;
            }
            
            .cart-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: flex-start;
                margin-top: 0.5rem;
            }
        }
        
        @media (max-width: 640px) {
            .cart-container {
                padding: 1rem;
            }
            
            .cart-title {
                font-size: 2rem;
            }
            
            .cart-item {
                grid-template-columns: 70px 1fr;
            }
            
            .cart-thumb {
                width: 70px;
                height: 70px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="cart-container">
        <div class="cart-header">
            <h1 class="cart-title">Shopping Cart</h1>
            <p class="cart-subtitle">Review your order before checkout</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(!count($items))
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <div class="empty-cart-text">Your cart is empty</div>
                <a href="{{ route('products.index') }}" class="btn-checkout" style="max-width: 300px; margin: 0 auto; text-decoration: none;">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="cart-grid">
                <!-- Cart Items -->
                <div class="cart-items-card">
                    <h2 style="font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; font-size: 1.25rem;">Items ({{ count($items) }})</h2>
                    @foreach($items as $it)
                        <div class="cart-item">
                            <div>
                                @if($it['image'])
                                    <img src="{{ asset('storage/'.$it['image']) }}" alt="{{ $it['name'] }}" class="cart-thumb">
                                @else
                                    <div style="width: 100px; height: 100px; background: #f1f5f9; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 2rem;">👕</div>
                                @endif
                            </div>
                            <div class="cart-item-info">
                                <div class="cart-item-name">{{ $it['name'] }}</div>
                                <div class="cart-item-meta">
                                    {{ $it['jersey_type'] ?? '-' }} • Size {{ $it['size'] ?? '-' }} • Qty {{ $it['quantity'] }}
                                    @if(!empty($it['long_sleeve'])) • Long Sleeve @endif
                                </div>
                                <div class="cart-item-price">
                                    <span style="font-size: 0.875rem; color: #64748b; font-weight: 500;">{{ $it['currency'] }}</span> {{ number_format($it['line_total'], 2) }}
                                </div>
                            </div>
                            <div class="cart-actions">
                                <form method="POST" action="{{ route('cart.update') }}" class="cart-qty-update">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $it['product_id'] }}">
                                    <input type="hidden" name="size" value="{{ $it['size'] }}">
                                    <input type="hidden" name="long_sleeve" value="{{ $it['long_sleeve'] ? 1 : 0 }}">
                                    <input type="number" name="quantity" value="{{ $it['quantity'] }}" min="1" class="cart-qty-input">
                                    <button type="submit" class="btn-update">Update</button>
                                </form>
                                <form method="POST" action="{{ route('cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $it['product_id'] }}">
                                    <button type="submit" class="btn-remove">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Order Summary -->
                <div class="cart-summary-card">
                    <h2 class="summary-title">Order Summary</h2>
                    
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value"><span style="font-size: 0.875rem; color: #64748b; font-weight: 500;">{{ $currency }}</span> {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-value" style="color: #10b981; font-weight: 600;">Free (COD)</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Total</span>
                        <span class="summary-value summary-total"><span style="font-size: 1rem; color: #64748b; font-weight: 500;">{{ $currency }}</span> {{ number_format($total, 2) }}</span>
                    </div>
                    
                    <!-- Payment Method Selection -->
                    <div class="payment-section">
                        <div class="payment-title">Payment Method</div>
                        <div class="payment-options">
                            <label class="payment-option active">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <span class="payment-label">Cash on Delivery (COD)</span>
                                <span class="payment-badge badge-available">Available</span>
                            </label>
                            <label class="payment-option disabled">
                                <input type="radio" name="payment_method" value="bank_transfer" disabled>
                                <span class="payment-label">Bank Transfer</span>
                                <span class="payment-badge badge-coming">Coming Soon</span>
                            </label>
                            <label class="payment-option disabled">
                                <input type="radio" name="payment_method" value="e_wallet" disabled>
                                <span class="payment-label">E-Wallet</span>
                                <span class="payment-badge badge-coming">Coming Soon</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Checkout Form -->
                    <form method="POST" action="{{ route('checkout.cod') }}" class="checkout-form">
                        @csrf
                        <input type="hidden" name="currency" value="{{ $currency }}">
                        <input type="hidden" name="payment_method" value="cod" id="paymentMethodInput">
                        
                        <div class="form-group">
                            <label class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" placeholder="Enter your full name" required class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email (Optional)</label>
                            <input type="email" name="email" placeholder="your.email@example.com" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Phone / WhatsApp <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="phone" placeholder="+673 1234 5678" required class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Delivery Address <span style="color: #ef4444;">*</span></label>
                            <textarea name="address" placeholder="Enter your complete delivery address for COD" required rows="3" class="form-textarea"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" placeholder="Any special instructions or notes..." rows="2" class="form-textarea"></textarea>
                        </div>
                        
                        <button type="submit" class="btn-checkout">
                            <i data-feather="truck"></i>
                            Complete Order (COD)
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </section>
    
    <script>
        // Update payment method input when radio button changes
        document.addEventListener('DOMContentLoaded', function() {
            const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
            const paymentMethodInput = document.getElementById('paymentMethodInput');
            
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked && !this.disabled) {
                        if (paymentMethodInput) {
                            paymentMethodInput.value = this.value;
                        }
                    }
                });
            });
            
            // Update active state for payment options
            paymentRadios.forEach(radio => {
                const label = radio.closest('.payment-option');
                if (radio.checked && !radio.disabled) {
                    label.classList.add('active');
                } else if (radio.disabled) {
                    label.classList.add('disabled');
                }
            });
        });
    </script>
@endsection
