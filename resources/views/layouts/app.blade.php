<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel')) - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Styles + Scripts (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col md:flex-row" x-data="{ sidebarOpen: false }">
        <!-- Sidebar Overlay (Mobile) -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/50 z-40 md:hidden"
            x-cloak>
        </div>

        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar -->
            @include('layouts.partials.topbar')

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                <!-- Flash Messages -->
                <div class="max-w-7xl mx-auto space-y-4 mb-6">
                    @if (session('status'))
                        <div class="flex items-center p-4 text-emerald-800 bg-emerald-50 border border-emerald-100 rounded-xl shadow-sm">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-emerald-100 text-emerald-600 rounded-full mr-3">
                                <i data-feather="check" class="w-4 h-4"></i>
                            </div>
                            <div class="text-sm font-medium">{{ session('status') }}</div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="flex items-center p-4 text-rose-800 bg-rose-50 border border-rose-100 rounded-xl shadow-sm">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-rose-100 text-rose-600 rounded-full mr-3">
                                <i data-feather="alert-circle" class="w-4 h-4"></i>
                            </div>
                            <div class="text-sm font-medium">{{ session('error') }}</div>
                        </div>
                    @endif
                </div>

                <div class="max-w-7xl mx-auto">
                    <!-- Breadcrumb -->
                    @if (isset($breadcrumbs))
                        <div class="mb-6">
                            @include('layouts.partials.breadcrumb')
                        </div>
                    @endif

                    <!-- Page Header -->
                    @unless (View::hasSection('hide-page-header'))
                        <div class="mb-8">
                            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">@yield('page-title', 'Dashboard')</h1>
                            @if (View::hasSection('page-subtitle'))
                                <p class="mt-1 text-slate-500">@yield('page-subtitle')</p>
                            @endif
                        </div>
                    @endunless

                    <!-- Main Content Content -->
                    <div class="animate-in fade-in slide-in-from-bottom-2 duration-500">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Feather Icons
            if (window.feather) {
                feather.replace();
            }
        });
    </script>
</body>

</html>