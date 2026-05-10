<?php

namespace App\Services\Payment;

use App\Http\Requests\Payment\CreateCheckoutSessionRequest;
use App\Http\Requests\Preorder\StorePreorderRequest;
use App\Models\Preorder;
use App\Models\Product;
use App\Services\CurrencyService;
use App\Services\OrderService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCheckoutApplicationService
{
    public function __construct(
        protected OrderService $orderService,
        protected CurrencyService $currencyService,
        protected StripeService $stripeService,
    ) {}

    public function createCartCheckout(CreateCheckoutSessionRequest $request)
    {
        $data = $request->validated();
        $data['address'] = $this->orderService->formatAddress($data);

        $cart = session()->get('cart', []);
        if ($cart === []) {
            return back()->withErrors(['cart' => 'Cart kosong']);
        }

        $currency = $data['currency'] ?? session()->get('currency', 'MYR');
        $config = $this->currencyService->getCurrencyConfig($currency);

        [$lineItems, $orderItems, $totalAmount] = $this->stripeService->prepareCartLineItems($cart, $currency, $config);

        $shippingCost = (float) ($data['shipping_cost'] ?? 0);
        if ($shippingCost > 0) {
            $totalAmount += $shippingCost;
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => ['name' => 'Shipping - ' . ($data['shipping_courier_name'] ?? 'Courier')],
                    'unit_amount' => $this->currencyService->convertToCents($shippingCost, $currency),
                ],
                'quantity' => 1,
            ];
        }

        if ($lineItems === []) {
            return back()->withErrors(['cart' => 'Tidak ada produk valid di cart']);
        }

        try {
            $checkoutSession = $this->stripeService->createSession(
                $lineItems,
                array_merge($data, ['currency' => $currency]),
                route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                route('payment.cancel'),
                $data['email'] ?? null
            );

            session()->put('stripe_checkout', [
                'session_id' => $checkoutSession->id,
                'order_data' => $data,
                'order_items' => $orderItems,
                'currency' => $currency,
                'total_amount' => $totalAmount,
                'shipping_data' => [
                    'shipping_cost' => $shippingCost,
                    'shipping_courier_name' => $data['shipping_courier_name'] ?? null,
                    'shipping_courier_logo' => $data['shipping_courier_logo'] ?? null,
                    'shipping_service_name' => $data['shipping_service_name'] ?? null,
                    'shipping_service_id' => $data['shipping_service_id'] ?? null,
                    'shipping_source' => $data['shipping_source'] ?? null,
                ],
            ]);

            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            return back()->withErrors(['stripe' => 'Error creating payment session: ' . $e->getMessage()]);
        }
    }

    public function handleCartPaymentSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('cart.show')->withErrors(['payment' => 'Invalid payment session']);
        }

        $existingOrder = Preorder::where('stripe_session_id', $sessionId)->first();
        if ($existingOrder) {
            session()->forget(['cart', 'stripe_checkout']);
            $orders = Preorder::where('stripe_session_id', $sessionId)->get();

            return view('cart.thankyou', ['orders' => $orders, 'currency' => $existingOrder->currency]);
        }

        try {
            $checkoutSession = $this->stripeService->retrieveSession($sessionId);
            if ($checkoutSession->payment_status !== 'paid') {
                return redirect()->route('cart.show')->withErrors(['payment' => 'Payment not completed']);
            }

            $checkoutData = session()->get('stripe_checkout');
            if (!$checkoutData || $checkoutData['session_id'] !== $sessionId) {
                $this->stripeService->refundPayment($checkoutSession->payment_intent);

                return redirect()->route('cart.show')->withErrors(['payment' => 'Session expired or invalid. Payment refunded automatically.']);
            }

            $orders = $this->orderService->createOrdersFromStripe(
                $checkoutData,
                $sessionId,
                $checkoutSession->payment_intent ?? null
            );

            session()->forget(['cart', 'stripe_checkout']);

            return view('cart.thankyou', ['orders' => collect($orders), 'currency' => $checkoutData['currency']]);
        } catch (\Exception $e) {
            Log::error('Payment success handling failed: ' . $e->getMessage());

            return redirect()->route('cart.show')->withErrors(['payment' => 'Error verifying payment: ' . $e->getMessage()]);
        }
    }

    public function cartPaymentCancelledRedirect()
    {
        session()->forget('stripe_checkout');

        return redirect()->route('cart.show')->with('error', 'Payment was cancelled');
    }

    public function createSinglePreorderStripeSession(StorePreorderRequest $request)
    {
        $data = $request->validated();
        $data['address'] = $this->orderService->formatAddress($data);

        $product = Product::findOrFail($data['product_id']);
        if (!$product->is_active && !$product->available_for_preorder) {
            abort(404);
        }

        $itemsData = array_filter($data['items'] ?? [], fn ($item) => ($item['quantity_ss'] ?? 0) > 0 || ($item['quantity_ls'] ?? 0) > 0);
        if ($itemsData === []) {
            return back()->withErrors(['items' => 'Please select at least one item quantity.'])->withInput();
        }

        $currency = $data['currency'] ?? 'MYR';
        $config = $this->currencyService->getCurrencyConfig($currency);

        [$lineItems, $orderItems, $totalAmount, $allCustomFields] = $this->stripeService->preparePreorderLineItems($product, $itemsData, $currency, $config);

        $shippingCost = (float) ($data['shipping_cost'] ?? 0) * $config['rate'];
        $totalAmount += $shippingCost;
        if ($shippingCost > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => ['name' => 'Shipping Fee'],
                    'unit_amount' => $this->currencyService->convertToCents($shippingCost, $currency),
                ],
                'quantity' => 1,
            ];
        }

        $data['custom_fields'] = $allCustomFields;

        try {
            $checkoutSession = $this->stripeService->createSession(
                $lineItems,
                ['name' => $data['name'], 'product_id' => $product->id],
                route('payment.preorder.success') . '?session_id={CHECKOUT_SESSION_ID}',
                route('payment.preorder.cancel'),
                $data['email'] ?? null
            );

            session()->put('stripe_preorder_checkout', [
                'session_id' => $checkoutSession->id,
                'order_data' => $data,
                'order_items' => $orderItems,
                'product_id' => $product->id,
                'currency' => $currency,
                'total_amount' => $totalAmount,
                'shipping_data' => [
                    'shipping_cost' => $shippingCost,
                    'shipping_courier_name' => $data['shipping_courier_name'] ?? null,
                    'shipping_service_id' => $data['shipping_service_id'] ?? null,
                    'shipping_source' => $data['shipping_source'] ?? null,
                ],
            ]);

            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            return back()->withErrors(['stripe' => $e->getMessage()])->withInput();
        }
    }

    public function handlePreorderStripeSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('preorder.landing')->withErrors(['payment' => 'Invalid payment session']);
        }

        $existingOrder = Preorder::where('stripe_session_id', $sessionId)->first();
        if ($existingOrder) {
            session()->forget('stripe_preorder_checkout');

            return redirect()->route(
                $existingOrder->product->available_for_preorder ? 'preorder.thankyou' : 'order.thankyou',
                ['uuid' => $existingOrder->uuid]
            );
        }

        try {
            $checkoutSession = $this->stripeService->retrieveSession($sessionId);
            if ($checkoutSession->payment_status !== 'paid') {
                return redirect()->route('preorder.landing')->withErrors(['payment' => 'Payment not completed']);
            }

            $checkoutData = session()->get('stripe_preorder_checkout');
            if (!$checkoutData || $checkoutData['session_id'] !== $sessionId) {
                $this->stripeService->refundPayment($checkoutSession->payment_intent);

                return redirect()->route('preorder.landing')->withErrors(['payment' => 'Session expired or invalid. Payment refunded automatically.']);
            }

            $orders = $this->orderService->createOrdersFromStripe(
                $checkoutData,
                $sessionId,
                $checkoutSession->payment_intent ?? null
            );

            session()->forget('stripe_preorder_checkout');
            $firstOrder = $orders[0];

            return redirect()->route(
                $firstOrder->product->available_for_preorder ? 'preorder.thankyou' : 'order.thankyou',
                ['uuid' => $firstOrder->uuid]
            );
        } catch (\Exception $e) {
            Log::error('Preorder success handling failed: ' . $e->getMessage());

            return redirect()->route('preorder.landing')->withErrors(['payment' => 'Error verifying payment: ' . $e->getMessage()]);
        }
    }

    public function preorderStripeCancelledRedirect()
    {
        session()->forget('stripe_preorder_checkout');

        return redirect()->route('preorder.landing')->with('error', 'Payment was cancelled');
    }
}
