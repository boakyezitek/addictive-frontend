<?php

namespace App\Providers;

use App\Services\RevenueCat\RevenueCat;
use App\Services\RevenueCat\Interfaces\RevenueCatInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RevenueCatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('revenue-cat', function() {
            return new RevenueCat(config('revenue-cat.api_key'));
        });

        $this->app->singleton(RevenueCatInterface::class, function () {
            return new RevenueCat(config('revenue-cat.api_key'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
