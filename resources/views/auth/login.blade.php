@extends('layouts.auth')

@section('title', 'Sign In - ' . config('app.name', 'Maxumax'))

@section('auth-title', 'Welcome Back')
@section('auth-subtitle', 'Enter your credentials to access the admin dashboard.')

@section('content')
<div class="space-y-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ config('app.name', 'MAXUMAX') }}</h1>
        <p class="text-slate-500 font-medium mt-1">Admin Portal</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div class="space-y-1.5">
            <label for="email" class="text-xs font-bold text-slate-700 uppercase tracking-widest ml-1">
                Email Address
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-feather="mail" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="name@company.com" 
                    class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-slate-400">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs font-bold text-rose-600 ml-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-1.5" x-data="{ show: false }">
            <div class="flex justify-between items-center px-1">
                <label for="password" class="text-xs font-bold text-slate-700 uppercase tracking-widest">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 transition-colors uppercase tracking-wider">
                        Forgot?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-feather="lock" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input :type="show ? 'text' : 'password'" id="password" name="password" required 
                    placeholder="••••••••" 
                    class="block w-full pl-10 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-slate-400">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i :data-feather="show ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs font-bold text-rose-600 ml-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center px-1">
            <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative flex items-center justify-center">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                        class="peer h-5 w-5 rounded-lg border-slate-200 bg-slate-50 text-indigo-600 focus:ring-indigo-500/20 transition-all cursor-pointer appearance-none border checked:bg-indigo-600 checked:border-indigo-600">
                    <i data-feather="check" class="absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></i>
                </div>
                <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">
                    Keep me signed in
                </span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-indigo-600 text-white text-sm font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20 active:scale-[0.98]">
            Sign Into Portal
        </button>
    </form>
</div>
@endsection
