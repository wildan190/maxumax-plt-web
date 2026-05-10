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

class OrderAdminController extends Controller
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
        $base = $this->listing->retailOrdersQuery();
        $listing = $this->listing->paginateWithStatusCounts($base, $request, true);
        $orders = $listing['records'];
        $counts = $listing['counts'];

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders', 'url' => route('admin.orders.index')]
        ));

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function printIndex(Request $request)
    {
        $orders = $this->listing->retailPrintList($request);

        return view('admin.orders.print', compact('orders'));
    }

    public function markPaid(Request $request, Preorder $order)
    {
        return $this->fulfillment->markPaidRetail($order);
    }

    public function confirm(Request $request, Preorder $order)
    {
        return $this->fulfillment->confirm($order);
    }

    public function markPacking(Request $request, Preorder $order)
    {
        return $this->fulfillment->markPacking($request, $order);
    }

    public function markShipped(Request $request, Preorder $order)
    {
        return $this->fulfillment->markShipped($request, $order);
    }

    public function markDelivered(Request $request, Preorder $order)
    {
        return $this->fulfillment->markDelivered($request, $order);
    }

    public function destroy(Preorder $order)
    {
        return $this->fulfillment->destroy($order, 'Order deleted successfully');
    }

    public function show(Preorder $order)
    {
        $order->load('product', 'histories');

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders', 'url' => route('admin.orders.index')],
            ['label' => '#' . $order->order_number, 'url' => route('admin.orders.show', $order)]
        ));

        return view('admin.orders.show', compact('order'));
    }

    public function printShow(Preorder $order)
    {
        $order->load('product', 'variant', 'histories');

        return view('admin.orders.print_show', compact('order'));
    }

    public function shipping(Preorder $order)
    {
        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders', 'url' => route('admin.orders.index')],
            ['label' => '#' . $order->order_number, 'url' => route('admin.orders.show', $order)],
            ['label' => 'Shipping', 'url' => route('admin.orders.shipping', $order)]
        ));

        return view('admin.orders.shipping', ['order' => $order, 'rates' => session('rates') ?? []]);
    }

    public function checkRates(Request $request, Preorder $order)
    {
        return $this->shippingActions->checkRates($request, $order, 'admin.orders.shipping');
    }

    public function bookShipping(Request $request, Preorder $order)
    {
        return $this->shippingActions->bookShipping($request, $order, 'admin.orders.show');
    }

    public function refreshTracking(Preorder $order, EasyParcelService $easyParcel)
    {
        return $this->shippingActions->refreshTracking($order, $easyParcel, false);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        return $this->csv->streamRetailOrders();
    }

    public function requestRefund(Request $request, Preorder $order)
    {
        return $this->refunds->requestRefund($request, $order, 'Order');
    }

    public function approveRefund(Request $request, Preorder $order)
    {
        return $this->refunds->approveRefund($request, $order, notifyCustomerEmail: false, labelEntity: 'Order');
    }

    public function rejectRefund(Request $request, Preorder $order)
    {
        return $this->refunds->rejectRefund($request, $order);
    }
}
