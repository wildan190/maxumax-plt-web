<?php

namespace App\Notifications;

use App\Models\Preorder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewPreorderNotification extends Notification
{
    use Queueable;

    protected $preorder;

    /**
     * Create a new notification instance.
     */
    public function __construct(Preorder $preorder)
    {
        $this->preorder = $preorder;
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
            'type' => 'new_preorder',
            'preorder_id' => $this->preorder->id,
            'order_number' => $this->preorder->order_number,
            'customer_name' => $this->preorder->name,
            'total_amount' => $this->preorder->total_amount,
            'currency' => $this->preorder->currency,
            'message' => "New preorder #{$this->preorder->order_number} received from {$this->preorder->name}.",
        ];
    }
}
