@extends('layouts.auth')

@section('title', 'Konfirmasi Kata Sandi - ' . config('app.name', 'Laravel'))

@section('auth-title', 'Konfirmasi Identitas Anda')
@section('auth-subtitle', 'Ini diperlukan untuk alasan keamanan')

@section('content')
<div class="auth-form-header">
    <h1>Konfirmasi Kata Sandi</h1>
    <p>Masukkan kata sandi Anda untuk melanjutkan</p>
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <!-- Password -->
    <div class="auth-form-group">
        <label for="password">Kata Sandi</label>
        <input type="password" id="password" name="password" required autofocus>
        @error('password')
            <div class="auth-form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="auth-form-submit">Konfirmasi</button>

    <!-- Back to Login -->
    <div class="auth-form-link" style="justify-content: center; gap: 0.5rem;">
        <a href="{{ route('login') }}">← Kembali ke Login</a>
    </div>
</form>
@endsection
