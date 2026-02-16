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

<body class="public-body bg-black text-white" x-data="{ 
    showSplash: !sessionStorage.getItem('splash_dismissed') && window.location.pathname === '/' 
}" :class="{ 'overflow-hidden': showSplash }">

    <!-- Splash Screen -->
    <div x-show="showSplash" x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] bg-black flex flex-col items-center justify-center">

        <!-- Logo -->
        <div class="relative mb-12 transform transition-all duration-1000 scale-110">
            <img src="{{ asset('assets/img/logo.png') }}" alt="MAXUMAX"
                class="w-[280px] sm:w-[400px] md:w-[500px] invert brightness-0" />
        </div>

        <!-- Shop Now Button -->
        <button @click="showSplash = false; sessionStorage.setItem('splash_dismissed', 'true')"
            class="px-12 py-4 bg-white text-black font-black text-sm tracking-[0.2em] rounded-full uppercase transition-all duration-300 hover:bg-slate-200 hover:scale-105 active:scale-95 shadow-2xl">
            Shop Now
        </button>
    </div>

    @php
        $cartCount = is_array(session('cart')) ? count(session('cart')) : 0;
    @endphp

    <!-- NAVBAR -->
    <nav x-show="!showSplash" x-data="{ mobileMenuOpen: false }" class="bg-black py-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">

            <!-- BRAND -->
            <a href="/" class="flex items-center">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Maxumax Logo" class="h-5 md:h-7 invert brightness-0">
            </a>

            <!-- DESKTOP NAV -->
            <div class="hidden lg:flex items-center gap-10">
                <a href="/"
                    class="text-white font-black uppercase tracking-widest text-xs hover:text-slate-400 transition-colors">Home</a>
                <a href="{{ route('products.index') }}"
                    class="text-white font-black uppercase tracking-widest text-xs hover:text-slate-400 transition-colors">Products</a>
                <a href="{{ route('order.track') }}"
                    class="text-white font-black uppercase tracking-widest text-xs hover:text-slate-400 transition-colors">Track
                    Order</a>

                <div class="h-4 w-px bg-white/20 mx-2"></div>

                <!-- Currency Desktop -->
                <div class="relative" x-data="{ open: false }" @click.away="open=false">
                    <button @click="open=!open"
                        class="flex items-center gap-2 text-white font-black uppercase tracking-widest text-xs">
                        {{ session('currency', 'MYR') }}
                        <i data-feather="chevron-down" style="width:14px;height:14px"></i>
                    </button>

                    <div x-show="open" x-cloak x-transition
                        class="absolute right-0 mt-4 w-32 bg-slate-900 border border-white/10 rounded-xl shadow-2xl overflow-hidden">
                        @foreach (['MYR', 'BND', 'SGD', 'IDR'] as $curr)
                            <button onclick="setCurrency('{{ $curr }}')"
                                class="flex w-full items-center justify-between px-4 py-3 text-xs font-black uppercase tracking-widest
                                                   {{ session('currency', 'MYR') == $curr ? 'bg-white text-black' : 'text-white hover:bg-white/10' }}">
                                {{ $curr }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Cart Desktop -->
                <a href="{{ route('cart.show') }}" class="relative text-white hover:text-slate-400 transition-colors">
                    <i data-feather="shopping-cart" style="width:18px;height:18px;"></i>
                    @if ($cartCount)
                        <span
                            class="absolute -top-2 -right-2 bg-white text-black text-[9px] font-black h-4 min-w-[16px] px-1 rounded-full flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </div>

            <!-- MOBILE RIGHT -->
            <div class="lg:hidden flex items-center gap-6">
                <a href="{{ route('cart.show') }}" class="relative text-white">
                    <i data-feather="shopping-cart" style="width:20px;height:20px"></i>
                    @if ($cartCount)
                        <span
                            class="absolute -top-2 -right-2 bg-white text-black text-[9px] font-black h-4 min-w-[16px] px-1 rounded-full flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white">
                    <i data-feather="menu" x-show="!mobileMenuOpen"></i>
                    <i data-feather="x" x-show="mobileMenuOpen" style="display:none"></i>
                </button>
            </div>
        </div>

        <!-- MOBILE MENU -->
        <div x-show="mobileMenuOpen" x-transition x-cloak
            class="lg:hidden bg-black border-t border-white/10 shadow-2xl">
            <div class="flex flex-col p-6 space-y-6">

                <!-- Nav Links -->
                <a @click="mobileMenuOpen=false" href="/"
                    class="text-white font-black uppercase tracking-widest text-sm">Home</a>
                <a @click="mobileMenuOpen=false" href="{{ route('products.index') }}"
                    class="text-white font-black uppercase tracking-widest text-sm">Products</a>
                <a @click="mobileMenuOpen=false" href="{{ route('order.track') }}"
                    class="text-white font-black uppercase tracking-widest text-sm">Track Order</a>

                <!-- Currency Mobile -->
                <div class="pt-6 border-t border-white/10">
                    <p class="text-[10px] font-black text-white/40 mb-4 uppercase tracking-[0.2em]">Currency</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach (['MYR', 'BND', 'SGD', 'IDR'] as $curr)
                            <button onclick="setCurrency('{{ $curr }}')"
                                class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest
                                        {{ session('currency', 'MYR') == $curr ? 'bg-white text-black' : 'bg-white/5 text-white hover:bg-white/10' }}">
                                {{ $curr }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </nav>


    <!-- Main Content -->
    <main x-show="!showSplash" class="w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer x-show="!showSplash" class="bg-slate-900 border-t border-white/5 pt-24 pb-12 px-6 overflow-hidden">
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
                        <a href="https://www.instagram.com/maxumax.my/" target="_blank"
                            class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-instagram">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                                </rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/maxumax.my/" target="_blank"
                            class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-facebook">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="https://www.tiktok.com/@maxumax.my" target="_blank"
                            class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-tiktok">
                                <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                            </svg>
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
                                class="text-slate-400 hover:text-white font-medium transition-colors">Track
                                Shipment</a>
                        </li>
                        <li><a href="#" class="text-slate-400 hover:text-white font-medium transition-colors">Size
                                Guide</a></li>
                        <li><a href="{{ route('pages.policies') }}"
                                class="text-slate-400 hover:text-white font-medium transition-colors">Policies &
                                Terms</a></li>
                        <li><a href="{{ route('order.track') }}"
                                class="text-slate-400 hover:text-white font-medium transition-colors">FAQ</a>
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
                            <span class="text-slate-400 font-medium">+60 14-343 6496</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <i data-feather="map-pin" class="text-blue-400 mt-1" style="width:18px;height:18px;"></i>
                            <span class="text-slate-400 font-medium leading-relaxed">
                                Kepayan Perdana No A5-2<br>
                                First Floor, Block A<br>
                                Kota Kinabalu, Sabah, 88200<br>
                                Malaysia
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">
                    &copy; 2026 {{ config('app.name', 'Maxumax') }} Pro. All Rights Reserved.
                </p>
                <div class="flex gap-8">
                    <a href="{{ route('pages.policies') }}"
                        class="text-slate-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Policies</a>
                    <a href="{{ route('pages.policies') }}"
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
                body: JSON.stringify({
                    currency: currency
                })
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
                if (dropdown && !dropdown.classList.contains('hidden') && !button.contains(event.target) &&
                    !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>