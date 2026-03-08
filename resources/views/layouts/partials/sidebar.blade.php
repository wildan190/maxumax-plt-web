<!-- Sidebar Partial -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-header">
        <a href="{{ route('dashboard') }}" class="admin-sidebar-logo">
            {{ config('app.name', 'Maxumax') }}
        </a>
        <!-- Close Button for Mobile -->
        <button type="button" class="admin-sidebar-close" id="sidebarClose" aria-label="Close sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <nav class="admin-sidebar-nav">
        <!-- Main Section -->
        <div class="admin-nav-section-title">Menu Utama</div>

        <a href="{{ route('dashboard') }}" class="admin-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                <path d="M21 3v5h-5"></path>
                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                <path d="M3 21v-5h5"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('profile.show') }}"
            class="admin-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Profil</span>
        </a>

        <!-- Management Section -->
        <div class="admin-nav-section-title">Manajemen</div>

        <a href="{{ route('admin.preorders.index') }}"
            class="admin-nav-item {{ request()->routeIs('admin.preorders.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 7h18M3 12h18M3 17h18"></path>
            </svg>
            <span>Preorders</span>
            @if(auth()->user()->unreadNotifications->where('data.type', 'new_preorder')->count() > 0)
                <span class="notification-dot"></span>
            @endif
        </a>

        <a href="{{ route('admin.orders.index') }}"
            class="admin-nav-item {{ request()->routeIs('admin.orders.index') || request()->routeIs('admin.orders.show') || request()->routeIs('admin.orders.confirm') || request()->routeIs('admin.orders.markPaid') || request()->routeIs('admin.orders.destroy') || request()->routeIs('admin.orders.export') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Orders</span>
            @if(auth()->user()->unreadNotifications->whereIn('data.type', ['new_order', 'replacement_ready'])->count() > 0)
                <span class="notification-dot"></span>
            @endif
        </a>

        <a href="{{ route('admin.products.index') }}"
            class="admin-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
            </svg>
            <span>Products</span>
        </a>

        <a href="{{ route('admin.orders.history') }}"
            class="admin-nav-item {{ request()->routeIs('admin.orders.history') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 4h18"></path>
                <path d="M3 10h18"></path>
                <path d="M3 16h18"></path>
                <circle cx="7" cy="10" r="1"></circle>
                <circle cx="7" cy="16" r="1"></circle>
                <circle cx="7" cy="4" r="1"></circle>
            </svg>
            <span>Orders History</span>
        </a>

        <a href="{{ route('admin.shipping.myparcel.index') }}"
            class="admin-nav-item {{ request()->routeIs('admin.shipping.myparcel.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="3" width="15" height="13"></rect>
                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                <circle cx="18.5" cy="18.5" r="2.5"></circle>
            </svg>
            <span>MyParcel Asia</span>
        </a>

        <a href="{{ route('admin.reports.index') }}"
            class="admin-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
            <span>Reports</span>
        </a>

        <a href="{{ route('admin.galleries.index') }}"
            class="admin-nav-item {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
            <span>Gallery</span>
        </a>

        <a href="{{ route('admin.complaints.index') }}"
            class="admin-nav-item {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span>Complaints</span>
            @if(auth()->user()->unreadNotifications->where('data.type', 'new_complaint')->count() > 0)
                <span class="notification-dot"></span>
            @endif
        </a>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.users.index') }}"
                class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Users</span>
            </a>
        @endif

        <!-- Account Section -->
        <div class="admin-nav-section-title">Account</div>

        <form method="POST" action="{{ route('logout') }}" style="display: contents;">
            @csrf
            <button type="submit" class="admin-nav-item"
                style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; font-size: 0.95rem; color: var(--text-light); padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    style="width: 20px; height: 20px; flex-shrink: 0;">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>