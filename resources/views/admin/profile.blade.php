@extends('layouts.app')

@section('page-title', 'Account Settings')

@section('content')
    <div style="max-width: 1000px; margin: 0 auto;">
        <!-- Header Section -->
        <div style="margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1
                    style="font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0; letter-spacing: -0.025em; text-transform: uppercase;">
                    Manage Profile</h1>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">Update your personal information and
                    security preferences</p>
            </div>
            <div
                style="background: #f3f4f6; padding: 0.5rem 1rem; border-radius: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 0.5rem; height: 0.5rem; background: #10b981; border-radius: 50%;"></div>
                <span
                    style="font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em;">Active
                    Administrator</span>
            </div>
        </div>

        @if (session('success'))
            <div
                style="margin-bottom: 2rem; padding: 1rem 1.25rem; background: #ecfdf5; border-left: 4px solid #10b981; border-radius: 0.75rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <i data-feather="check-circle" style="width: 20px; height: 20px; color: #059669;"></i>
                <p style="margin: 0; font-size: 0.875rem; font-weight: 600; color: #065f46;">{{ session('success') }}</p>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 320px 1fr; gap: 2.5rem; align-items: start;">

            <!-- Sidebar Column -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Profile Summary Card -->
                <div
                    style="background: white; border-radius: 1.5rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; text-align: center; padding: 2.5rem 1.5rem;">
                    <div
                        style="width: 100px; height: 100px; margin: 0 auto 1.5rem; background: #111827; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 800; border: 4px solid #f3f4f6; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: #111827; margin: 0 0 0.25rem;">
                        {{ auth()->user()->name }}</h2>
                    <p style="font-size: 0.875rem; color: #6b7280; font-weight: 500; margin-bottom: 1.5rem;">
                        {{ auth()->user()->email }}</p>

                    <div
                        style="padding-top: 1.5rem; border-top: 1px solid #f3f4f6; display: flex; flex-direction: column; gap: 0.75rem; text-align: left;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i data-feather="shield" style="width: 16px; height: 16px; color: #6b7280;"></i>
                            <span
                                style="font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em;">Access
                                Role: Superadmin</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i data-feather="calendar" style="width: 16px; height: 16px; color: #6b7280;"></i>
                            <span
                                style="font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em;">Joined:
                                {{ auth()->user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div style="padding: 1.5rem; background: #f9fafb; border-radius: 1.25rem; border: 1px dashed #e5e7eb;">
                    <h4
                        style="font-size: 0.75rem; font-weight: 800; color: #111827; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-feather="info" style="width: 14px; height: 14px;"></i> Security Note
                    </h4>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0; line-height: 1.5; font-weight: 500;">Always use
                        a strong password and ensure you log out of shared devices to keep the admin panel secure.</p>
                </div>
            </div>

            <!-- Main Content Column -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">

                <!-- Personal Information Card -->
                <div
                    style="background: white; border-radius: 1.25rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                    <div
                        style="padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 0.75rem;">
                        <div
                            style="width: 2.5rem; height: 2.5rem; background: #e0e7ff; color: #4f46e5; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            <i data-feather="user" style="width: 20px; height: 20px;"></i>
                        </div>
                        <h3
                            style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                            Personal Information</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div>
                                    <label for="name"
                                        style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Display
                                        Name</label>
                                    <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required
                                        style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; color: #111827; font-weight: 600; outline: none; transition: all 0.2s;"
                                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)';"
                                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    @error('name')
                                        <div
                                            style="color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-top: 0.375rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email"
                                        style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Email
                                        Address</label>
                                    <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required
                                        style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; color: #111827; font-weight: 600; outline: none;"
                                        onfocus="this.style.borderColor='#3b82f6';"
                                        onblur="this.style.borderColor='#e5e7eb';">
                                    @error('email')
                                        <div
                                            style="color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-top: 0.375rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-end; padding-top: 0.5rem;">
                                <button type="submit"
                                    style="padding: 0.75rem 2rem; background: #111827; color: white; border: none; border-radius: 1rem; font-size: 0.875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Card -->
                <div
                    style="background: white; border-radius: 1.25rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                    <div
                        style="padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 0.75rem;">
                        <div
                            style="width: 2.5rem; height: 2.5rem; background: #fff7ed; color: #f59e0b; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            <i data-feather="lock" style="width: 20px; height: 20px;"></i>
                        </div>
                        <h3
                            style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                            Security & Password</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div style="margin-bottom: 1.5rem;">
                                <label for="current_password"
                                    style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Current
                                    Password</label>
                                <input type="password" id="current_password" name="current_password" required
                                    style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; color: #111827; font-weight: 600;">
                                @error('current_password')
                                    <div style="color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-top: 0.375rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div>
                                    <label for="password"
                                        style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">New
                                        Password</label>
                                    <input type="password" id="password" name="password" required
                                        style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; color: #111827; font-weight: 600;">
                                    @error('password')
                                        <div
                                            style="color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-top: 0.375rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation"
                                        style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Confirm
                                        New Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required
                                        style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; color: #111827; font-weight: 600;">
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-end; padding-top: 0.5rem;">
                                <button type="submit"
                                    style="padding: 0.75rem 2rem; background: #f9fafb; color: #111827; border: 1px solid #e5e7eb; border-radius: 1rem; font-size: 0.875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: all 0.2s;">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div
                    style="background: #fef2f2; border: 1px solid #fee2e2; border-radius: 1.25rem; overflow: hidden; margin-top: 1rem;">
                    <div
                        style="padding: 1.5rem; border-bottom: 1px solid #fee2e2; display: flex; align-items: center; gap: 0.75rem;">
                        <div
                            style="width: 2.5rem; height: 2.5rem; background: #fee2e2; color: #dc2626; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                            <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
                        </div>
                        <h3
                            style="font-size: 1rem; font-weight: 700; color: #991b1b; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                            Danger Area</h3>
                    </div>
                    <div style="padding: 2rem; display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h4 style="font-size: 0.875rem; font-weight: 700; color: #991b1b; margin: 0 0 0.25rem;">
                                Permanent Account Deletion</h4>
                            <p style="font-size: 0.8125rem; color: #b91c1c; margin: 0; font-weight: 500;">Please proceed
                                with caution. This action cannot be reversed.</p>
                        </div>
                        <form method="POST" action="{{ route('profile.destroy') }}"
                            onsubmit="return confirm('Are you absolutely sure? This will delete your administrator access permanently.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="padding: 0.75rem 1.5rem; background: #dc2626; color: white; border: none; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: all 0.2s;">
                                Delete Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection