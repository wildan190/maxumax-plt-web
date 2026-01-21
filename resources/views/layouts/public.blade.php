<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Maxumax')) - Premium Quality Jersey</title>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="@yield('meta_description', 'Maxumax - Premium quality jerseys for sports and lifestyle. Expertly crafted in Malaysia. Pre-order now for exclusive designs.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name', 'Maxumax'))">
    <meta property="og:description"
        content="@yield('meta_description', 'Maxumax - Premium quality jerseys for sports and lifestyle. Expertly crafted in Malaysia.')">
    <meta property="og:image" content="{{ asset('assets/img/og-image.jpg') }}">
    <meta property="og:site_name" content="Maxumax Malaysia">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name', 'Maxumax'))">
    <meta property="twitter:description"
        content="@yield('meta_description', 'Maxumax - Premium quality jerseys for sports and lifestyle.')">
    <meta property="twitter:image" content="{{ asset('assets/img/og-image.jpg') }}">

    <!-- Schema Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Maxumax Malaysia",
      "image": "{{ asset('assets/img/logo.png') }}",
      "@id": "https://maxumax.my",
      "url": "https://maxumax.my",
      "telephone": "+60XXXXXXXX",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Main Street",
        "addressLocality": "Kuala Lumpur",
        "postalCode": "XXXXX",
        "addressCountry": "MY"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 3.1390,
        "longitude": 101.6869
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday",
          "Sunday"
        ],
        "opens": "00:00",
        "closes": "23:59"
      }
    }
    </script>

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
                <img src="{{ asset('assets/img/logo.png') }}" alt="{{ config('app.name', 'Maxumax') }} Logo"
                    class="public-logo">
            </a>

            <!-- Desktop Nav -->
            <div class="public-nav-links hidden lg:flex items-center gap-10">
                <a href="/" class="public-nav-link group relative py-2">
                    Home
                    <span
                        class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('preorder.landing') }}" class="public-nav-link group relative py-2">
                    Pre-order
                    <span
                        class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('products.index') }}" class="public-nav-link group relative py-2">
                    Products
                    <span
                        class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('order.track') }}" class="public-nav-link group relative py-2">
                    Track Order
                    <span
                        class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>

                <div class="h-6 w-px bg-slate-200 mx-2"></div>

                <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
                    <button type="button" @click="open = !open"
                        class="public-nav-link flex items-center gap-2 font-black uppercase tracking-widest text-xs px-3 py-1.5 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                        {{ session('currency', 'MYR') }}
                        <i data-feather="chevron-down" style="width: 14px; height: 14px;"></i>
                    </button>
                    <div x-show="open" x-cloak
                        class="absolute right-0 z-[100] mt-3 w-32 origin-top-right rounded-xl bg-white shadow-2xl ring-1 ring-black/5 focus:outline-none overflow-hidden"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                        <div class="py-1">
                            @foreach(['MYR', 'BND', 'SGD', 'IDR'] as $curr)
                                <a href="#" onclick="setCurrency('{{ $curr }}'); return false;"
                                    class="flex items-center justify-between px-4 py-2.5 text-sm font-bold {{ session('currency', 'MYR') == $curr ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                                    {{ $curr }}
                                    @if(session('currency', 'MYR') == $curr)
                                        <i data-feather="check" style="width:14px;height:14px;"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @php $cartCount = is_array(session('cart')) ? count(session('cart')) : 0; @endphp
                <a href="{{ route('cart.show') }}"
                    class="relative group p-2 bg-slate-900 rounded-xl hover:bg-blue-600 transition-all hover:scale-110 active:scale-95 shadow-lg shadow-slate-900/10">
                    <i data-feather="shopping-cart" style="width: 18px; height: 18px; color: #ffffff;"></i>
                    @if($cartCount)
                        <span
                            class="absolute -top-1.5 -right-1.5 bg-white text-blue-600 border-2 border-slate-900 rounded-full h-5 min-w-[20px] px-1 font-black text-[10px] flex items-center justify-center">
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
    <footer class="bg-slate-900 border-t border-white/5 pt-24 pb-12 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
                <div class="col-span-1 lg:col-span-1">
                    <a href="/" class="inline-block mb-8">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Maxumax Logo"
                            class="h-10 w-auto invert brightness-0">
                    </a>
                    <p class="text-slate-400 font-medium leading-relaxed mb-8">
                        Elevating performance through precision engineered sports apparel. Defined by speed, durability,
                        and aesthetics.
                    </p>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-all">
                            <i data-feather="instagram" style="width:18px;height:18px;"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-all">
                            <i data-feather="facebook" style="width:18px;height:18px;"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-all">
                            <i data-feather="twitter" style="width:18px;height:18px;"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Navigation</h4>
                    <ul class="space-y-4">
                        <li><a href="/" class="text-slate-400 hover:text-white font-medium transition-colors">Home
                                Archive</a></li>
                        <li><a href="{{ route('preorder.landing') }}"
                                class="text-slate-400 hover:text-white font-medium transition-colors">Pre-order
                                Drops</a></li>
                        <li><a href="{{ route('products.index') }}"
                                class="text-slate-400 hover:text-white font-medium transition-colors">Season
                                Inventory</a></li>
                        <li><a href="{{ route('gallery.index') }}"
                                class="text-slate-400 hover:text-white font-medium transition-colors">Visual
                                Collection</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Support</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('order.track') }}"
                                class="text-slate-400 hover:text-white font-medium transition-colors">Track Shipment</a>
                        </li>
                        <li><a href="#" class="text-slate-400 hover:text-white font-medium transition-colors">Size
                                Guide</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white font-medium transition-colors">Return
                                Policy</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white font-medium transition-colors">FAQ</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">HQ Contact</h4>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <i data-feather="mail" class="text-blue-400 mt-1" style="width:18px;height:18px;"></i>
                            <span class="text-slate-400 font-medium">contact@maxumax.my</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <i data-feather="phone" class="text-blue-400 mt-1" style="width:18px;height:18px;"></i>
                            <span class="text-slate-400 font-medium">+60 3-XXXX XXXX</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <i data-feather="map-pin" class="text-blue-400 mt-1" style="width:18px;height:18px;"></i>
                            <span class="text-slate-400 font-medium">Kuala Lumpur, Malaysia</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">
                    &copy; 2026 {{ config('app.name', 'Maxumax') }} Pro. All Rights Reserved.
                </p>
                <div class="flex gap-8">
                    <a href="#"
                        class="text-slate-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Privacy</a>
                    <a href="#"
                        class="text-slate-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Terms</a>
                </div>
            </div>
        </div>
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