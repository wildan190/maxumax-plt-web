<?php

namespace App\Services\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AdminNotificationService
{
    public function paginatedForAuthenticatedUser(int $perPage = 20): LengthAwarePaginator
    {
        $user = Auth::user();

        return $user->notifications()->paginate($perPage);
    }

    public function markSingleRead(string $id): void
    {
        Auth::user()->notifications()->findOrFail($id)->markAsRead();
    }

    public function markAllRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function deleteOne(string $id): void
    {
        Auth::user()->notifications()->findOrFail($id)->delete();
    }
}
