<?php

namespace App\Services\Admin;

use App\Mail\PaymentSuccess;
use App\Models\Preorder;
use App\Repositories\Preorder\PreorderHistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PreorderFulfillmentAdminService
{
    public function __construct(
        protected PreorderStockLedgerService $stockLedger,
        protected PreorderHistoryRepository $history,
        protected PreorderAutoShipmentAfterPaidService $autoShipment,
    ) {}

    public function confirm(Preorder $preorder): \Illuminate\Http\RedirectResponse
    {
        $old = $preorder->status;

        if ($preorder->status === 'paid') {
            return back()->with('status', 'Order sudah paid');
        }

        if ($preorder->status === 'confirmed') {
            return back()->with('status', 'Order sudah dikonfirmasi');
        }

        $preorder->status = 'confirmed';
        $preorder->save();

        $this->history->add($preorder->id, $old, 'confirmed', 'Confirmed by admin');

        return back()->with('status', 'Order dikonfirmasi');
    }

    /**
     * Ready-stock admin: no payment email, no auto-booking.
     */
    public function markPaidRetail(Preorder $preorder): \Illuminate\Http\RedirectResponse
    {
        return $this->markPaidInternal($preorder, sendPaymentMail: false, tryAutoCourier: false);
    }

    /**
     * Preorder admin: payment success email + optional auto courier booking.
     */
    public function markPaidPreorderPipeline(Preorder $preorder): \Illuminate\Http\RedirectResponse
    {
        return $this->markPaidInternal($preorder, sendPaymentMail: true, tryAutoCourier: true);
    }

    protected function markPaidInternal(Preorder $preorder, bool $sendPaymentMail, bool $tryAutoCourier): \Illuminate\Http\RedirectResponse
    {
        if ($preorder->status !== 'confirmed') {
            return back()->with('error', 'Order harus dikonfirmasi admin terlebih dahulu sebelum ditandai sebagai paid');
        }

        $old = $preorder->status;
        $preorder->status = 'paid';
        $preorder->save();

        $note = 'Marked as paid by admin' . $this->stockLedger->decrementOnMarkPaid($preorder);

        $this->history->add($preorder->id, $old, 'paid', $note);

        if ($sendPaymentMail && $preorder->email) {
            Mail::to($preorder->email)->send(new PaymentSuccess($preorder));
        }

        $autoBookingMsg = $tryAutoCourier ? $this->autoShipment->run($preorder) : null;

        return back()->with('status', trim('Marked as paid' . ($autoBookingMsg ? ' — ' . $autoBookingMsg : '')));
    }

    public function markPacking(Request $request, Preorder $preorder): \Illuminate\Http\RedirectResponse
    {
        if (!in_array($preorder->status, ['confirmed', 'paid'])) {
            return back()->with('error', 'Order harus dalam status confirmed atau paid sebelum dipacking');
        }

        $oldShippingStatus = $preorder->shipping_status;
        $preorder->shipping_status = 'packing';
        $preorder->save();

        $this->history->add(
            $preorder->id,
            $preorder->status,
            $preorder->status,
            'Order sedang dipacking' . ($oldShippingStatus ? ' (dari: ' . $oldShippingStatus . ')' : ''),
        );

        return back()->with('status', 'Order ditandai sebagai packing');
    }

    public function markShipped(Request $request, Preorder $preorder): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        if ($preorder->shipping_status !== 'packing') {
            return back()->with('error', 'Order harus dalam status packing sebelum dikirim');
        }

        $preorder->shipping_status = 'shipped';
        $preorder->tracking_number = $request->input('tracking_number');
        $preorder->save();

        $this->history->add(
            $preorder->id,
            $preorder->status,
            $preorder->status,
            'Order telah dikirim. Nomor resi: ' . $preorder->tracking_number,
        );

        return back()->with('status', 'Order ditandai sebagai shipped dengan nomor resi: ' . $preorder->tracking_number);
    }

    public function markDelivered(Request $request, Preorder $preorder): \Illuminate\Http\RedirectResponse
    {
        if ($preorder->shipping_status !== 'shipped') {
            return back()->with('error', 'Order harus dalam status shipped sebelum ditandai sebagai delivered');
        }

        $preorder->shipping_status = 'delivered';
        $preorder->save();

        $this->history->add(
            $preorder->id,
            $preorder->status,
            $preorder->status,
            'Order telah diterima oleh customer',
        );

        return back()->with('status', 'Order ditandai sebagai delivered');
    }

    public function destroy(Preorder $preorder, string $successMessage = 'Order deleted successfully'): \Illuminate\Http\RedirectResponse
    {
        $this->history->add($preorder->id, $preorder->status, 'deleted', 'Deleted by admin');
        $preorder->delete();

        return back()->with('status', $successMessage);
    }
}
