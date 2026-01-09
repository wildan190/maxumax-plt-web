<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'));

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
    Route::delete('/orders/{order}', [App\Http\Controllers\OrderAdminController::class, 'destroy'])->name('admin.orders.destroy');

    // Product management
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});

// Preorder landing (supports optional subdomain via PREORDER_DOMAIN env)
Route::group(['domain' => env('PREORDER_DOMAIN', null)], function () {
    // If PREORDER_DOMAIN is not set, this group still works for the default domain.
    Route::get('/preorder', [PreorderController::class, 'showLanding'])->name('preorder.landing');
    Route::get('/preorder/create/{product}', [PreorderController::class, 'create'])->name('preorder.create');
    Route::post('/preorder', [PreorderController::class, 'store'])->name('preorder.store');
    Route::get('/preorder/thank-you/{id}', [PreorderController::class, 'thankyou'])->name('preorder.thankyou');
    Route::get('/order/create/{product}', [PreorderController::class, 'create'])->name('order.create');
    Route::post('/order', [PreorderController::class, 'store'])->name('order.store');
    Route::get('/order/thank-you/{id}', [PreorderController::class, 'thankyou'])->name('order.thankyou');
});

// Fallback registration if domain not configured (simple routes)
Route::get('/preorder', [PreorderController::class, 'showLanding'])->name('preorder.landing');
Route::get('/preorder/create/{product}', [PreorderController::class, 'create'])->name('preorder.create');
Route::post('/preorder', [PreorderController::class, 'store'])->name('preorder.store');
Route::get('/preorder/thank-you/{id}', [PreorderController::class, 'thankyou'])->name('preorder.thankyou');
Route::get('/order/create/{product}', [PreorderController::class, 'create'])->name('order.create');
Route::post('/order', [PreorderController::class, 'store'])->name('order.store');
Route::get('/order/thank-you/{id}', [PreorderController::class, 'thankyou'])->name('order.thankyou');

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::get('/order/track', [PreorderController::class, 'track'])->name('order.track');
Route::get('/products', [PreorderController::class, 'showProducts'])->name('products.index');
Route::get('/product/{product}', [PreorderController::class, 'showProduct'])->name('product.show');

Route::get('/cart', [PreorderController::class, 'cartShow'])->name('cart.show');
Route::post('/cart/add', [PreorderController::class, 'cartAdd'])->name('cart.add');
Route::post('/cart/update', [PreorderController::class, 'cartUpdate'])->name('cart.update');
Route::post('/cart/remove', [PreorderController::class, 'cartRemove'])->name('cart.remove');
Route::post('/checkout/cod', [PreorderController::class, 'checkoutCod'])->name('checkout.cod');
Route::post('/currency/set', [PreorderController::class, 'setCurrency'])->name('currency.set');
Route::post('/currency/set', [PreorderController::class, 'setCurrency'])->name('currency.set');