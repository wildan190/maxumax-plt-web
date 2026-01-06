<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Feather Icons -->
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

        <!-- Styles + Scripts (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="auth-layout">
            <!-- Left Side -->
            <div class="auth-side">
                <div class="auth-side-content">
                    <div class="auth-side-logo">
                        {{ config('app.name', 'MaxuMax') }}
                    </div>
                    <h2 class="auth-side-title">@yield('auth-title', 'Selamat Datang')</h2>
                    <p class="auth-side-desc">@yield('auth-subtitle', 'Kelola bisnis Anda dengan mudah dan efisien')</p>
                    
                    <div class="auth-side-features">
                        <div class="auth-side-feature">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <div class="auth-side-feature-text">
                                <h4>Keamanan Terjamin</h4>
                                <p>Enkripsi end-to-end untuk melindungi data Anda</p>
                            </div>
                        </div>
                        <div class="auth-side-feature">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <div class="auth-side-feature-text">
                                <h4>Akses Mudah</h4>
                                <p>Diakses dari mana saja kapan saja</p>
                            </div>
                        </div>
                        <div class="auth-side-feature">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <div class="auth-side-feature-text">
                                <h4>Dukungan Penuh</h4>
                                <p>Tim support siap membantu 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="auth-form-container">
                <div class="auth-form-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Initialize Feather Icons -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                feather.replace();
            });
        </script>

        <!-- Vite handled scripts included above -->
    </body>
</html>
