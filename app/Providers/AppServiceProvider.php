<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Preorder::observe(\App\Observers\PreorderObserver::class);
        \App\Models\Complaint::observe(\App\Observers\ComplaintObserver::class);
    }
}
