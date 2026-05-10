<?php

use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\GalleryAdminController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('reports')->name('admin.reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/export', [ReportController::class, 'export'])->name('export');
});

Route::resource('galleries', GalleryAdminController::class)->names('admin.galleries');

Route::resource('users', UserAdminController::class)->names('admin.users');
