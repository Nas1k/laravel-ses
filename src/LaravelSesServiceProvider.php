<?php

namespace Nas1k\LaravelSes;

use Illuminate\Support\ServiceProvider;

class LaravelSesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nas1kLaravelSes');
        $this->publishes([
            __DIR__ . '../config/aws.php' => config_path('aws.php'),
            __DIR__ . '../resources/views' => base_path('resources/views/vendor/nas1kLaravelSes'),
        ]);
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}
