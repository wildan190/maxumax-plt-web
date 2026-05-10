<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminNotificationService;

class NotificationController extends Controller
{
    public function index(AdminNotificationService $notifications)
    {
        $notificationsList = $notifications->paginatedForAuthenticatedUser();

        return view('admin.notifications.index', ['notifications' => $notificationsList]);
    }

    public function markAsRead(string $id, AdminNotificationService $notifications)
    {
        $notifications->markSingleRead($id);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(AdminNotificationService $notifications)
    {
        $notifications->markAllRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(string $id, AdminNotificationService $notifications)
    {
        $notifications->deleteOne($id);

        return back()->with('success', 'Notification deleted.');
    }
}
