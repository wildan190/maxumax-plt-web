<?php

namespace App\Services\Complaint;

use App\Models\Complaint;
use App\Models\Preorder;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CustomerComplaintService
{
    public function findPreorderForComplaint(string $preorder): Preorder
    {
        return Preorder::with(['product', 'histories', 'complaints'])
            ->where('order_number', $preorder)
            ->orWhere('id', $preorder)
            ->orWhere('uuid', $preorder)
            ->firstOrFail();
    }

    public function expiresAtAfterDelivery(Preorder $preorder): ?Carbon
    {
        $deliveryTime = $preorder->getDeliveryTimestamp();
        if (!$deliveryTime) {
            return null;
        }

        return Carbon::parse($deliveryTime)->addDays(7);
    }

    public function activeComplaintBlocking(Preorder $preorder): ?Complaint
    {
        return $preorder->complaints()->whereIn('status', ['pending', 'approved'])->first();
    }

    /**
     * @param  array{type: string, reason: string}  $data  Validated payload (preorder resolved separately)
     */
    public function createComplaint(Preorder $preorder, array $data): Complaint
    {
        $deliveryTime = $preorder->getDeliveryTimestamp();
        if (!$deliveryTime) {
            throw ValidationException::withMessages([
                'delivery' => 'Order must be delivered before filing a complaint.',
            ]);
        }

        $expiresAt = Carbon::parse($deliveryTime)->addDays(7);
        if (now()->isAfter($expiresAt)) {
            throw ValidationException::withMessages([
                'expired' => 'Complaint window has expired.',
            ]);
        }

        return Complaint::create([
            'preorder_id' => $preorder->id,
            'type' => $data['type'],
            'reason' => $data['reason'],
            'status' => 'pending',
            'refund_amount' => $data['type'] === 'refund' ? $preorder->total_amount : null,
            'expires_at' => $expiresAt,
        ]);
    }
}
