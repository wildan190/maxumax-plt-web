<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewComplaintNotification extends Notification
{
    use Queueable;

    protected $complaint;

    /**
     * Create a new notification instance.
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
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
        $orderNumber = $this->complaint->preorder->order_number ?? 'Unknown';
        $customerName = $this->complaint->preorder->name ?? 'Unknown';

        return [
            'type' => 'new_complaint',
            'complaint_id' => $this->complaint->id,
            'order_number' => $orderNumber,
            'customer_name' => $customerName,
            'complaint_type' => $this->complaint->type,
            'message' => "New {$this->complaint->type} complaint received for order #{$orderNumber} from {$customerName}.",
        ];
    }
}
