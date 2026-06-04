<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Maxumax – Sportswear Customization Expert')</title>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="@yield('meta_description', 'MAXUMAX is a sportswear brand based in Kota Kinabalu, Sabah, offering ready stock sportswear and fully customized teamwear for teams, schools, clubs, companies, events, and organizations.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name', 'Maxumax'))">
    <meta property="og:description"
        content="@yield('meta_description', 'Maxumax - Premium quality jerseys for sports and lifestyle. Expertly crafted in Malaysia.')">
    <meta property="og:image" content="@yield('meta_image', asset('assets/img/og-image.jpg'))">
    <meta property="og:site_name" content="Maxumax Malaysia">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name', 'Maxumax'))">
    <meta property="twitter:description"
        content="@yield('meta_description', 'Maxumax - Premium quality jerseys for sports and lifestyle.')">
    <meta property="twitter:image" content="@yield('meta_image', asset('assets/img/og-image.jpg'))">

    <!-- Schema Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Maxumax Malaysia",
      "image": "{{ asset('assets/img/logo.png') }}",
      "@id": "https://maxumax.my",
      "url": "https://maxumax.my",
      "telephone": "+601131614760",
      "email": "maxumax.my@gmail.com",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Lot 27, Ground Floor, Block D, Plaza 333, Penampang",
        "addressLocality": "Kota Kinabalu",
        "addressRegion": "Sabah",
        "postalCode": "88300",
        "addressCountry": "MY"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 5.9189,
        "longitude": 116.0717
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday"
        ],
        "opens": "09:00",
        "closes": "18:00"
      },
      "sameAs": [
        "https://www.facebook.com/maxumax.my",
        "https://www.instagram.com/maxumax.my"
      ]
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
    @stack('schema')
</head>

<body class="public-body bg-black text-white">

    @include('partials.public.navbar')


    <!-- Main Content -->
    <main class="w-full">
        @yield('content')
    </main>

    @include('partials.public.footer')

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