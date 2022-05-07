<?php

namespace Sokanacademy\RabbitMQ;

use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Sokanacademy\RabbitMQ\Commands\ConsumeMessages;
use Sokanacademy\RabbitMQ\Commands\DeclareExchanges;

class RabbitMQServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(RabbitMQ::class, function () {
            return new RabbitMQ();
        });

        $this->app->extend('events', function (Dispatcher $dispatcher, $app) {
            return (new RabbitMQDispatcher($app))->setQueueResolver(function () use ($app) {
                return $app->make(QueueFactoryContract::class);
            });
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/rabbitmq.php',
            'rabbitmq'
        );
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeclareExchanges::class,
                ConsumeMessages::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/rabbitmq.php' => config_path('rabbitmq.php'),
        ]);
    }
}
