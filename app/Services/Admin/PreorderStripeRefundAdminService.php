<?php

namespace App\Services\Admin;

use App\Jobs\SendEmailJob;
use App\Mail\RefundApproved;
use App\Models\Preorder;
use App\Repositories\Preorder\PreorderHistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Refund;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;

class PreorderStripeRefundAdminService
{
    public function __construct(
        protected PreorderStockLedgerService $stockLedger,
        protected PreorderHistoryRepository $history,
    ) {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function convertToCents(float $amount, string $currency): int
    {
        if ($currency === 'IDR') {
            return (int) round($amount);
        }

        return (int) round($amount * 100);
    }

    public function requestRefund(Request $request, Preorder $preorder, string $labelEntity = 'Order'): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'refund_reason' => 'required|string|max:1000',
            'refund_amount' => 'nullable|numeric|min:0|max:' . $preorder->total_amount,
        ]);

        if (!$preorder->stripe_payment_intent_id) {
            return back()->with('error', $labelEntity . ' ini tidak menggunakan Stripe payment, tidak dapat direfund');
        }

        if ($preorder->refund_status && in_array($preorder->refund_status, ['pending', 'approved', 'completed'])) {
            return back()->with('error', 'Refund request sudah ada untuk ' . strtolower($labelEntity) . ' ini');
        }

        $refundAmount = $request->input('refund_amount', $preorder->total_amount);

        $preorder->refund_status = 'pending';
        $preorder->refund_amount = $refundAmount;
        $preorder->refund_reason = $request->input('refund_reason');
        $preorder->save();

        $this->history->add(
            $preorder->id,
            $preorder->status,
            $preorder->status,
            'Refund requested: ' . $request->input('refund_reason') . ' (Amount: ' . $preorder->currency . ' ' . number_format($refundAmount, 2) . ')',
        );

        return back()->with('status', 'Refund request telah dibuat, menunggu konfirmasi admin');
    }

    public function approveRefund(Request $request, Preorder $preorder, bool $notifyCustomerEmail, string $labelEntity = 'Order'): \Illuminate\Http\RedirectResponse
    {
        if ($preorder->refund_status !== 'pending') {
            return back()->with('error', 'Refund request tidak dalam status pending');
        }

        if (!$preorder->stripe_payment_intent_id) {
            return back()->with('error', $labelEntity . ' ini tidak memiliki Stripe payment intent ID');
        }

        try {
            DB::beginTransaction();

            $refundAmount = $this->convertToCents($preorder->refund_amount, $preorder->currency);

            $refund = Refund::create([
                'payment_intent' => $preorder->stripe_payment_intent_id,
                'amount' => $refundAmount,
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'order_number' => $preorder->order_number,
                    'refund_reason' => $preorder->refund_reason ?? 'Admin approved refund',
                ],
            ]);

            $preorder->refund_status = 'approved';
            $preorder->stripe_refund_id = $refund->id;
            $preorder->status = 'refunded';
            $preorder->save();

            $this->history->add(
                $preorder->id,
                'pending',
                'refunded',
                'Refund approved and processed via Stripe. Refund ID: ' . $refund->id . ' (Amount: ' . $preorder->currency . ' ' . number_format($preorder->refund_amount, 2) . ')',
            );

            $this->stockLedger->restoreAfterRefund($preorder);

            if ($notifyCustomerEmail && $preorder->email) {
                SendEmailJob::dispatch($preorder->email, new RefundApproved($preorder), 2);
            }

            DB::commit();

            return back()->with('status', 'Refund telah disetujui dan diproses melalui Stripe');
        } catch (ApiErrorException $e) {
            DB::rollBack();

            return back()->with('error', 'Error processing refund: ' . $e->getMessage());
        }
    }

    public function rejectRefund(Request $request, Preorder $preorder): \Illuminate\Http\RedirectResponse
    {
        if ($preorder->refund_status !== 'pending') {
            return back()->with('error', 'Refund request tidak dalam status pending');
        }

        $preorder->refund_status = 'rejected';
        $preorder->save();

        $this->history->add(
            $preorder->id,
            $preorder->status,
            $preorder->status,
            'Refund request rejected by admin',
        );

        return back()->with('status', 'Refund request telah ditolak');
    }
}
