@extends('layouts.auth')

@section('title', 'Masuk - ' . config('app.name', 'Laravel'))

@section('auth-title', 'Selamat Datang Kembali')
@section('auth-subtitle', 'Masuk ke akun Anda untuk melanjutkan')

@section('content')
<div class="auth-form-header">
    <h1>Masuk</h1>
    <p>Silakan masukkan kredensial Anda</p>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email -->
    <div class="auth-form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
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

    <!-- Remember Me -->
    <div class="auth-form-group">
        <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 400; cursor: pointer;">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <span>Ingat saya</span>
        </label>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="auth-form-submit">Masuk</button>

    <!-- Links -->
    <div class="auth-form-link">
        <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
        <a href="{{ route('register') }}">Daftar akun baru</a>
    </div>
</form>
@endsection
