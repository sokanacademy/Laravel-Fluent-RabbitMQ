<?php

namespace Sokanacademy\LaravelFluentRabbitMQ\Commands;

use Illuminate\Console\Command;

class LaravelFluentRabbitMQCommand extends Command
{
    public $signature = 'laravel-fluent-rabbitmq';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
