<?php

namespace Sokanacademy\RabbitMQ\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Collection;
use ReflectionClass;
use Sokanacademy\RabbitMQ\RabbitMQ;
use Sokanacademy\RabbitMQ\Support\ShouldPublish;

class DeclareExchanges extends Command
{
    protected $signature = 'rabbitmq:declare-exchanges';

    protected $description = 'declare exchanges';

    public function handle(RabbitMQ $rabbitmq): int
    {
        $this
            ->getEvents()
            ->filter(function (string $event) {
                return in_array(
                    ShouldPublish::class,
                    (new ReflectionClass($event))->getInterfaceNames()
                );
            })
            ->each(function (string $event) use ($rabbitmq) {
                $rabbitmq
                    ->exchange()
                    ->durable()
                    ->type($this->determineExchangeType($event))
                    ->name(class_basename($event))
                    ->declare();

                $this->info('declared ' . class_basename($event));
                $this->newLine();
            });

        return Command::SUCCESS;
    }

    private function getEvents(): Collection
    {
        $events = [];

        foreach ($this->laravel->getProviders(EventServiceProvider::class) as $provider) {
            $providerEvents = array_merge_recursive(
                $provider->shouldDiscoverEvents()
                    ? $provider->discoverEvents()
                    : [],
                $provider->listens()
            );

            $events = array_merge_recursive($events, $providerEvents);
        }

        return collect($events)->keys();
    }

    private function determineExchangeType(string $event): string
    {
        $reflection = (new ReflectionClass($event));

        if (! $reflection->hasProperty('exchangeType')) {
            return 'fanout';
        }

        $property = $reflection->getProperty('exchangeType');

        $property->setAccessible(true);

        return $property->getValue();
    }
}
