<?php

namespace Sokanacademy\LaravelFluentRabbitMQ;

use Sokanacademy\LaravelFluentRabbitMQ\Commands\LaravelFluentRabbitMQCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelFluentRabbitMQServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-fluent-rabbitmq')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-fluent-rabbitmq_table')
            ->hasCommand(LaravelFluentRabbitMQCommand::class);
    }
}
