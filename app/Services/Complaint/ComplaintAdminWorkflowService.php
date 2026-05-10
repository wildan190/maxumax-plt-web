<?php

namespace App\Services\Complaint;

use App\Models\Complaint;
use App\Models\Preorder;
use App\Models\PreorderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Refund;
use Stripe\Stripe;

class ComplaintAdminWorkflowService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function paginatedIndex(Request $request)
    {
        $query = Complaint::with('preorder.product', 'approver');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return $query->latest()->paginate(20);
    }

    /**
     * @throws \Throwable
     */
    public function approveComplaint(Request $request, Complaint $complaint): void
    {
        DB::beginTransaction();
        try {
            if ($complaint->type === 'refund') {
                $this->processRefund($complaint);
            } else {
                $this->processReplacement($complaint);
            }

            $complaint->update([
                'status' => 'approved',
                'return_status' => $complaint->type === 'replacement' ? 'waiting_return' : 'pending',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'admin_notes' => $request->input('admin_notes'),
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rejectComplaint(Request $request, Complaint $complaint): void
    {
        $complaint->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        PreorderHistory::create([
            'preorder_id' => $complaint->preorder_id,
            'old_status' => $complaint->preorder->status,
            'new_status' => $complaint->preorder->status,
            'note' => 'Complaint rejected: ' . $request->input('rejection_reason'),
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function confirmReturnShipment(Complaint $complaint): void
    {
        DB::beginTransaction();
        try {
            $complaint->update([
                'return_status' => 'received',
                'return_received_at' => now(),
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $replacementOrder = Preorder::where('order_number', $complaint->replacement_order_number)->first();
            if ($replacementOrder) {
                $replacementOrder->update(['status' => 'confirmed']);

                PreorderHistory::create([
                    'preorder_id' => $replacementOrder->id,
                    'old_status' => 'pending',
                    'new_status' => 'confirmed',
                    'note' => 'Replacement order activated: Return shipment received.',
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function processRefund(Complaint $complaint): void
    {
        $preorder = $complaint->preorder;

        if (!$preorder->stripe_payment_intent_id) {
            throw new \Exception('No Stripe payment found for this order.');
        }

        $amountInCents = (int) round($complaint->refund_amount * 100);

        $refund = Refund::create([
            'payment_intent' => $preorder->stripe_payment_intent_id,
            'amount' => $amountInCents,
            'metadata' => [
                'complaint_id' => $complaint->id,
                'reason' => $complaint->reason,
            ],
        ]);

        $preorder->update([
            'status' => 'refunded',
            'refund_status' => 'completed',
            'stripe_refund_id' => $refund->id,
        ]);

        PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $preorder->getOriginal('status'),
            'new_status' => 'refunded',
            'note' => 'Refund processed via complaint system. Refund ID: ' . $refund->id,
        ]);
    }

    protected function processReplacement(Complaint $complaint): void
    {
        $originalOrder = $complaint->preorder;

        $replacementOrder = Preorder::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'order_number' => $this->generateReplacementOrderNumber($originalOrder),
            'product_id' => $originalOrder->product_id,
            'product_variant_id' => $originalOrder->product_variant_id,
            'name' => $originalOrder->name,
            'email' => $originalOrder->email,
            'phone' => $originalOrder->phone,
            'address' => $originalOrder->address,
            'jersey_type' => $originalOrder->jersey_type,
            'size' => $originalOrder->size,
            'long_sleeve' => $originalOrder->long_sleeve,
            'custom_fields' => $originalOrder->custom_fields,
            'quantity' => $originalOrder->quantity,
            'unit_price' => $originalOrder->unit_price,
            'total_amount' => 0,
            'currency' => $originalOrder->currency,
            'status' => 'pending',
            'notes' => 'Replacement order (Pending Return) for: ' . $originalOrder->order_number,
            'items' => $originalOrder->items,
        ]);

        PreorderHistory::create([
            'preorder_id' => $replacementOrder->id,
            'old_status' => null,
            'new_status' => 'pending',
            'note' => 'Replacement order created (Waiting for Return) for complaint ID: ' . $complaint->id,
        ]);

        $originalOrder->update(['status' => 'replaced']);

        PreorderHistory::create([
            'preorder_id' => $originalOrder->id,
            'old_status' => $originalOrder->getOriginal('status'),
            'new_status' => 'replaced',
            'note' => 'Order replaced with: ' . $replacementOrder->order_number,
        ]);

        $complaint->update([
            'replacement_order_number' => $replacementOrder->order_number,
        ]);
    }

    protected function generateReplacementOrderNumber($originalOrder): string
    {
        return $originalOrder->order_number . '-R' . now()->format('ymdHis');
    }
}
