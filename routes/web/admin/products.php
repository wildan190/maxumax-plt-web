<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->name('admin.products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/template', [ProductController::class, 'downloadTemplate'])->name('template');
    Route::get('/export', [ProductController::class, 'export'])->name('export');
    Route::post('/import', [ProductController::class, 'import'])->name('import');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');
});
