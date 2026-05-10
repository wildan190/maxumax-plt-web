<?php

use App\Http\Controllers\OrderAdminController;
use App\Http\Controllers\PreorderAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/orders/history', [PreorderAdminController::class, 'history'])->name('admin.orders.history');

Route::prefix('orders')->name('admin.orders.')->group(function () {
    Route::get('/', [OrderAdminController::class, 'index'])->name('index');
    Route::get('/print', [OrderAdminController::class, 'printIndex'])->name('print');
    Route::get('/export/csv', [OrderAdminController::class, 'exportCsv'])->name('export');

    Route::get('{order}', [OrderAdminController::class, 'show'])->name('show');
    Route::get('{order}/print', [OrderAdminController::class, 'printShow'])->name('printShow');
    Route::post('{order}/confirm', [OrderAdminController::class, 'confirm'])->name('confirm');
    Route::post('{order}/mark-paid', [OrderAdminController::class, 'markPaid'])->name('markPaid');
    Route::post('{order}/mark-packing', [OrderAdminController::class, 'markPacking'])->name('markPacking');
    Route::post('{order}/mark-shipped', [OrderAdminController::class, 'markShipped'])->name('markShipped');
    Route::post('{order}/mark-delivered', [OrderAdminController::class, 'markDelivered'])->name('markDelivered');
    Route::post('{order}/request-refund', [OrderAdminController::class, 'requestRefund'])->name('requestRefund');
    Route::post('{order}/approve-refund', [OrderAdminController::class, 'approveRefund'])->name('approveRefund');
    Route::post('{order}/reject-refund', [OrderAdminController::class, 'rejectRefund'])->name('rejectRefund');
    Route::delete('{order}', [OrderAdminController::class, 'destroy'])->name('destroy');

    Route::get('{order}/shipping', [OrderAdminController::class, 'shipping'])->name('shipping');
    Route::post('{order}/shipping/rates', [OrderAdminController::class, 'checkRates'])->name('checkRates');
    Route::get('{order}/shipping/rates', [OrderAdminController::class, 'shipping']);
    Route::post('{order}/shipping/book', [OrderAdminController::class, 'bookShipping'])->name('bookShipping');
    Route::get('{order}/shipping/book', [OrderAdminController::class, 'shipping']);
    Route::post('{order}/shipping/refresh', [OrderAdminController::class, 'refreshTracking'])->name('refreshTracking');
});
