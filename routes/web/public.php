<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);

Route::post('/shipping/rates', [ShippingController::class, 'checkRates'])->name('shipping.rates');

Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery.index');
Route::get('/policies', [PageController::class, 'policies'])->name('pages.policies');
