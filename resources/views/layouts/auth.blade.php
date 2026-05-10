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
        <!-- Center - Form -->
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Initialize Feather Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            feather.replace();
        });
    </script>

    <!-- Vite handled scripts included above -->
</body>

</html>