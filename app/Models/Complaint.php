<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Preorder;
use App\Models\User;


class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'preorder_id',
        'type',
        'status',
        'reason',
        'refund_amount',
        'return_tracking_number',
        'return_status',
        'replacement_order_number',
        'expires_at',
        'approved_at',
        'return_received_at',
        'rejected_at',
        'completed_at',
        'approved_by',
        'admin_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'return_received_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
        'refund_amount' => 'decimal:2',
    ];

    public function preorder()
    {
        return $this->belongsTo(Preorder::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isExpired(): bool
    {
        return $this->status === 'pending' && now()->isAfter($this->expires_at);
    }

    public function canBeCancelled(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }

    public function canConfirmReturn(): bool
    {
        return $this->type === 'replacement' &&
            $this->status === 'approved' &&
            $this->return_status === 'waiting_return';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')->where('expires_at', '<', now());
    }
}

