<?php

namespace App\Http\Controllers;

use App\Http\Requests\Preorder\CheckoutCodRequest;
use App\Http\Requests\Preorder\StorePreorderRequest;
use App\Models\Preorder;
use App\Models\Product;
use App\Services\CurrencyService;
use App\Services\EasyParcelService;
use App\Services\Storefront\CatalogLandingService;
use App\Services\Storefront\PreorderStorefrontFlowService;
use Illuminate\Http\Request;

class PreorderController extends Controller
{
    public function create(Request $request, Product $product, CurrencyService $currencyService)
    {
        if (!($product->is_active || $product->available_for_preorder)) {
            abort(404);
        }

        $product->load('variants');
        $currency = $currencyService->resolveCurrency($request);
        $currencyConfig = $currencyService->getCurrencyConfig($currency);

        return view('preorder.create', compact('product', 'currency', 'currencyConfig'));
    }

    public function store(StorePreorderRequest $request, PreorderStorefrontFlowService $flow)
    {
        return $flow->submitProductOrderForm($request);
    }

    public function thankyou(string $uuid, PreorderStorefrontFlowService $flow)
    {
        return $flow->thankYouView($uuid);
    }

    public function track(Request $request, EasyParcelService $easyParcel, PreorderStorefrontFlowService $flow)
    {
        return $flow->trackOrderView($request, $easyParcel);
    }

    public function showProducts(Request $request, PreorderStorefrontFlowService $flow)
    {
        return $flow->retailProductCatalogView($request);
    }

    public function showProduct(Request $request, Product $product, PreorderStorefrontFlowService $flow)
    {
        return $flow->retailProductDetailView($request, $product);
    }

    public function setCurrency(Request $request, PreorderStorefrontFlowService $flow)
    {
        return $flow->setCurrencyJson($request);
    }

    public function cartAdd(Request $request, PreorderStorefrontFlowService $flow)
    {
        return $flow->cartAddLine($request);
    }

    public function cartShow(Request $request, PreorderStorefrontFlowService $flow)
    {
        return $flow->cartOverviewView($request);
    }

    public function cartUpdate(Request $request, PreorderStorefrontFlowService $flow)
    {
        return $flow->cartUpdateQuantities($request);
    }

    public function cartRemove(Request $request, PreorderStorefrontFlowService $flow)
    {
        return $flow->cartRemoveLine($request);
    }

    public function checkoutCod(CheckoutCodRequest $request, PreorderStorefrontFlowService $flow)
    {
        return $flow->checkoutCodView($request);
    }

    public function markDelivered(Request $request, Preorder $order, PreorderStorefrontFlowService $flow)
    {
        return $flow->customerMarkDelivered($order);
    }

    public function requestRefund(Request $request, Preorder $order, PreorderStorefrontFlowService $flow)
    {
        return $flow->customerRequestRefund($request, $order);
    }
}
