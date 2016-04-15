<?php

namespace Nas1k\LaravelSes;

use Nas1k\LaravelSes\Domain\Entity\Report;
use Nas1k\LaravelSes\Domain\Entity\ReportRepository;
use Aws\Ses\SesClient;
use Doctrine\ORM\Mapping\ClassMetadata;
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
            __DIR__ . '../config/doctrine.php' => config_path('doctrine.php'),
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
        $this->app->bind(SesClient::class, function ($app){
            return new SesClient($app['config']['aws']);
        });
        $this->app->bind(ReportRepository::class, function ($app) {
            return new ReportRepository($app['em'], new ClassMetadata(Report::class));
        });
    }
}
