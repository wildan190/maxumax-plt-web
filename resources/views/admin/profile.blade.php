@extends('layouts.app')

@section('page-title', 'Account Settings')
@section('page-subtitle', 'Update your personal information and security preferences')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Sidebar Column -->
    <div class="space-y-6">
        <!-- Profile Summary Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden text-center p-8">
            <div class="w-24 h-24 mx-auto mb-6 bg-slate-900 text-white rounded-full flex items-center justify-center text-3xl font-black border-4 border-slate-50 shadow-xl">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-1">
                {{ auth()->user()->name }}
            </h2>
            <p class="text-sm text-slate-500 font-medium mb-6">
                {{ auth()->user()->email }}
            </p>

            <div class="pt-6 border-t border-slate-50 flex flex-col gap-3 text-left">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i data-feather="shield" class="w-4 h-4"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Access Role</span>
                        <span class="text-xs font-bold text-slate-700">Administrator</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i data-feather="calendar" class="w-4 h-4"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Member Since</span>
                        <span class="text-xs font-bold text-slate-700">{{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Note -->
        <div class="p-6 bg-indigo-600 rounded-3xl text-white shadow-xl shadow-indigo-600/20">
            <div class="flex items-center gap-3 mb-3">
                <i data-feather="lock" class="w-5 h-5 text-indigo-200"></i>
                <h4 class="text-xs font-black uppercase tracking-widest">Security Tip</h4>
            </div>
            <p class="text-sm font-medium leading-relaxed opacity-90">Always use a strong password and ensure you log out of shared devices to keep the admin panel secure.</p>
        </div>
    </div>

    <!-- Main Content Column -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Personal Information Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400">
                    <i data-feather="user" class="w-4 h-4"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Personal Information</h3>
            </div>
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label for="name" class="text-xs font-bold text-slate-700 uppercase tracking-widest ml-1">Display Name</label>
                            <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required
                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            @error('name')
                                <p class="mt-1 text-xs font-bold text-rose-600 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="email" class="text-xs font-bold text-slate-700 uppercase tracking-widest ml-1">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required
                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            @error('email')
                                <p class="mt-1 text-xs font-bold text-rose-600 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white text-sm font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400">
                    <i data-feather="shield" class="w-4 h-4"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Security & Password</h3>
            </div>
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1.5">
                        <label for="current_password" class="text-xs font-bold text-slate-700 uppercase tracking-widest ml-1">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        @error('current_password')
                            <p class="mt-1 text-xs font-bold text-rose-600 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label for="password" class="text-xs font-bold text-slate-700 uppercase tracking-widest ml-1">New Password</label>
                            <input type="password" id="password" name="password" required
                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            @error('password')
                                <p class="mt-1 text-xs font-bold text-rose-600 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="text-xs font-bold text-slate-700 uppercase tracking-widest ml-1">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-8 py-3 bg-white border border-slate-200 text-slate-900 text-sm font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-rose-50 rounded-3xl border border-rose-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-rose-100 bg-rose-100/20 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white border border-rose-100 flex items-center justify-center text-rose-500">
                    <i data-feather="alert-triangle" class="w-4 h-4"></i>
                </div>
                <h3 class="text-sm font-bold text-rose-900 uppercase tracking-wider">Danger Zone</h3>
            </div>
            <div class="p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h4 class="text-sm font-black text-rose-900 uppercase tracking-tight mb-1">Delete Account Permanently</h4>
                    <p class="text-xs text-rose-600 font-medium">This action cannot be undone. All your data will be cleared.</p>
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}"
                    onsubmit="return confirm('Are you absolutely sure? This will delete your administrator access permanently.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-3 bg-rose-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/20">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection