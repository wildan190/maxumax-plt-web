@extends('layouts.app')

@section('page-title', 'Add New User')

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
        <div style="margin-bottom: 2rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">User Account Details</h2>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem;">Fill in the information below to create a new
                account.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Name -->
                <div style="margin-bottom: 1.5rem; grid-column: span 2;">
                    <label for="name"
                        style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Full
                        Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        placeholder="John Doe"
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
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        placeholder="john@example.com"
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
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff (Limited Admin)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Full Access)
                        </option>
                    </select>
                    @error('role')
                        <p
                            style="color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; display: flex; align-items: center; gap: 0.25rem;">
                            <i data-feather="alert-circle" style="width: 12px; height: 12px;"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="password"
                        style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; transition: border-color 0.2s; font-family: inherit;" />
                    @error('password')
                        <p
                            style="color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; display: flex; align-items: center; gap: 0.25rem;">
                            <i data-feather="alert-circle" style="width: 12px; height: 12px;"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="password_confirmation"
                        style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Confirm
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="••••••••"
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; transition: border-color 0.2s; font-family: inherit;" />
                </div>
            </div>

            <div
                style="display: flex; justify-content: flex-end; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid #f3f4f6; gap: 1rem;">
                <a href="{{ route('admin.users.index') }}"
                    style="padding: 0.75rem 2rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; color: #374151; font-weight: 600; text-decoration: none; font-size: 0.95rem; transition: background 0.2s;"
                    onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    Cancel
                </a>
                <button type="submit"
                    style="background: #000; color: white; padding: 0.75rem 2.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: transform 0.2s, background 0.2s;"
                    onmouseover="this.style.background='#1f2937'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#000'; this.style.transform='translateY(0)'">
                    Create User
                </button>
            </div>
        </form>
    </div>
@endsection