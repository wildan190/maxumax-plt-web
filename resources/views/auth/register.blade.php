@extends('layouts.auth')

@section('title', 'Daftar - ' . config('app.name', 'Laravel'))

@section('auth-title', 'Buat Akun Baru')
@section('auth-subtitle', 'Bergabunglah dengan ribuan pengguna kami')

@section('content')
<div class="auth-form-header">
    <h1>Daftar</h1>
    <p>Isi form di bawah untuk membuat akun</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div class="auth-form-group">
        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        @error('name')
            <div class="auth-form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="auth-form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="auth-form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="auth-form-group">
        <label for="password">Kata Sandi</label>
        <input type="password" id="password" name="password" required>
        @error('password')
            <div class="auth-form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password Confirmation -->
    <div class="auth-form-group">
        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
        @error('password_confirmation')
            <div class="auth-form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Terms Checkbox -->
    <div class="auth-form-group">
        <label style="display: flex; align-items: flex-start; gap: 0.5rem; font-weight: 400; cursor: pointer;">
            <input type="checkbox" name="terms" id="terms" required style="margin-top: 0.25rem;">
            <span>Saya setuju dengan <a href="#" style="color: var(--primary); text-decoration: none;">Syarat & Ketentuan</a> dan <a href="#" style="color: var(--primary); text-decoration: none;">Kebijakan Privasi</a></span>
        </label>
        @error('terms')
            <div class="auth-form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="auth-form-submit">Buat Akun</button>

    <!-- Link to Login -->
    <div class="auth-form-link" style="justify-content: center; gap: 0.5rem;">
        <span>Sudah punya akun?</span>
        <a href="{{ route('login') }}">Masuk di sini</a>
    </div>
</form>
@endsection
