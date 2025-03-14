<?php

namespace App\Providers;

use App\Models\Penjualan;
use App\Observers\PenjualanObserver;
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
    public function boot()
    {
        Penjualan::observe(PenjualanObserver::class);
    }
}
