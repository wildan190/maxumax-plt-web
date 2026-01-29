<?php

namespace App\Observers;

use App\Models\Complaint;
use App\Models\User;
use App\Notifications\NewComplaintNotification;
use Illuminate\Support\Facades\Notification;

class ComplaintObserver
{
    /**
     * Handle the Complaint "created" event.
     */
    public function created(Complaint $complaint): void
    {
        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($admins, new NewComplaintNotification($complaint));
    }
}
