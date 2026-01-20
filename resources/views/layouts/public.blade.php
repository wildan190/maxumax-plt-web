<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Maxumax'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="public-body">
    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="public-navbar" x-data="{ mobileMenuOpen: false }">
        <div class="public-container">
            <a href="/" class="public-brand">
                {{ config('app.name', 'Maxumax') }}
            </a>

            <!-- Desktop Nav -->
            <div class="public-nav-links hidden lg:flex items-center">
                <a href="/" class="public-nav-link">Home</a>
                <a href="{{ route('preorder.landing') }}" class="public-nav-link">Pre-order</a>
                <a href="{{ route('products.index') }}" class="public-nav-link">Products</a>
                <a href="{{ route('order.track') }}" class="public-nav-link">Track Order</a>

                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <div>
                        <button type="button" class="public-nav-link flex items-center gap-1" id="currency-menu-button"
                            aria-expanded="true" aria-haspopup="true"
                            onclick="document.getElementById('currency-dropdown').classList.toggle('hidden')">
                            {{ session('currency', 'MYR') }}
                            <i data-feather="chevron-down" style="width: 16px; height: 16px;"></i>
                        </button>
                    </div>
                    <div id="currency-dropdown"
                        class="hidden absolute right-0 z-10 mt-2 w-24 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="currency-menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <a href="#" onclick="setCurrency('MYR'); return false;"
                                class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-0">MYR</a>
                            <a href="#" onclick="setCurrency('BND'); return false;"
                                class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-1">BND</a>
                            <a href="#" onclick="setCurrency('SGD'); return false;"
                                class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-3">SGD</a>
                            <a href="#" onclick="setCurrency('IDR'); return false;"
                                class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-2">IDR</a>
                        </div>
                    </div>
                </div>

                @php $cartCount = is_array(session('cart')) ? count(session('cart')) : 0; @endphp
                <a href="{{ route('cart.show') }}" class="public-nav-link" aria-label="Cart" title="Cart"
                    style="position: relative; display: inline-flex; align-items: center; gap: 6px; padding: 4px 6px;">
                    <i data-feather="shopping-cart" style="width: 20px; height: 20px; color: #0f172a;"></i>
                    @if($cartCount)
                        <span
                            style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: #ffffff; border-radius: 6px; height: 18px; min-width: 18px; padding: 0 4px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden flex items-center gap-4">
                @php $cartCount = is_array(session('cart')) ? count(session('cart')) : 0; @endphp
                <a href="{{ route('cart.show') }}" class="public-nav-link relative" aria-label="Cart">
                    <i data-feather="shopping-cart" style="width: 20px; height: 20px; color: #0f172a;"></i>
                    @if($cartCount)
                        <span
                            style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: #ffffff; border-radius: 6px; height: 18px; min-width: 18px; padding: 0 4px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-800 focus:outline-none">
                    <i data-feather="menu" x-show="!mobileMenuOpen"></i>
                    <i data-feather="x" x-show="mobileMenuOpen" style="display: none;"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" style="display: none;" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden bg-white border-t border-gray-100 absolute w-full left-0 top-full shadow-lg z-50">
            <div class="flex flex-col p-4 space-y-4">
                <a href="/" class="block text-gray-800 hover:text-black font-medium">Home</a>
                <a href="{{ route('preorder.landing') }}"
                    class="block text-gray-800 hover:text-black font-medium">Pre-order</a>
                <a href="{{ route('products.index') }}"
                    class="block text-gray-800 hover:text-black font-medium">Products</a>
                <a href="{{ route('order.track') }}" class="block text-gray-800 hover:text-black font-medium">Track
                    Order</a>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-sm text-gray-500 mb-2">Currency</p>
                    <div class="flex gap-3">
                        <button onclick="setCurrency('MYR')"
                            class="px-3 py-1 text-sm rounded {{ session('currency', 'MYR') == 'MYR' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700' }}">MYR</button>
                        <button onclick="setCurrency('BND')"
                            class="px-3 py-1 text-sm rounded {{ session('currency', 'MYR') == 'BND' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700' }}">BND</button>
                        <button onclick="setCurrency('IDR')"
                            class="px-3 py-1 text-sm rounded {{ session('currency', 'MYR') == 'IDR' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700' }}">IDR</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="public-footer">
        <p>&copy; 2026 {{ config('app.name', 'Maxumax') }}. All rights reserved.</p>
    </footer>

    <script>
        function setCurrency(currency) {
            fetch('{{ route('currency.set') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ currency: currency })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Close currency dropdown when clicking outside
            document.addEventListener('click', function (event) {
                var dropdown = document.getElementById('currency-dropdown');
                var button = document.getElementById('currency-menu-button');
                if (dropdown && !dropdown.classList.contains('hidden') && !button.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>