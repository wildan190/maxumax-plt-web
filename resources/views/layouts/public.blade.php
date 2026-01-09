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
                    <a href="{{ route('preorder.landing') }}" class="public-nav-link">Pre-order</a>
                    <a href="{{ route('products.index') }}" class="public-nav-link">Products</a>
                    <a href="{{ route('order.track') }}" class="public-nav-link">Track Order</a>
                    @php $cartCount = is_array(session('cart')) ? count(session('cart')) : 0; @endphp
                    <a href="{{ route('cart.show') }}" class="public-nav-link" aria-label="Cart" title="Cart" style="position: relative; display: inline-flex; align-items: center; gap: 6px; padding: 4px 6px;">
                        <i data-feather="shopping-cart" style="width: 20px; height: 20px; color: #0f172a;"></i>
                        @if($cartCount)
                            <span style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: #ffffff; border-radius: 6px; height: 18px; min-width: 18px; padding: 0 4px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
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
