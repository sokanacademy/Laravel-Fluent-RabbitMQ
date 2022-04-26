<?php

namespace Sokanacademy\LaravelFluentRabbitMQ\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokanacademy\LaravelFluentRabbitMQ\LaravelFluentRabbitMQ
 */
class LaravelFluentRabbitMQ extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-fluent-rabbitmq';
    }
}
