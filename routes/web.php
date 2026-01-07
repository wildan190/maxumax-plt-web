<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FeedbackController;
use App\Models\Feedback;

Route::get('/', function () {
    $avg = round((float) Feedback::avg('rating'), 2);
    $count = (int) Feedback::count();
    $latest = Feedback::orderByDesc('created_at')->limit(6)->get();
    return view('home', [
        'feedbackAvg' => $avg,
        'feedbackCount' => $count,
        'latestFeedback' => $latest,
    ]);
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
    Route::post('/preorders/{id}/confirm', [App\Http\Controllers\PreorderAdminController::class, 'confirm'])->name('admin.preorders.confirm');
    Route::post('/preorders/{id}/mark-paid', [App\Http\Controllers\PreorderAdminController::class, 'markPaid'])->name('admin.preorders.markPaid');
    Route::delete('/preorders/{id}', [App\Http\Controllers\PreorderAdminController::class, 'destroy'])->name('admin.preorders.destroy');
    Route::get('/preorders/export/csv', [App\Http\Controllers\PreorderAdminController::class, 'exportCsv'])->name('admin.preorders.export');

    // Product management
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    // Orders history
    Route::get('/orders/history', [App\Http\Controllers\PreorderAdminController::class, 'history'])->name('admin.orders.history');
});

// Preorder landing (supports optional subdomain via PREORDER_DOMAIN env)
Route::group(['domain' => env('PREORDER_DOMAIN', null)], function () {
    // If PREORDER_DOMAIN is not set, this group still works for the default domain.
    Route::get('/preorder', [PreorderController::class, 'showLanding'])->name('preorder.landing');
    Route::get('/preorder/create/{product}', [PreorderController::class, 'create'])->name('preorder.create');
    Route::post('/preorder', [PreorderController::class, 'store'])->name('preorder.store');
    Route::get('/preorder/thank-you/{id}', [PreorderController::class, 'thankyou'])->name('preorder.thankyou');
});

// Fallback registration if domain not configured (simple routes)
Route::get('/preorder', [PreorderController::class, 'showLanding'])->name('preorder.landing');
Route::get('/preorder/create/{product}', [PreorderController::class, 'create'])->name('preorder.create');
Route::post('/preorder', [PreorderController::class, 'store'])->name('preorder.store');
Route::get('/preorder/thank-you/{id}', [PreorderController::class, 'thankyou'])->name('preorder.thankyou');

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
