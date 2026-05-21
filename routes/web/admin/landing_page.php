<?php

use App\Http\Controllers\LandingPageAdminController;
use Illuminate\Support\Facades\Route;

Route::get('landing-page', [LandingPageAdminController::class, 'edit'])->name('admin.landing-page.edit');
Route::put('landing-page', [LandingPageAdminController::class, 'update'])->name('admin.landing-page.update');
Route::post('landing-page/reset-hero', [LandingPageAdminController::class, 'resetHero'])->name('admin.landing-page.reset-hero');
Route::post('landing-page/reset-shop', [LandingPageAdminController::class, 'resetShop'])->name('admin.landing-page.reset-shop');
Route::post('landing-page/reset-featured', [LandingPageAdminController::class, 'resetFeatured'])->name('admin.landing-page.reset-featured');
Route::post('landing-page/reset-projects', [LandingPageAdminController::class, 'resetProjects'])->name('admin.landing-page.reset-projects');

// Partials for dynamic row adding
Route::get('landing-page/partial-hero', [LandingPageAdminController::class, 'partialHero'])->name('admin.landing-page.partial-hero');
Route::get('landing-page/partial-shop', [LandingPageAdminController::class, 'partialShop'])->name('admin.landing-page.partial-shop');
Route::get('landing-page/partial-featured', [LandingPageAdminController::class, 'partialFeatured'])->name('admin.landing-page.partial-featured');
Route::get('landing-page/partial-project', [LandingPageAdminController::class, 'partialProject'])->name('admin.landing-page.partial-project');
