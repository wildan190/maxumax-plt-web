<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Preorder;
use App\Models\PreorderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Refund;
use Stripe\Stripe;

class ComplaintAdminController extends Controller
{
    /**
     * Display complaints list
     */
    public function index(Request $request)
    {
        $query = Complaint::with('preorder.product', 'approver');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $complaints = $query->latest()->paginate(20);

        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Show complaint details
     */
    public function show(Complaint $complaint)
    {
        $complaint->load('preorder.product', 'preorder.histories', 'approver');

        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Approve and process complaint
     */
    public function approve(Request $request, Complaint $complaint)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if (!$complaint->canBeApproved()) {
            return back()->with('error', 'This complaint cannot be approved.');
        }

        DB::beginTransaction();
        try {
            if ($complaint->type === 'refund') {
                // Process refund
                $this->processRefund($complaint);
            } else {
                // Process replacement
                $this->processReplacement($complaint);
            }

            $complaint->update([
                'status' => 'approved',
                'return_status' => $complaint->type === 'replacement' ? 'waiting_return' : 'pending',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'admin_notes' => $request->admin_notes,
            ]);

            DB::commit();

            return redirect()->route('admin.complaints.show', $complaint)
                ->with('success', 'Complaint approved and processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing complaint: ' . $e->getMessage());
        }
    }

    /**
     * Reject complaint
     */
    public function reject(Request $request, Complaint $complaint)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($complaint->status !== 'pending') {
            return back()->with('error', 'Only pending complaints can be rejected.');
        }

        $complaint->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        PreorderHistory::create([
            'preorder_id' => $complaint->preorder_id,
            'old_status' => $complaint->preorder->status,
            'new_status' => $complaint->preorder->status,
            'note' => 'Complaint rejected: ' . $request->rejection_reason,
        ]);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint rejected.');
    }

    /**
     * Confirm that the return shipment has been received
     */
    public function confirmReturn(Request $request, Complaint $complaint)
    {
        if (!$complaint->canConfirmReturn()) {
            return back()->with('error', 'Return receipt cannot be confirmed for this complaint.');
        }

        DB::beginTransaction();
        try {
            // Update complaint
            $complaint->update([
                'return_status' => 'received',
                'return_received_at' => now(),
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Activate the replacement order (change from pending to confirmed)
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

            return back()->with('success', 'Return receipt confirmed. Replacement order is now active.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error confirming return: ' . $e->getMessage());
        }
    }

    /**
     * Process refund via Stripe
     */
    private function processRefund(Complaint $complaint)
    {
        $preorder = $complaint->preorder;

        if (!$preorder->stripe_payment_intent_id) {
            throw new \Exception('No Stripe payment found for this order.');
        }

        // Initialize Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        // Convert amount to cents
        $amountInCents = (int) round($complaint->refund_amount * 100);

        // Create refund
        $refund = Refund::create([
            'payment_intent' => $preorder->stripe_payment_intent_id,
            'amount' => $amountInCents,
            'metadata' => [
                'complaint_id' => $complaint->id,
                'reason' => $complaint->reason,
            ],
        ]);

        // Update preorder
        $preorder->update([
            'status' => 'refunded',
            'refund_status' => 'completed',
            'stripe_refund_id' => $refund->id,
        ]);

        // Add history
        PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $preorder->getOriginal('status'),
            'new_status' => 'refunded',
            'note' => 'Refund processed via complaint system. Refund ID: ' . $refund->id,
        ]);
    }

    /**
     * Process replacement order
     */
    private function processReplacement(Complaint $complaint)
    {
        $originalOrder = $complaint->preorder;

        // Create a new preorder as replacement
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
            'total_amount' => 0, // No charge for replacement
            'currency' => $originalOrder->currency,
            'status' => 'pending', // Pending return receipt
            'notes' => 'Replacement order (Pending Return) for: ' . $originalOrder->order_number,
            'items' => $originalOrder->items,
        ]);

        // Add history to replacement
        PreorderHistory::create([
            'preorder_id' => $replacementOrder->id,
            'old_status' => null,
            'new_status' => 'pending',
            'note' => 'Replacement order created (Waiting for Return) for complaint ID: ' . $complaint->id,
        ]);

        // Update original order
        $originalOrder->update(['status' => 'replaced']);

        // Add history to original
        PreorderHistory::create([
            'preorder_id' => $originalOrder->id,
            'old_status' => $originalOrder->getOriginal('status'),
            'new_status' => 'replaced',
            'note' => 'Order replaced with: ' . $replacementOrder->order_number,
        ]);

        // Update complaint with replacement info
        $complaint->update([
            'replacement_order_number' => $replacementOrder->order_number,
        ]);
    }

    /**
     * Generate replacement order number
     */
    private function generateReplacementOrderNumber($originalOrder)
    {
        return $originalOrder->order_number . '-R' . now()->format('ymdHis');
    }
}
