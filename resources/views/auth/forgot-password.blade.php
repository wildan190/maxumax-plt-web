@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi - ' . config('app.name', 'Laravel'))

@section('auth-title', 'Lupa Kata Sandi?')
@section('auth-subtitle', 'Tidak masalah, kami akan membantu Anda')

@section('content')
<div class="auth-form-header">
    <h1>Lupa Kata Sandi</h1>
    <p>Masukkan email Anda untuk menerima tautan reset</p>
</div>

@if (session('status'))
    <div style="padding: 0.75rem; margin-bottom: 1.5rem; background-color: #d1fae5; border: 1px solid #a7f3d0; border-radius: 0.5rem; color: #065f46; font-size: 0.875rem;">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- Email -->
    <div class="auth-form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <div class="auth-form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="auth-form-submit">Kirim Tautan Reset</button>

    <!-- Back to Login -->
    <div class="auth-form-link" style="justify-content: center; gap: 0.5rem;">
        <a href="{{ route('login') }}">← Kembali ke Login</a>
    </div>
</form>
@endsection
