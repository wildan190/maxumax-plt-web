<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = App\Models\Product::where('is_active', true)->where('available_for_preorder', true)->get();
    $highlightedGallery = App\Models\Gallery::where('is_highlight', true)->latest()->take(6)->get();

    // Get currency from session or default
    $currency = session('currency', 'MYR');
    $currencyConfig = config("currencies.{$currency}", config('currencies.MYR'));

    return view('home', compact('products', 'highlightedGallery', 'currency', 'currencyConfig'));
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin preorder management
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/preorders', [App\Http\Controllers\PreorderAdminController::class, 'index'])->name('admin.preorders.index');
    Route::get('/preorders/{preorder}', [App\Http\Controllers\PreorderAdminController::class, 'show'])->name('admin.preorders.show');
    Route::post('/preorders/{preorder}/confirm', [App\Http\Controllers\PreorderAdminController::class, 'confirm'])->name('admin.preorders.confirm');
    Route::post('/preorders/{preorder}/mark-paid', [App\Http\Controllers\PreorderAdminController::class, 'markPaid'])->name('admin.preorders.markPaid');
    Route::post('/preorders/{preorder}/mark-packing', [App\Http\Controllers\PreorderAdminController::class, 'markPacking'])->name('admin.preorders.markPacking');
    Route::post('/preorders/{preorder}/mark-shipped', [App\Http\Controllers\PreorderAdminController::class, 'markShipped'])->name('admin.preorders.markShipped');
    Route::post('/preorders/{preorder}/mark-delivered', [App\Http\Controllers\PreorderAdminController::class, 'markDelivered'])->name('admin.preorders.markDelivered');
    Route::post('/preorders/{preorder}/request-refund', [App\Http\Controllers\PreorderAdminController::class, 'requestRefund'])->name('admin.preorders.requestRefund');
    Route::post('/preorders/{preorder}/approve-refund', [App\Http\Controllers\PreorderAdminController::class, 'approveRefund'])->name('admin.preorders.approveRefund');
    Route::post('/preorders/{preorder}/reject-refund', [App\Http\Controllers\PreorderAdminController::class, 'rejectRefund'])->name('admin.preorders.rejectRefund');
    Route::delete('/preorders/{preorder}', [App\Http\Controllers\PreorderAdminController::class, 'destroy'])->name('admin.preorders.destroy');
    Route::get('/preorders/export/csv', [App\Http\Controllers\PreorderAdminController::class, 'exportCsv'])->name('admin.preorders.export');

    // Orders history (all orders including preorders) - must be before /orders/{order}
    Route::get('/orders/history', [App\Http\Controllers\PreorderAdminController::class, 'history'])->name('admin.orders.history');

    // Admin order management (product orders, not preorders)
    Route::get('/orders', [App\Http\Controllers\OrderAdminController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/export/csv', [App\Http\Controllers\OrderAdminController::class, 'exportCsv'])->name('admin.orders.export');
    Route::get('/orders/{order}', [App\Http\Controllers\OrderAdminController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{order}/confirm', [App\Http\Controllers\OrderAdminController::class, 'confirm'])->name('admin.orders.confirm');
    Route::post('/orders/{order}/mark-paid', [App\Http\Controllers\OrderAdminController::class, 'markPaid'])->name('admin.orders.markPaid');
    Route::post('/orders/{order}/mark-packing', [App\Http\Controllers\OrderAdminController::class, 'markPacking'])->name('admin.orders.markPacking');
    Route::post('/orders/{order}/mark-shipped', [App\Http\Controllers\OrderAdminController::class, 'markShipped'])->name('admin.orders.markShipped');
    Route::post('/orders/{order}/mark-delivered', [App\Http\Controllers\OrderAdminController::class, 'markDelivered'])->name('admin.orders.markDelivered');
    Route::post('/orders/{order}/request-refund', [App\Http\Controllers\OrderAdminController::class, 'requestRefund'])->name('admin.orders.requestRefund');
    Route::post('/orders/{order}/approve-refund', [App\Http\Controllers\OrderAdminController::class, 'approveRefund'])->name('admin.orders.approveRefund');
    Route::post('/orders/{order}/reject-refund', [App\Http\Controllers\OrderAdminController::class, 'rejectRefund'])->name('admin.orders.rejectRefund');
    Route::delete('/orders/{order}', [App\Http\Controllers\OrderAdminController::class, 'destroy'])->name('admin.orders.destroy');

    // Product management
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    // Reports
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/export', [App\Http\Controllers\ReportController::class, 'export'])->name('admin.reports.export');

    // Gallery
    Route::resource('galleries', App\Http\Controllers\GalleryAdminController::class)->names('admin.galleries');

    // User management
    Route::resource('users', App\Http\Controllers\Admin\UserAdminController::class)->names('admin.users');

    // Admin Complaint Routes
    Route::prefix('complaints')->name('admin.complaints.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ComplaintAdminController::class, 'index'])->name('index');
        Route::get('/{complaint}', [App\Http\Controllers\Admin\ComplaintAdminController::class, 'show'])->name('show');
        Route::post('/{complaint}/approve', [App\Http\Controllers\Admin\ComplaintAdminController::class, 'approve'])->name('approve');
        Route::post('/{complaint}/reject', [App\Http\Controllers\Admin\ComplaintAdminController::class, 'reject'])->name('reject');
        Route::post('/{complaint}/confirm-return', [App\Http\Controllers\Admin\ComplaintAdminController::class, 'confirmReturn'])->name('confirm-return');
    });
});

// Public Gallery
Route::get('/gallery', [App\Http\Controllers\PageController::class, 'gallery'])->name('gallery.index');

// Preorder landing (supports optional subdomain via PREORDER_DOMAIN env)
Route::group(['domain' => env('PREORDER_DOMAIN', null)], function () {
    // If PREORDER_DOMAIN is not set, this group still works for the default domain.
    Route::get('/preorder', [PreorderController::class, 'showLanding'])->name('preorder.landing');
    Route::get('/preorder/create/{product}', [PreorderController::class, 'create'])->name('preorder.create');
    Route::post('/preorder', [PreorderController::class, 'store'])->name('preorder.store');
    Route::get('/preorder/thank-you/{uuid}', [PreorderController::class, 'thankyou'])->name('preorder.thankyou');
    Route::get('/order/create/{product}', [PreorderController::class, 'create'])->name('order.create');
    Route::post('/order', [PreorderController::class, 'store'])->name('order.store');
    Route::get('/order/thank-you/{uuid}', [PreorderController::class, 'thankyou'])->name('order.thankyou');
});

// Fallback registration if domain not configured (simple routes)
Route::get('/preorder', [PreorderController::class, 'showLanding'])->name('preorder.landing');
Route::get('/preorder/create/{product}', [PreorderController::class, 'create'])->name('preorder.create');
Route::post('/preorder', [PreorderController::class, 'store'])->name('preorder.store');
Route::get('/preorder/thank-you/{uuid}', [PreorderController::class, 'thankyou'])->name('preorder.thankyou');
Route::get('/order/create/{product}', [PreorderController::class, 'create'])->name('order.create');
Route::post('/order', [PreorderController::class, 'store'])->name('order.store');
Route::get('/order/thank-you/{uuid}', [PreorderController::class, 'thankyou'])->name('order.thankyou');

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::get('/order/track', [PreorderController::class, 'track'])->name('order.track');
Route::post('/preorder/{order}/mark-delivered', [PreorderController::class, 'markDelivered'])->name('preorder.markDelivered');
Route::post('/preorder/{order}/request-refund', [PreorderController::class, 'requestRefund'])->name('preorder.requestRefund');
Route::get('/products', [PreorderController::class, 'showProducts'])->name('products.index');
Route::get('/product/{product}', [PreorderController::class, 'showProduct'])->name('product.show');

Route::get('/cart', [PreorderController::class, 'cartShow'])->name('cart.show');
Route::post('/cart/add', [PreorderController::class, 'cartAdd'])->name('cart.add');
Route::post('/cart/update', [PreorderController::class, 'cartUpdate'])->name('cart.update');
Route::post('/cart/remove', [PreorderController::class, 'cartRemove'])->name('cart.remove');
Route::post('/checkout/cod', [PreorderController::class, 'checkoutCod'])->name('checkout.cod');
Route::post('/checkout/stripe', [PaymentController::class, 'createCheckoutSession'])->name('checkout.stripe');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/preorder/checkout/stripe', [PaymentController::class, 'createPreorderCheckoutSession'])->name('preorder.checkout.stripe');
Route::get('/payment/preorder/success', [PaymentController::class, 'preorderSuccess'])->name('payment.preorder.success');
Route::get('/payment/preorder/cancel', [PaymentController::class, 'preorderCancel'])->name('payment.preorder.cancel');
Route::post('/currency/set', [PreorderController::class, 'setCurrency'])->name('currency.set');

// Customer Complaint Routes
Route::prefix('complaints')->name('complaints.')->group(function () {
    Route::get('/create/{preorder}', [App\Http\Controllers\ComplaintController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\ComplaintController::class, 'store'])->name('store');
    Route::get('/{complaint}', [App\Http\Controllers\ComplaintController::class, 'show'])->name('show');
    Route::post('/{complaint}/cancel', [App\Http\Controllers\ComplaintController::class, 'cancel'])->name('cancel');
});