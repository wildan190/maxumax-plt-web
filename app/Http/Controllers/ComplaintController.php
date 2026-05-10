<?php

namespace App\Http\Controllers;

use App\Http\Requests\Complaint\StoreComplaintRequest;
use App\Models\Complaint;
use App\Models\Preorder;
use App\Services\Complaint\CustomerComplaintService;

class ComplaintController extends Controller
{
    public function create(string $preorder, CustomerComplaintService $complaints)
    {
        $preorderModel = $complaints->findPreorderForComplaint($preorder);

        $deliveryTime = $preorderModel->getDeliveryTimestamp();
        if (!$deliveryTime) {
            return back()->with('error', 'You can only file a complaint after the order has been delivered.');
        }

        $expiresAt = $complaints->expiresAtAfterDelivery($preorderModel);
        if ($expiresAt && now()->isAfter($expiresAt)) {
            return back()->with('error', 'The complaint window has expired. You must file within 7 days of delivery.');
        }

        $existingComplaint = $complaints->activeComplaintBlocking($preorderModel);
        if ($existingComplaint) {
            return redirect()->route('complaints.show', $existingComplaint)
                ->with('info', 'You already have an active complaint for this order.');
        }

        return view('complaints.create', [
            'preorder' => $preorderModel,
            'expiresAt' => $expiresAt,
        ]);
    }

    public function store(StoreComplaintRequest $request, CustomerComplaintService $complaints)
    {
        $preorder = Preorder::with('histories')->findOrFail($request->validated('preorder_id'));

        if ($blocking = $complaints->activeComplaintBlocking($preorder)) {
            return redirect()->route('complaints.show', $blocking)
                ->with('info', 'You already have an active complaint.');
        }

        $complaint = $complaints->createComplaint($preorder, [
            'type' => $request->validated('type'),
            'reason' => $request->validated('reason'),
        ]);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Your complaint has been submitted successfully. We will review it shortly.');
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('preorder.product', 'approver');

        return view('complaints.show', compact('complaint'));
    }

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
