<?php

namespace Nas1k\LaravelSes\Providers;

use Nas1k\LaravelSes\Domain\Entity\Report;
use Nas1k\LaravelSes\Domain\Entity\ReportRepository;
use Aws\Ses\SesClient;
use Aws\Sns\SnsClient;
use Aws\Sqs\SqsClient;
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
        $this->app->bind(SnsClient::class, function ($app){
            return new SnsClient($app['config']['aws']['sns']);
        });
        $this->app->bind(SesClient::class, function ($app){
            return new SesClient($app['config']['aws']['ses']);
        });
        $this->app->bind(SqsClient::class, function ($app){
            return new SqsClient($app['config']['aws']['sqs']);
        });
        $this->app->bind(ReportRepository::class, function ($app) {
            return new ReportRepository($app['em'], new ClassMetadata(Report::class));
        });
    }
}
