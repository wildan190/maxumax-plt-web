@extends('layouts.auth')

@section('title', 'Reset Kata Sandi - ' . config('app.name', 'Laravel'))

@section('auth-title', 'Atur Ulang Kata Sandi')
@section('auth-subtitle', 'Buat kata sandi baru yang kuat')

@section('content')
<div class="auth-form-header">
    <h1>Reset Kata Sandi</h1>
    <p>Silakan buat kata sandi baru Anda</p>
</div>

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email -->
    <div class="auth-form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required>
        @error('email')
            <div class="auth-form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="auth-form-group">
        <label for="password">Kata Sandi Baru</label>
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

    <!-- Submit Button -->
    <button type="submit" class="auth-form-submit">Reset Kata Sandi</button>

    <!-- Back to Login -->
    <div class="auth-form-link" style="justify-content: center; gap: 0.5rem;">
        <a href="{{ route('login') }}">← Kembali ke Login</a>
    </div>
</form>
@endsection
