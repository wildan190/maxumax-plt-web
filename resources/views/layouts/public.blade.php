<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'MaxuMax'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Feather Icons -->
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="public-body">
        <!-- Navbar -->
        <nav class="public-navbar">
            <div class="public-container">
                <a href="/" class="public-brand">
                    {{ config('app.name', 'MaxuMax') }}
                </a>
                <div class="public-nav-links">
                    <a href="/" class="public-nav-link">Home</a>
                    <a href="#products" class="public-nav-link">Products</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="w-full">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="public-footer">
            <p>&copy; 2026 {{ config('app.name', 'MaxuMax') }}. All rights reserved.</p>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        </script>
    </body>
</html>
