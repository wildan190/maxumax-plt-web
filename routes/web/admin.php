<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin panel (prefix /admin, middleware auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->group(function () {
    require __DIR__.'/admin/shipping_integrations.php';
    require __DIR__.'/admin/preorders.php';
    require __DIR__.'/admin/orders.php';
    require __DIR__.'/admin/products.php';
    require __DIR__.'/admin/reports_cms_users.php';
    require __DIR__.'/admin/support.php';
});
