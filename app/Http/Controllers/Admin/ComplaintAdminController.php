<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Services\Complaint\ComplaintAdminWorkflowService;
use Illuminate\Http\Request;

class ComplaintAdminController extends Controller
{
    public function index(Request $request, ComplaintAdminWorkflowService $workflow)
    {
        $complaints = $workflow->paginatedIndex($request);

        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('preorder.product', 'preorder.histories', 'approver');

        return view('admin.complaints.show', compact('complaint'));
    }

    public function approve(Request $request, Complaint $complaint, ComplaintAdminWorkflowService $workflow)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if (!$complaint->canBeApproved()) {
            return back()->with('error', 'This complaint cannot be approved.');
        }

        try {
            $workflow->approveComplaint($request, $complaint);
        } catch (\Throwable $e) {
            return back()->with('error', 'Error processing complaint: ' . $e->getMessage());
        }

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Complaint approved and processed successfully.');
    }

    public function reject(Request $request, Complaint $complaint, ComplaintAdminWorkflowService $workflow)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($complaint->status !== 'pending') {
            return back()->with('error', 'Only pending complaints can be rejected.');
        }

        $workflow->rejectComplaint($request, $complaint);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint rejected.');
    }

    public function confirmReturn(Request $request, Complaint $complaint, ComplaintAdminWorkflowService $workflow)
    {
        if (!$complaint->canConfirmReturn()) {
            return back()->with('error', 'Return receipt cannot be confirmed for this complaint.');
        }

        try {
            $workflow->confirmReturnShipment($complaint);
        } catch (\Throwable $e) {
            return back()->with('error', 'Error confirming return: ' . $e->getMessage());
        }

        return back()->with('success', 'Return receipt confirmed. Replacement order is now active.');
    }
}
