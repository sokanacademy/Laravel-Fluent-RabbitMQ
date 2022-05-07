<?php

namespace Alirzaj\RabbitMQ\Commands;

use Alirzaj\RabbitMQ\RabbitMQ;
use Alirzaj\RabbitMQ\Support\ShouldPublish;
use Illuminate\Console\Command;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Collection;
use ReflectionClass;

class DeclareExchanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:declare-exchanges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'declare exchanges';

    public function handle(RabbitMQ $rabbitmq)
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
                    ->type('direct')
                    ->name(class_basename($event))
                    ->declare();

                $this->info('declared ' . class_basename($event));
                $this->newLine();
            });

        return 0;
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
}
