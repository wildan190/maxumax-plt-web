@extends('layouts.public')

@section('title', 'Track Order')

@push('styles')
    <style>
        :root {
            --dark: #0f172a;
            --gray-500: #64748b;
        }
        .track-container { max-width: 800px; margin: 0 auto; padding: 3rem 1rem; }
        .track-title { font-size: 2rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem; }
        .track-sub { color: var(--gray-500); margin-bottom: 1.5rem; }
        .track-form { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .track-form input { flex: 1; border: 1px solid #cbd5e1; border-radius: 0.5rem; padding: 0.5rem 0.75rem; }
        .track-form button { background: #000; color: #fff; border: none; border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 600; }
        .alert { display:inline-block; border-radius: 0.75rem; padding: 0.5rem 0.75rem; font-weight: 600; margin-top: 1rem; }
        .alert-error { background:#fff7ed; color:#9a3412; border:1px solid #fed7aa; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1rem; margin-top: 1rem; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .label { font-size: 0.8rem; color: var(--gray-500); }
        .value { font-weight: 600; color: var(--dark); }
        .status { font-size: 0.875rem; padding: 0.25rem 0.5rem; border-radius: 9999px; border: 1px solid; }
    </style>
@endpush

@section('content')
    <section class="track-container">
        <h1 class="track-title">Track Order</h1>
        <p class="track-sub">Masukkan order number untuk cek status tanpa login.</p>

        <form method="GET" action="{{ route('order.track') }}" class="track-form">
            <input type="text" name="order" value="{{ $orderInput }}" placeholder="Contoh: MM-ABCDEFGH" required />
            <button type="submit">Track</button>
        </form>

        @if($error)
            <div class="alert alert-error">{{ $error }}</div>
        @endif

        @if($preorder)
            <div class="card">
                <div class="flex items-center justify-between" style="display:flex; align-items:center; justify-content:space-between;">
                    <div class="value">{{ $preorder->order_number }}</div>
                    <div class="status" style="
                        @if($preorder->status === 'paid') background:#ecfdf5; color:#065f46; border-color:#a7f3d0;
                        @elseif($preorder->status === 'confirmed') background:#eff6ff; color:#1e40af; border-color:#bfdbfe;
                        @else background:#fefce8; color:#854d0e; border-color:#fde68a; @endif
                    ">
                        {{ ucfirst($preorder->status) }}
                    </div>
                </div>
                <div class="row" style="margin-top:0.75rem;">
                    <div>
                        <div class="label">Produk</div>
                        <div class="value">{{ optional($preorder->product)->name ?? ($preorder->jersey_type ?? '-') }}</div>
                    </div>
                    <div>
                        <div class="label">Jumlah</div>
                        <div class="value">{{ $preorder->quantity }} pcs</div>
                    </div>
                    <div>
                        <div class="label">Total</div>
                        <div class="value">{{ $preorder->currency ?? 'MYR' }} {{ number_format($preorder->total_amount, 2) }}</div>
                    </div>
                    <div>
                        <div class="label">Kontak</div>
                        <div class="value">{{ $preorder->phone }}{{ $preorder->email ? ' / ' . $preorder->email : '' }}</div>
                    </div>
                </div>
                <div style="margin-top:0.75rem;">
                    <a href="{{ route('preorder.thankyou', ['id' => $preorder->id]) }}" class="inline-block bg-black text-white px-4 py-2 rounded-md font-semibold text-sm">Lihat detail</a>
                </div>
            </div>
        @endif
    </section>
@endsection
