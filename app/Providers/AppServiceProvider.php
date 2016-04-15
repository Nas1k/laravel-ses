<?php

namespace App\Providers;

use App\Domain\Entity\Report;
use App\Domain\Entity\ReportRepository;
use Aws\Ses\SesClient;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
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
