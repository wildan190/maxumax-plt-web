<?php

namespace App\Notifications;

use App\Models\Preorder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReplacementNeedsResendingNotification extends Notification
{
    use Queueable;

    protected $replacementOrder;

    /**
     * Create a new notification instance.
     */
    public function __construct(Preorder $replacementOrder)
    {
        $this->replacementOrder = $replacementOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'replacement_ready',
            'order_id' => $this->replacementOrder->id,
            'order_number' => $this->replacementOrder->order_number,
            'customer_name' => $this->replacementOrder->name,
            'message' => "Replacement order #{$this->replacementOrder->order_number} for {$this->replacementOrder->name} is ready for shipping (Return Received).",
        ];
    }
}
