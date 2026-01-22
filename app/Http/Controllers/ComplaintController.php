<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Preorder;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Show the complaint submission form
     */
    public function create($preorder)
    {
        $preorder = Preorder::with(['product', 'histories', 'complaints'])
            ->where('order_number', $preorder)
            ->orWhere('id', $preorder)
            ->orWhere('uuid', $preorder)
            ->firstOrFail();

        // Check if order is delivered
        $deliveryTime = $preorder->getDeliveryTimestamp();

        if (!$deliveryTime) {
            return back()->with('error', 'You can only file a complaint after the order has been delivered.');
        }

        // Calculate expiration (7 days from delivery)
        $expiresAt = \Carbon\Carbon::parse($deliveryTime)->addDays(7);

        if (now()->isAfter($expiresAt)) {
            return back()->with('error', 'The complaint window has expired. You must file within 7 days of delivery.');
        }

        // Check if there's already a pending complaint
        $existingComplaint = $preorder->complaints()->whereIn('status', ['pending', 'approved'])->first();
        if ($existingComplaint) {
            return redirect()->route('complaints.show', $existingComplaint)
                ->with('info', 'You already have an active complaint for this order.');
        }

        return view('complaints.create', compact('preorder', 'expiresAt'));
    }

    /**
     * Store a new complaint
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'preorder_id' => 'required|exists:preorders,id',
            'type' => 'required|in:refund,replacement',
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $preorder = Preorder::with('histories')->findOrFail($data['preorder_id']);

        // Verify delivery and expiration
        $deliveryTime = $preorder->getDeliveryTimestamp();

        if (!$deliveryTime) {
            return back()->withErrors(['delivery' => 'Order must be delivered before filing a complaint.'])->withInput();
        }

        $expiresAt = \Carbon\Carbon::parse($deliveryTime)->addDays(7);

        if (now()->isAfter($expiresAt)) {
            return back()->withErrors(['expired' => 'Complaint window has expired.'])->withInput();
        }

        // Check for existing complaint
        $existing = $preorder->complaints()->whereIn('status', ['pending', 'approved'])->first();
        if ($existing) {
            return redirect()->route('complaints.show', $existing)
                ->with('info', 'You already have an active complaint.');
        }

        // Create complaint
        $complaint = Complaint::create([
            'preorder_id' => $preorder->id,
            'type' => $data['type'],
            'reason' => $data['reason'],
            'status' => 'pending',
            'refund_amount' => $data['type'] === 'refund' ? $preorder->total_amount : null,
            'expires_at' => $expiresAt,
        ]);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Your complaint has been submitted successfully. We will review it shortly.');
    }

    /**
     * Show complaint status
     */
    public function show(Complaint $complaint)
    {
        $complaint->load('preorder.product', 'approver');

        return view('complaints.show', compact('complaint'));
    }

    /**
     * Cancel a pending complaint
     */
    public function cancel(Complaint $complaint)
    {
        if (!$complaint->canBeCancelled()) {
            return back()->with('error', 'This complaint cannot be cancelled.');
        }

        $complaint->update(['status' => 'rejected', 'rejection_reason' => 'Cancelled by customer']);

        return redirect()->route('order.track', ['order' => $complaint->preorder->order_number])
            ->with('success', 'Complaint has been cancelled.');
    }
}
