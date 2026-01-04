@extends('layouts.app')

@section('title', 'Profil')

@section('page-title', 'Profil Pengguna')
@section('page-subtitle', 'Kelola informasi dan pengaturan akun Anda')

@section('content')
<div style="display: grid; grid-template-columns: 1fr; gap: 2rem; max-width: 900px;">
    <!-- Success Message -->
    @if (session('success'))
        <div style="padding: 1rem; background-color: #d1fae5; border: 1px solid #a7f3d0; border-radius: 0.5rem; color: #065f46; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Card -->
    <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 2rem;">
        <div style="display: flex; align-items: flex-start; gap: 2rem; margin-bottom: 2rem;">
            <div style="width: 100px; height: 100px; border-radius: 50%; background: var(--dark-gray); display: flex; align-items: center; justify-content: center; color: var(--secondary); font-weight: 600; font-size: 2.5rem; flex-shrink: 0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin: 0 0 0.5rem;">{{ auth()->user()->name }}</h2>
                <p style="color: var(--text-light); margin: 0 0 1rem;">{{ auth()->user()->email }}</p>
                <div style="display: flex; gap: 0.5rem;">
                    <span style="display: inline-block; padding: 0.5rem 1rem; background: var(--light-gray); border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600; color: var(--text-dark); text-transform: uppercase; letter-spacing: 0.05em;">
                        Admin
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Profile Form -->
    <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0 0 1.5rem;">Informasi Profil</h3>
        
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-dark); margin-bottom: 0.5rem;">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required style="width: 100%; padding: 0.75rem; font-size: 1rem; border: 1px solid var(--border-gray); border-radius: 0.5rem; font-family: inherit;">
                @error('name')
                    <div style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-dark); margin-bottom: 0.5rem;">Email</label>
                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required style="width: 100%; padding: 0.75rem; font-size: 1rem; border: 1px solid var(--border-gray); border-radius: 0.5rem; font-family: inherit;">
                @error('email')
                    <div style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" style="padding: 0.75rem 1.5rem; background: var(--primary); color: var(--secondary); border: none; border-radius: 0.5rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- Update Password Form -->
    <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0 0 1.5rem;">Ubah Kata Sandi</h3>
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label for="current_password" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-dark); margin-bottom: 0.5rem;">Kata Sandi Saat Ini</label>
                <input type="password" id="current_password" name="current_password" required style="width: 100%; padding: 0.75rem; font-size: 1rem; border: 1px solid var(--border-gray); border-radius: 0.5rem; font-family: inherit;">
                @error('current_password')
                    <div style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-dark); margin-bottom: 0.5rem;">Kata Sandi Baru</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 0.75rem; font-size: 1rem; border: 1px solid var(--border-gray); border-radius: 0.5rem; font-family: inherit;">
                @error('password')
                    <div style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password_confirmation" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-dark); margin-bottom: 0.5rem;">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required style="width: 100%; padding: 0.75rem; font-size: 1rem; border: 1px solid var(--border-gray); border-radius: 0.5rem; font-family: inherit;">
            </div>

            <button type="submit" style="padding: 0.75rem 1.5rem; background: var(--primary); color: var(--secondary); border: none; border-radius: 0.5rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                Perbarui Kata Sandi
            </button>
        </form>
    </div>

    <!-- Danger Zone -->
    <div style="background: var(--secondary); border: 2px solid #dc2626; border-radius: 0.75rem; padding: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #dc2626; margin: 0 0 1.5rem;">Zona Berbahaya</h3>
        
        <p style="color: var(--text-light); margin-bottom: 1.5rem;">Menghapus akun Anda tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen.</p>

        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.');">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding: 0.75rem 1.5rem; background: #dc2626; color: var(--secondary); border: none; border-radius: 0.5rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                Hapus Akun
            </button>
        </form>
    </div>
</div>
@endsection

