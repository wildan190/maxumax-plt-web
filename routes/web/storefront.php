<?php

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PreorderController;
use Illuminate\Support\Facades\Route;

$preorderOrderWebRoutes = function () {
    Route::get('/preorder', [PreorderController::class, 'showLanding'])->name('preorder.landing');
    Route::get('/preorder/create/{product}', [PreorderController::class, 'create'])->name('preorder.create');
    Route::post('/preorder', [PreorderController::class, 'store'])->name('preorder.store');
    Route::get('/preorder/thank-you/{uuid}', [PreorderController::class, 'thankyou'])->name('preorder.thankyou');

    Route::get('/order/create/{product}', [PreorderController::class, 'create'])->name('order.create');
    Route::post('/order', [PreorderController::class, 'store'])->name('order.store');
    Route::get('/order/thank-you/{uuid}', [PreorderController::class, 'thankyou'])->name('order.thankyou');
};

$domain = env('PREORDER_DOMAIN');
if (is_string($domain) && trim($domain) !== '') {
    Route::domain($domain)->group($preorderOrderWebRoutes);
}
$preorderOrderWebRoutes();

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

Route::get('/order/track', [PreorderController::class, 'track'])->name('order.track');
Route::post('/preorder/{order}/mark-delivered', [PreorderController::class, 'markDelivered'])->name('preorder.markDelivered');
Route::post('/preorder/{order}/request-refund', [PreorderController::class, 'requestRefund'])->name('preorder.requestRefund');

Route::get('/products', [PreorderController::class, 'showProducts'])->name('products.index');
Route::get('/product/{product}', [PreorderController::class, 'showProduct'])->name('product.show');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [PreorderController::class, 'cartShow'])->name('show');
    Route::post('/add', [PreorderController::class, 'cartAdd'])->name('add');
    Route::post('/update', [PreorderController::class, 'cartUpdate'])->name('update');
    Route::post('/remove', [PreorderController::class, 'cartRemove'])->name('remove');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::post('/cod', [PreorderController::class, 'checkoutCod'])->name('cod');
    Route::post('/stripe', [PaymentController::class, 'createCheckoutSession'])->name('stripe');
});

Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('/preorder/success', [PaymentController::class, 'preorderSuccess'])->name('preorder.success');
    Route::get('/preorder/cancel', [PaymentController::class, 'preorderCancel'])->name('preorder.cancel');
});

Route::post('/preorder/checkout/stripe', [PaymentController::class, 'createPreorderCheckoutSession'])->name('preorder.checkout.stripe');

Route::post('/currency/set', [PreorderController::class, 'setCurrency'])->name('currency.set');

Route::prefix('complaints')->name('complaints.')->group(function () {
    Route::get('/create/{preorder}', [ComplaintController::class, 'create'])->name('create');
    Route::post('/', [ComplaintController::class, 'store'])->name('store');
    Route::get('{complaint}', [ComplaintController::class, 'show'])->name('show');
    Route::post('{complaint}/cancel', [ComplaintController::class, 'cancel'])->name('cancel');
});
