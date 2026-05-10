<?php

use App\Http\Controllers\Admin\ComplaintAdminController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('complaints')->name('admin.complaints.')->group(function () {
    Route::get('/', [ComplaintAdminController::class, 'index'])->name('index');
    Route::get('{complaint}', [ComplaintAdminController::class, 'show'])->name('show');
    Route::post('{complaint}/approve', [ComplaintAdminController::class, 'approve'])->name('approve');
    Route::post('{complaint}/reject', [ComplaintAdminController::class, 'reject'])->name('reject');
    Route::post('{complaint}/confirm-return', [ComplaintAdminController::class, 'confirmReturn'])->name('confirm-return');
});

Route::prefix('notifications')->name('admin.notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
    Route::delete('{id}', [NotificationController::class, 'destroy'])->name('destroy');
});
