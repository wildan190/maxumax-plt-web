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
    </head>
    <body>
        <div class="admin-layout">
            <!-- Sidebar -->
            @include('layouts.partials.sidebar')

            <!-- Main Content -->
            <div class="admin-main">
                <!-- Topbar -->
                @include('layouts.partials.topbar')

                <!-- Content Area -->
                <div class="admin-content">
                    <!-- Breadcrumb -->
                    @if (isset($breadcrumbs))
                        @include('layouts.partials.breadcrumb')
                    @endif

                    <!-- Page Header -->
                    <div class="admin-page-header">
                        <h1 class="admin-page-title">@yield('page-title', 'Dashboard')</h1>
                        @if (View::hasSection('page-subtitle'))
                            <p class="admin-page-desc">@yield('page-subtitle')</p>
                        @endif
                    </div>

                    <!-- Main Content -->
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Sidebar Toggle Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Feather Icons
                feather.replace();

                // Sidebar toggle
                const sidebarToggle = document.getElementById('sidebarToggle');
                const adminSidebar = document.getElementById('adminSidebar');

                if (sidebarToggle && adminSidebar) {
                    sidebarToggle.addEventListener('click', function() {
                        adminSidebar.classList.toggle('active');
                    });

                    // Close sidebar when clicking outside
                    document.addEventListener('click', function(event) {
                        if (!event.target.closest('.admin-sidebar') && 
                            !event.target.closest('.admin-topbar-toggle')) {
                            adminSidebar.classList.remove('active');
                        }
                    });
                }
            });
        </script>

        <!-- Vite handled scripts included above -->
    </body>
</html>
