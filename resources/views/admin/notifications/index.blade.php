@extends('layouts.app')

@section('page-title', 'Notifications')

@section('content')
<div class="admin-container">
    <div class="admin-notifications-actions-bar">
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit" class="admin-btn-mark-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    Mark All As Read
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="admin-alert admin-alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-card">
        <div class="admin-notifications-list">
            @forelse($notifications as $notification)
                <div class="admin-notification-item {{ $notification->unread() ? 'unread' : '' }}">
                    <div class="admin-notification-icon">
                        <div class="icon-circle {{ $notification->data['type'] ?? '' }}">
                            @switch($notification->data['type'] ?? '')
                                @case('new_order')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    @break
                                @case('new_preorder')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    @break
                                @case('new_complaint')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                    @break
                                @case('replacement_ready')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    @break
                                @default
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                            @endswitch
                        </div>
                    </div>
                    <div class="admin-notification-content">
                        <div class="notification-text">
                            <p class="notification-message">{{ $notification->data['message'] ?? 'No message' }}</p>
                            <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="admin-notification-actions">
                            @php
                                $link = '#';
                                if (($notification->data['type'] ?? '') == 'new_order') {
                                    $link = route('admin.orders.show', $notification->data['order_number'] ?? 0);
                                } elseif (($notification->data['type'] ?? '') == 'new_preorder') {
                                    $link = route('admin.preorders.show', $notification->data['order_number'] ?? 0);
                                } elseif (($notification->data['type'] ?? '') == 'new_complaint') {
                                    $link = route('admin.complaints.show', $notification->data['complaint_id'] ?? 0);
                                } elseif (($notification->data['type'] ?? '') == 'replacement_ready') {
                                    $link = route('admin.orders.show', $notification->data['order_number'] ?? 0);
                                }
                            @endphp
                            
                            <a href="{{ $link }}" class="action-btn detail">View Details</a>
                            
                            @if($notification->unread())
                                <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="action-btn mark-read">Mark As Read</button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" onclick="return confirm('Delete this notification?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="admin-empty-state">
                    <div class="empty-state-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </div>
                    <p>No notifications at the moment.</p>
                </div>
            @endforelse
        </div>
        
        @if($notifications->hasPages())
            <div class="admin-pagination-container">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

<style>
:root {
    --primary: #3b82f6;
    --border-gray: #e5e7eb;
    --text-dark: #1f2937;
    --text-light: #6b7280;
    --hover-light: rgba(59, 130, 246, 0.05);
    --light-gray: #f3f4f6;
}

.admin-notifications-actions-bar {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.5rem;
}

.admin-notifications-actions-bar form {
    margin: 0;
}

.admin-btn-mark-all {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.admin-btn-mark-all:hover {
    opacity: 0.9;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.admin-notifications-list {
    display: flex;
    flex-direction: column;
    background: white;
    border-radius: 8px;
    border: 1px solid var(--border-gray);
    overflow: hidden;
}

.admin-notification-item {
    display: flex;
    gap: 1.5rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-gray);
    transition: all 0.2s ease;
    align-items: flex-start;
}

.admin-notification-item:last-child {
    border-bottom: none;
}

.admin-notification-item.unread {
    background-color: rgba(59, 130, 246, 0.02);
}

.admin-notification-item:hover {
    background-color: var(--hover-light);
}

.admin-notification-icon {
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.icon-circle {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light-gray);
    color: var(--text-light);
}

.icon-circle svg {
    width: 24px;
    height: 24px;
}

.icon-circle.new_order { 
    background: rgba(16, 185, 129, 0.1); 
    color: #10b981; 
}
.icon-circle.new_preorder { 
    background: rgba(59, 130, 246, 0.1); 
    color: #3b82f6; 
}
.icon-circle.new_complaint { 
    background: rgba(239, 68, 68, 0.1); 
    color: #ef4444; 
}
.icon-circle.replacement_ready { 
    background: rgba(14, 165, 233, 0.1); 
    color: #0ea5e9; 
}

.admin-notification-content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-width: 0;
}

.notification-text {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.notification-message {
    margin: 0;
    font-weight: 600;
    color: var(--text-dark);
    font-size: 1rem;
    word-break: break-word;
}

.notification-time {
    font-size: 0.85rem;
    color: var(--text-light);
    display: block;
}

.admin-notification-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.action-btn {
    background: none;
    border: none;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.action-btn.detail {
    color: white;
    background: var(--primary);
}

.action-btn.detail:hover {
    opacity: 0.9;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.action-btn.mark-read {
    color: var(--primary);
    background: rgba(59, 130, 246, 0.1);
}

.action-btn.mark-read:hover {
    background: rgba(59, 130, 246, 0.15);
}

.action-btn.delete {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
}

.action-btn.delete:hover {
    background: rgba(239, 68, 68, 0.2);
}

.admin-empty-state {
    padding: 5rem 2rem;
    text-align: center;
    color: var(--text-light);
}

.empty-state-icon {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
    opacity: 0.3;
}

.admin-pagination-container {
    padding: 1.5rem;
    border-top: 1px solid var(--border-gray);
    display: flex;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-notification-item {
        flex-direction: column;
        gap: 1rem;
    }

    .admin-notification-actions {
        flex-direction: column;
        width: 100%;
    }

    .action-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection
