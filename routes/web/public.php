<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);

Route::post('/shipping/rates', [ShippingController::class, 'checkRates'])->name('shipping.rates');

Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery.index');
Route::get('/policies', [PageController::class, 'policies'])->name('pages.policies');
Route::get('/size-guide', [PageController::class, 'sizeGuide'])->name('pages.size-guide');
Route::get('/customization', [PageController::class, 'customization'])->name('pages.customization');
Route::get('/projects', [PageController::class, 'projects'])->name('pages.projects');
Route::get('/projects/{slug}', [PageController::class, 'projectDetail'])->name('pages.projects.detail');
Route::get('/projects/category/{category}', [PageController::class, 'projectCategory'])->name('pages.projects.category');
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');
Route::get('/contact-us', [PageController::class, 'contactUs'])->name('pages.contact-us');
Route::post('/contact-us', [PageController::class, 'submitContactUs'])->name('pages.contact-us.submit');
