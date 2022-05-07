<?php

namespace Alirzaj\RabbitMQ\Tests;

use Alirzaj\RabbitMQ\RabbitMQServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            RabbitMQServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
