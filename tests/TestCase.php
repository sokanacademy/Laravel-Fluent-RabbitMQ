<?php

namespace Sokanacademy\LaravelFluentRabbitMQ\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Sokanacademy\LaravelFluentRabbitMQ\LaravelFluentRabbitMQServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Sokanacademy\\LaravelFluentRabbitMQ\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelFluentRabbitMQServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-fluent-rabbitmq_table.php.stub';
        $migration->up();
        */
    }
}
