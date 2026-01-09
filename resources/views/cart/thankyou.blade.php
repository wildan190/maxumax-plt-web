@extends('layouts.public')

@section('title', 'Thank You')

@section('content')
    <section class="prod-container">
        <div class="card">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <span style="font-size:2rem;">✅</span>
                <div style="font-weight:800; color:#0f172a; font-size:1.25rem;">Pesanan Anda sudah kami terima (COD)</div>
            </div>
            <p class="prod-desc" style="margin-top:0.5rem;">Silakan simpan nomor order di bawah untuk tracking.</p>
            <div style="margin-top:0.75rem; display:grid; grid-template-columns: 1fr; gap:0.5rem;">
                @forelse($orders as $o)
                    <div style="display:flex; align-items:center; justify-content:space-between; border:1px solid #e2e8f0; border-radius:0.5rem; padding:0.75rem;">
                        <div style="font-weight:700; color:#111827;">{{ $o->order_number }}</div>
                        <div style="color:#64748b;">{{ optional($o->product)->name ?? $o->jersey_type }}</div>
                    </div>
                @empty
                    <div class="text-slate-500">Tidak ada order yang dibuat.</div>
                @endforelse
            </div>
            <div style="margin-top:1rem;">
                <a href="{{ route('order.track') }}" class="btn"><i data-feather="search"></i> Track Order</a>
                <a href="{{ route('preorder.landing') }}" class="btn" style="background:#111827; margin-left:0.5rem;">Kembali belanja</a>
            </div>
        </div>
    </section>
@endsection
