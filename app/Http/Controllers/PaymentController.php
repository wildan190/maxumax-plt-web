<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\CreateCheckoutSessionRequest;
use App\Http\Requests\Preorder\StorePreorderRequest;
use App\Services\Payment\PaymentCheckoutApplicationService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createCheckoutSession(CreateCheckoutSessionRequest $request, PaymentCheckoutApplicationService $payments)
    {
        return $payments->createCartCheckout($request);
    }

    public function success(Request $request, PaymentCheckoutApplicationService $payments)
    {
        return $payments->handleCartPaymentSuccess($request);
    }

    public function cancel(PaymentCheckoutApplicationService $payments)
    {
        return $payments->cartPaymentCancelledRedirect();
    }

    public function createPreorderCheckoutSession(StorePreorderRequest $request, PaymentCheckoutApplicationService $payments)
    {
        return $payments->createSinglePreorderStripeSession($request);
    }

    public function preorderSuccess(Request $request, PaymentCheckoutApplicationService $payments)
    {
        return $payments->handlePreorderStripeSuccess($request);
    }

    public function preorderCancel(PaymentCheckoutApplicationService $payments)
    {
        return $payments->preorderStripeCancelledRedirect();
    }
}
