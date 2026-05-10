<?php

use App\Http\Controllers\PreorderAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('preorders')->name('admin.preorders.')->group(function () {
    Route::get('/', [PreorderAdminController::class, 'index'])->name('index');
    Route::get('/export/csv', [PreorderAdminController::class, 'exportCsv'])->name('export');

    Route::get('{preorder}', [PreorderAdminController::class, 'show'])->name('show');
    Route::post('{preorder}/confirm', [PreorderAdminController::class, 'confirm'])->name('confirm');
    Route::post('{preorder}/mark-paid', [PreorderAdminController::class, 'markPaid'])->name('markPaid');
    Route::post('{preorder}/mark-packing', [PreorderAdminController::class, 'markPacking'])->name('markPacking');
    Route::post('{preorder}/mark-shipped', [PreorderAdminController::class, 'markShipped'])->name('markShipped');
    Route::post('{preorder}/mark-delivered', [PreorderAdminController::class, 'markDelivered'])->name('markDelivered');

    Route::get('{preorder}/shipping', [PreorderAdminController::class, 'shipping'])->name('shipping');
    Route::post('{preorder}/shipping/rates', [PreorderAdminController::class, 'checkRates'])->name('checkRates');
    Route::get('{preorder}/shipping/rates', [PreorderAdminController::class, 'shipping']);
    Route::post('{preorder}/shipping/book', [PreorderAdminController::class, 'bookShipping'])->name('bookShipping');
    Route::get('{preorder}/shipping/book', [PreorderAdminController::class, 'shipping']);
    Route::post('{preorder}/shipping/refresh', [PreorderAdminController::class, 'refreshTracking'])->name('refreshTracking');

    Route::post('{preorder}/request-refund', [PreorderAdminController::class, 'requestRefund'])->name('requestRefund');
    Route::post('{preorder}/approve-refund', [PreorderAdminController::class, 'approveRefund'])->name('approveRefund');
    Route::post('{preorder}/reject-refund', [PreorderAdminController::class, 'rejectRefund'])->name('rejectRefund');
    Route::delete('{preorder}', [PreorderAdminController::class, 'destroy'])->name('destroy');
});
