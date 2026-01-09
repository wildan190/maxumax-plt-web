@extends('layouts.public')

@section('title', 'Cart')

@push('styles')
    <style>
        .cart-container { max-width: 1000px; margin: 0 auto; padding: 2rem 1rem; }
        .cart-title { font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0 0 0.75rem; }
        .cart-sub { color: #64748b; margin: 0 0 1rem; }
        .cart-grid { display: grid; grid-template-columns: 1fr 320px; gap: 1rem; }
        .cart-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; }
        .cart-item { display: grid; grid-template-columns: 72px 1fr auto; gap: 0.75rem; align-items: center; }
        .cart-thumb { width: 72px; height: 72px; object-fit: cover; border-radius: 0.5rem; border: 1px solid #e2e8f0; }
        .cart-meta { color: #64748b; font-size: 0.875rem; }
        .cart-price { font-weight: 700; color: #111827; margin-top: 0.25rem; }
        .cart-actions { display: flex; gap: 0.5rem; }
        .cart-summary-row { display: flex; justify-content: space-between; align-items: center; padding-top: 0.75rem; border-top: 1px solid #e2e8f0; }
        .cart-summary-title { font-weight: 800; color: #0f172a; }
        .cart-summary-total { font-weight: 800; color: #111827; }
        .cart-form { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .cart-input { padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.375rem; }
        @media (max-width: 900px) { .cart-grid { grid-template-columns: 1fr; } }
    </style>
@endpush

@section('content')
    <section class="cart-container">
        <h1 class="cart-title">Your Cart</h1>
        <p class="cart-sub">Review pesanan Anda sebelum checkout Cash on Delivery.</p>
        @if(session('success'))
            <div class="cart-card" style="margin-top:0.5rem; color:#065f46; background:#ecfdf5; border-color:#a7f3d0;">{{ session('success') }}</div>
        @endif
        @if(!count($items))
            <div class="cart-card" style="margin-top:0.75rem; color:#64748b;">Cart kosong</div>
        @else
            <div class="cart-grid" style="margin-top:0.75rem;">
                <div class="cart-card">
                    @foreach($items as $it)
                        <div class="cart-item">
                            <div>
                                @if($it['image'])
                                    <img src="{{ asset('storage/'.$it['image']) }}" alt="{{ $it['name'] }}" class="cart-thumb">
                                @else
                                    <span class="text-slate-400">👕</span>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:700; color:#0f172a;">{{ $it['name'] }}</div>
                                <div class="cart-meta">
                                    {{ $it['jersey_type'] ?? '-' }} • Size {{ $it['size'] ?? '-' }} • Qty {{ $it['quantity'] }}
                                    @if(!empty($it['long_sleeve'])) • Long Sleeve @endif
                                </div>
                                <div class="cart-price">
                                    <span style="font-size:0.875rem; color:#64748b;">{{ $it['currency'] }}</span> {{ number_format($it['line_total'], 2) }}
                                </div>
                            </div>
                            <div class="cart-actions">
                                <form method="POST" action="{{ route('cart.update') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $it['product_id'] }}">
                                    <input type="hidden" name="size" value="{{ $it['size'] }}">
                                    <input type="hidden" name="long_sleeve" value="{{ $it['long_sleeve'] ? 1 : 0 }}">
                                    <input type="number" name="quantity" value="{{ $it['quantity'] }}" min="1" class="cart-input" style="width:80px;">
                                    <button type="submit" class="btn" style="background:#111827;">Update</button>
                                </form>
                                <form method="POST" action="{{ route('cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $it['product_id'] }}">
                                    <button type="submit" class="btn" style="background:#ef4444;">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="cart-card">
                    <div class="cart-summary-row">
                        <div class="cart-summary-title">Total</div>
                        <div class="cart-summary-total"><span style="font-size:0.875rem; color:#64748b;">{{ $currency }}</span> {{ number_format($total, 2) }}</div>
                    </div>
                    <div style="margin-top:0.75rem;">
                    <form method="POST" action="{{ route('checkout.cod') }}" class="cart-form">
                        @csrf
                        <input type="hidden" name="currency" value="{{ $currency }}">
                        <input type="text" name="name" placeholder="Nama lengkap" required class="cart-input">
                        <input type="email" name="email" placeholder="Email (opsional)" class="cart-input">
                        <input type="text" name="phone" placeholder="Phone/WhatsApp" required class="cart-input">
                        <input type="text" name="address" placeholder="Alamat lengkap untuk COD" required class="cart-input">
                        <textarea name="notes" placeholder="Catatan (opsional)" class="cart-input" style="grid-column: span 2;"></textarea>
                        <div style="grid-column: span 2; display:flex; justify-content:flex-end;">
                            <button type="submit" class="btn"><i data-feather="truck"></i> Checkout COD</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
