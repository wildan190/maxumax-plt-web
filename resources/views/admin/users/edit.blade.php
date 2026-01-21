@extends('layouts.app')

@section('page-title', 'Edit User Account')

@section('content')
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.users.index') }}"
            style="color: #6366f1; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; width: fit-content; transition: opacity 0.2s;"
            onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
            <i data-feather="arrow-left" style="width: 18px; height: 18px;"></i> Back to User List
        </a>
    </div>

    <div
        style="background: white; border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); padding: 2.5rem; max-width: 700px; margin: 0 auto;">
        <div
            style="margin-bottom: 2rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">Edit User Profile</h2>
                <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">Update details for
                    <strong>{{ $user->name }}</strong>.
                </p>
            </div>
            <div
                style="width: 48px; height: 48px; border-radius: 50%; background: #f3f4f6; color: #4b5563; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.25rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Name -->
                <div style="margin-bottom: 1.5rem; grid-column: span 2;">
                    <label for="name"
                        style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Full
                        Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; transition: border-color 0.2s; font-family: inherit;" />
                    @error('name')
                        <p
                            style="color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; display: flex; align-items: center; gap: 0.25rem;">
                            <i data-feather="alert-circle" style="width: 12px; height: 12px;"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="email"
                        style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Email
                        Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; transition: border-color 0.2s; font-family: inherit;" />
                    @error('email')
                        <p
                            style="color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; display: flex; align-items: center; gap: 0.25rem;">
                            <i data-feather="alert-circle" style="width: 12px; height: 12px;"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Role -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="role"
                        style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Account
                        Role</label>
                    <select name="role" id="role" required
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; background: white; cursor: pointer; font-family: inherit;">
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff (Limited
                            Admin)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Full
                            Access)</option>
                    </select>
                    @error('role')
                        <p
                            style="color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; display: flex; align-items: center; gap: 0.25rem;">
                            <i data-feather="alert-circle" style="width: 12px; height: 12px;"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div
                style="margin-top: 2rem; padding: 1.5rem; background: #f9fafb; border-radius: 0.5rem; border: 1px solid #f3f4f6;">
                <div style="display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <i data-feather="shield"
                        style="width: 20px; height: 20px; color: #6b7280; flex-shrink: 0; margin-top: 2px;"></i>
                    <div>
                        <h3 style="font-size: 0.95rem; font-weight: 700; color: #374151; margin: 0;">Change Password</h3>
                        <p style="color: #6b7280; font-size: 0.85rem; margin-top: 0.25rem;">Leave these fields blank to keep
                            the current password.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <!-- Password -->
                    <div>
                        <label for="password"
                            style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">New
                            Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; background: white; font-family: inherit;" />
                        @error('password')
                            <p
                                style="color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; display: flex; align-items: center; gap: 0.25rem;">
                                <i data-feather="alert-circle" style="width: 12px; height: 12px;"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation"
                            style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Confirm
                            New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="••••••••"
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; background: white; font-family: inherit;" />
                    </div>
                </div>
            </div>

            <div
                style="display: flex; justify-content: flex-end; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid #f3f4f6; gap: 1rem;">
                <a href="{{ route('admin.users.index') }}"
                    style="padding: 0.75rem 2rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; color: #374151; font-weight: 600; text-decoration: none; font-size: 0.95rem; transition: background 0.2s;"
                    onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                    Cancel
                </a>
                <button type="submit"
                    style="background: #000; color: white; padding: 0.75rem 2.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: transform 0.2s, background 0.2s;"
                    onmouseover="this.style.background='#1f2937'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#000'; this.style.transform='translateY(0)'">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
@endsection