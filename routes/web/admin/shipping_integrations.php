<?php

use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;

Route::get('/env/shipping', [ShippingController::class, 'envShippingCheck'])->name('admin.env.shipping');

Route::prefix('shipping/myparcel')->name('admin.shipping.myparcel.')->group(function () {
    Route::get('/', [ShippingController::class, 'myparcelDashboard'])->name('index');
    Route::get('/parcel-sizes', [ShippingController::class, 'myparcelParcelSizes'])->name('parcelSizes');
    Route::get('/content-types', [ShippingController::class, 'myparcelContentTypes'])->name('contentTypes');
    Route::post('/sdd-price', [ShippingController::class, 'myparcelSddPrice'])->name('sddPrice');
    Route::get('/cart-items', [ShippingController::class, 'myparcelCartItems'])->name('cartItems');
    Route::post('/checkout', [ShippingController::class, 'myparcelCheckout'])->name('checkout');
    Route::post('/create-shipment', [ShippingController::class, 'myparcelCreateShipment'])->name('createShipment');
    Route::get('/shipment-statuses', [ShippingController::class, 'myparcelShipmentStatuses'])->name('shipmentStatuses');
    Route::post('/trace', [ShippingController::class, 'myparcelTrace'])->name('trace');
    Route::get('/shipment-history', [ShippingController::class, 'myparcelShipmentHistory'])->name('shipmentHistory');
    Route::post('/consignment-note', [ShippingController::class, 'myparcelConsignmentNote'])->name('consignmentNote');
});
