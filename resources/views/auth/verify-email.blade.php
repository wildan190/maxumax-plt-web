@extends('layouts.auth')

@section('title', 'Verifikasi Email - ' . config('app.name', 'Laravel'))

@section('auth-title', 'Verifikasi Email Anda')
@section('auth-subtitle', 'Periksa email Anda untuk tautan verifikasi')

@section('content')
<div class="auth-form-header">
    <h1>Verifikasi Email</h1>
    <p>Kami telah mengirimkan tautan verifikasi ke email Anda</p>
</div>

@if (session('status') == 'verification-link-sent')
    <div style="padding: 0.75rem; margin-bottom: 1.5rem; background-color: #d1fae5; border: 1px solid #a7f3d0; border-radius: 0.5rem; color: #065f46; font-size: 0.875rem;">
        Tautan verifikasi baru telah dikirim ke email Anda
    </div>
@endif

<div style="padding: 1rem; margin-bottom: 1.5rem; background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 0.5rem; color: #92400e; font-size: 0.875rem; line-height: 1.5;">
    <strong>Sebelum melanjutkan:</strong> Periksa email Anda dan klik tautan verifikasi yang kami kirimkan untuk mengaktifkan akun Anda.
</div>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit" class="auth-form-submit">Kirim Ulang Tautan Verifikasi</button>
</form>

<!-- Logout Form -->
<form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
    @csrf
    <button type="submit" style="width: 100%; padding: 0.75rem; background: transparent; color: var(--primary); border: 1px solid var(--border-gray); border-radius: 0.5rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
        Keluar
    </button>
</form>
@endsection
