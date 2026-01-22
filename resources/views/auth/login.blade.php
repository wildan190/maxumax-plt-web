@extends('layouts.auth')

@section('title', 'Sign In - ' . config('app.name', 'Laravel'))

@section('auth-title', 'Welcome Back')
@section('auth-subtitle', 'Sign in to your account to continue')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">
                Email Address
            </label>

            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="you@example.com" class="w-full rounded-lg border border-gray-300 bg-white
                           px-4 py-2 text-gray-800
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block mb-1 text-sm font-medium text-gray-700">
                Password
            </label>

            <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full rounded-lg border border-gray-300 bg-white
                           px-4 py-2 text-gray-800
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-gray-700">
                    Remember me
                </span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2 font-semibold text-white
                       hover:bg-indigo-700 transition">
            Sign In
        </button>

        <!-- Links -->
        <div class="flex justify-between text-sm">
            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">
                Forgot your password?
            </a>

            <!-- <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">
                    Create an account
                </a> -->
        </div>
    </form>
@endsection