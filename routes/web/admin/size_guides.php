<?php

use App\Http\Controllers\SizeGuideAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('size-guides')->name('admin.size-guides.')->group(function () {
    Route::get('/', [SizeGuideAdminController::class, 'index'])->name('index');
    Route::get('create', [SizeGuideAdminController::class, 'create'])->name('create');
    Route::post('/', [SizeGuideAdminController::class, 'store'])->name('store');
    Route::get('{sizeGuide}/edit', [SizeGuideAdminController::class, 'edit'])->name('edit');
    Route::put('{sizeGuide}', [SizeGuideAdminController::class, 'update'])->name('update');
    Route::delete('{sizeGuide}', [SizeGuideAdminController::class, 'destroy'])->name('destroy');
});
