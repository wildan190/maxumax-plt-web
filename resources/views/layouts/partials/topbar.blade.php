<!-- Topbar/Navigation Partial -->
<div class="admin-topbar">
    <div class="admin-topbar-left">
        <button type="button" class="admin-topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <!-- Page title is rendered in the main header to avoid duplication -->
    </div>

    <div class="admin-topbar-right">
        <div class="admin-notification-wrapper" id="notificationDropdown">
            <button class="admin-topbar-notifications" id="notificationToggle" aria-label="Notifications" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </button>

            <div class="admin-notification-dropdown" id="notificationMenu">
                <div class="admin-notification-dropdown-header">
                    <h6>Notifikasi</h6>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="admin-mark-read-all">Mark all read</button>
                        </form>
                    @endif
                </div>
                <div class="admin-notification-dropdown-body">
                    @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                        <div class="admin-notification-dropdown-item {{ $notification->unread() ? 'unread' : '' }}">
                            <div class="admin-notification-dropdown-icon">
                                @switch($notification->data['type'] ?? '')
                                    @case('new_order')
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                        @break
                                    @case('new_preorder')
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                        @break
                                    @case('new_complaint')
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                        @break
                                    @case('replacement_ready')
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-info"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        @break
                                    @default
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                @endswitch
                            </div>
                            <div class="admin-notification-dropdown-content">
                                <p>{{ Str::limit($notification->data['message'] ?? 'No message', 50) }}</p>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="admin-notification-dropdown-empty">
                            Tidak ada notifikasi baru
                        </div>
                    @endforelse
                </div>
                <div class="admin-notification-dropdown-footer">
                    <a href="{{ route('admin.notifications.index') }}">Lihat Semua</a>
                </div>
            </div>
        </div>

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