<?php

namespace App\Observers;

use App\Models\Preorder;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Notifications\NewPreorderNotification;
use App\Notifications\ReplacementNeedsResendingNotification;
use Illuminate\Support\Facades\Notification;

class PreorderObserver
{
    /**
     * Handle the Preorder "created" event.
     */
    public function created(Preorder $preorder): void
    {
        // For COD or other direct creation that might be considered "new" immediately
        // Note: For Stripe, we usually wait for "paid" status in the updated event.
        // However, if it's created as 'paid' or 'confirmed' (e.g. by admin or manual process)
        if (in_array($preorder->status, ['paid', 'confirmed'])) {
            $this->notifyAdmins($preorder);
        }
    }

    /**
     * Handle the Preorder "updated" event.
     */
    public function updated(Preorder $preorder): void
    {
        // Trigger notification when status changes to 'paid' (Stripe success)
        if ($preorder->isDirty('status') && $preorder->status === 'paid' && $preorder->getOriginal('status') === 'pending') {
            $this->notifyAdmins($preorder);
        }

        // Trigger notification for replacement orders that are now confirmed (return received)
        if ($preorder->isDirty('status') && $preorder->status === 'confirmed' && str_contains($preorder->order_number, '-R')) {
            $admins = User::whereIn('role', ['admin', 'staff'])->get();
            Notification::send($admins, new ReplacementNeedsResendingNotification($preorder));
        }
    }

    /**
     * Notify admins about new order/preorder
     */
    protected function notifyAdmins(Preorder $preorder): void
    {
        $admins = User::whereIn('role', ['admin', 'staff'])->get();

        if (str_starts_with($preorder->order_number, 'MM-PO-')) {
            Notification::send($admins, new NewPreorderNotification($preorder));
        } else {
            Notification::send($admins, new NewOrderNotification($preorder));
        }
    }
}
