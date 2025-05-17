<?php

namespace Ahid\LeopardsCourier;

use Illuminate\Support\ServiceProvider;

class AhidLeopardsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/leopards-courier.php' => config_path('leopards-courier.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/leopards-courier.php',
            'leopards-courier'
        );

        $this->app->singleton('leopards-courier', function ($app) {
            return new LeopardsCourier();
        });
    }
}
