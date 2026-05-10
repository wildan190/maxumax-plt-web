<?php

namespace App\Http\Controllers;

use App\Services\Shipping\ShippingIntegrationHttpService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(
        protected ShippingIntegrationHttpService $shippingHttp,
    ) {}

    public function myparcelDashboard()
    {
        return $this->shippingHttp->myparcelDashboardView();
    }

    public function myparcelParcelSizes()
    {
        return $this->shippingHttp->myparcelParcelSizes();
    }

    public function myparcelContentTypes()
    {
        return $this->shippingHttp->myparcelContentTypes();
    }

    public function myparcelSddPrice(Request $request)
    {
        return $this->shippingHttp->myparcelSddPrice($request);
    }

    public function myparcelCartItems()
    {
        return $this->shippingHttp->myparcelCartItems();
    }

    public function myparcelCheckout(Request $request)
    {
        return $this->shippingHttp->myparcelCheckout($request);
    }

    public function myparcelShipmentStatuses()
    {
        return $this->shippingHttp->myparcelShipmentStatuses();
    }

    public function myparcelTrace(Request $request)
    {
        return $this->shippingHttp->myparcelTrace($request);
    }

    public function myparcelShipmentHistory(Request $request)
    {
        return $this->shippingHttp->myparcelShipmentHistory($request);
    }

    public function myparcelConsignmentNote(Request $request)
    {
        return $this->shippingHttp->myparcelConsignmentNote($request);
    }

    public function myparcelCreateShipment(Request $request)
    {
        return $this->shippingHttp->myparcelCreateShipment($request);
    }

    public function checkRates(Request $request)
    {
        return $this->shippingHttp->checkRates($request);
    }

    public function envShippingCheck()
    {
        return $this->shippingHttp->envShippingCheck();
    }
}
