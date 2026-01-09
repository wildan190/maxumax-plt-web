<!-- Topbar/Navigation Partial -->
<div class="admin-topbar">
    <div class="admin-topbar-left">
        <button type="button" class="admin-topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <!-- Page title is rendered in the main header to avoid duplication -->
    </div>

    <div class="admin-topbar-right">
        <div class="admin-topbar-user" id="userMenuToggle" aria-label="User menu">
            <div class="admin-user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="admin-topbar-user-info">
                <h5>{{ auth()->user()->name }}</h5>
                <p>{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</div>
