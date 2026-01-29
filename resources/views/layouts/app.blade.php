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
        /* Basic Layout Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }

        /* Admin Layout Container */
        .admin-layout {
            position: relative;
            min-height: 100vh;
        }

        /* Main Content Area */
        .admin-main {
            transition: margin-left 0.3s ease;
        }

        /* Admin Content */
        .admin-content {
            padding: 2rem;
        }

        /* Page Header */
        .admin-page-header {
            margin-bottom: 2rem;
        }

        .admin-page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 0.5rem;
        }

        .admin-page-desc {
            font-size: 1rem;
            color: #6b7280;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-content {
                padding: 1rem;
            }

            .admin-page-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar Overlay -->
        <div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

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
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Feather Icons
            if (window.feather) {
                feather.replace();
            }

            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const sidebarClose = document.getElementById('sidebarClose');

            console.log('Sidebar elements:', { sidebar, toggle, overlay, sidebarClose }); // Debug

            if (sidebar && toggle && overlay) {
                function openSidebar() {
                    console.log('Opening sidebar'); // Debug
                    // Remove display:none and force reflow
                    sidebar.style.display = 'block';
                    sidebar.offsetHeight; // Force reflow

                    // Add active classes
                    setTimeout(() => {
                        sidebar.classList.add('active');
                        overlay.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }, 10);
                }

                function closeSidebar() {
                    console.log('Closing sidebar'); // Debug
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';

                    // Wait for transition before hiding
                    setTimeout(() => {
                        if (!sidebar.classList.contains('active')) {
                            sidebar.style.display = '';
                        }
                    }, 300);
                }

                // Toggle button click
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Toggle clicked, sidebar active:', sidebar.classList.contains('active')); // Debug

                    if (sidebar.classList.contains('active')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });

                // Overlay click
                overlay.addEventListener('click', function (e) {
                    console.log('Overlay clicked'); // Debug
                    closeSidebar();
                });

                // Close button click
                if (sidebarClose) {
                    sidebarClose.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Close button clicked'); // Debug
                        closeSidebar();
                    });
                }

                // Close sidebar when clicking nav items on mobile
                const navItems = sidebar.querySelectorAll('.admin-nav-item');
                navItems.forEach(item => {
                    item.addEventListener('click', function () {
                        if (window.innerWidth <= 768) {
                            closeSidebar();
                        }
                    });
                });

                // Handle window resize
                window.addEventListener('resize', function () {
                    if (window.innerWidth > 768) {
                        sidebar.classList.remove('active');
                        sidebar.style.display = '';
                        overlay.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            } else {
                console.error('Sidebar elements not found!'); // Debug
            }

            // Notification Dropdown Toggle
            const notificationToggle = document.getElementById('notificationToggle');
            const notificationMenu = document.getElementById('notificationMenu');

            if (notificationToggle && notificationMenu) {
                notificationToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    notificationMenu.classList.toggle('active');
                    const isExpanded = notificationMenu.classList.contains('active');
                    notificationToggle.setAttribute('aria-expanded', isExpanded);
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function (e) {
                    if (!notificationMenu.contains(e.target) && !notificationToggle.contains(e.target)) {
                        notificationMenu.classList.remove('active');
                        notificationToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    </script>

    <!-- Vite handled scripts included above -->
</body>

</html>