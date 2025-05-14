<?php

namespace Tots\Unimatrix\Providers;

use Illuminate\Support\ServiceProvider;
use Tots\Unimatrix\Services\TotsUnimatrixService;

class TotsUnimatrixServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register role singleton
        $this->app->singleton(TotsUnimatrixService::class, function ($app) {
            return new TotsUnimatrixService(config('unimatrix'));
        });
    }

    /**
     *
     * @return void
     */
    public function boot()
    {
        
    }
}