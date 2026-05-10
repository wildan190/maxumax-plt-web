<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Repositories\Preorder\PreorderListingRepository;
use App\Services\Admin\PreorderCsvExportService;
use App\Services\Admin\PreorderFulfillmentAdminService;
use App\Services\Admin\PreorderShippingBackofficeService;
use App\Services\Admin\PreorderStripeRefundAdminService;
use App\Services\EasyParcelService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreorderAdminController extends Controller
{
    public function __construct(
        protected PreorderListingRepository $listing,
        protected PreorderFulfillmentAdminService $fulfillment,
        protected PreorderShippingBackofficeService $shippingActions,
        protected PreorderStripeRefundAdminService $refunds,
        protected PreorderCsvExportService $csv,
    ) {}

    public function index(Request $request)
    {
        $base = $this->listing->preorderOnlyQuery();
        $listing = $this->listing->paginateWithStatusCounts($base, $request, false);
        $preorders = $listing['records'];
        $counts = $listing['counts'];

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Preorders', 'url' => route('admin.preorders.index')]
        ));

        return view('admin.preorders.index', compact('preorders', 'counts'));
    }

    public function markPaid(Request $request, Preorder $preorder)
    {
        return $this->fulfillment->markPaidPreorderPipeline($preorder);
    }

    public function confirm(Request $request, Preorder $preorder)
    {
        return $this->fulfillment->confirm($preorder);
    }

    public function markPacking(Request $request, Preorder $preorder)
    {
        return $this->fulfillment->markPacking($request, $preorder);
    }

    public function markShipped(Request $request, Preorder $preorder)
    {
        return $this->fulfillment->markShipped($request, $preorder);
    }

    public function markDelivered(Request $request, Preorder $preorder)
    {
        return $this->fulfillment->markDelivered($request, $preorder);
    }

    public function destroy(Preorder $preorder)
    {
        return $this->fulfillment->destroy($preorder, 'Preorder deleted successfully');
    }

    public function show(Preorder $preorder)
    {
        $preorder->load('product', 'histories');

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Preorders', 'url' => route('admin.preorders.index')],
            ['label' => '#' . $preorder->order_number, 'url' => route('admin.preorders.show', $preorder)]
        ));

        return view('admin.preorders.show', compact('preorder'));
    }

    public function history(Request $request)
    {
        $data = $this->listing->paginateOrderHistory($request);
        $orders = $data['orders'];
        $type = $data['type'];
        $counts = $data['counts'];

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders History', 'url' => route('admin.orders.history')],
            ['label' => ucfirst($type), 'url' => route('admin.orders.history', ['type' => $type])]
        ));

        return view('admin.orders.history', compact('orders', 'type', 'counts'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        return $this->csv->streamPreorderOnly();
    }

    public function requestRefund(Request $request, Preorder $preorder)
    {
        return $this->refunds->requestRefund($request, $preorder, 'Preorder');
    }

    public function approveRefund(Request $request, Preorder $preorder)
    {
        return $this->refunds->approveRefund($request, $preorder, notifyCustomerEmail: true, labelEntity: 'Preorder');
    }

    public function rejectRefund(Request $request, Preorder $preorder)
    {
        return $this->refunds->rejectRefund($request, $preorder);
    }

    public function shipping(Preorder $preorder)
    {
        $rates = session('rates');

        return view('admin.preorders.shipping', compact('preorder', 'rates'));
    }

    public function checkRates(Request $request, Preorder $preorder)
    {
        return $this->shippingActions->checkRates($request, $preorder, 'admin.preorders.shipping');
    }

    public function bookShipping(Request $request, Preorder $preorder)
    {
        return $this->shippingActions->bookShipping($request, $preorder, 'admin.preorders.show');
    }

    public function refreshTracking(Preorder $preorder, EasyParcelService $easyParcel)
    {
        return $this->shippingActions->refreshTracking($preorder, $easyParcel, true);
    }
}
