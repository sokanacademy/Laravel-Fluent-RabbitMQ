<?php

namespace Sokanacademy\RabbitMQ\Commands;

use Illuminate\Console\Command;
use Sokanacademy\RabbitMQ\RabbitMQ;

class ConsumeMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'consume rabbitmq messages';

    /**
     * @var array
     */
    private $events;

    public function __construct()
    {
        parent::__construct();

        $this->events = collect(config('rabbitmq.consumers'))
            ->mapWithKeys(function ($event) {
                if (is_array($event)) {
                    return [class_basename($event[0]) => $event[0]];
                }

                return [class_basename($event) => $event];
            })
            ->toArray();
    }

    public function handle(RabbitMQ $rabbitmq)
    {
        $queue = $rabbitmq
            ->queue()
            ->durable()
            ->name(config('app.name'))
            ->declare();

        foreach (config('rabbitmq.consumers') as $event) {
            if (is_string($event)) {
                $queue->bindTo(class_basename($event));
            }

            if (is_array($event)) {
                $queue->bindTo(class_basename($event[0]), $event[1]);
            }
        }

        $rabbitmq
            ->consume()
            ->acknowledge()
            ->from(config('app.name'), [$this, 'fireEvent'])
            ->receive();

        return 0;
    }

    public function fireEvent(array $payload)
    {
        event(resolve($this->events[$payload['event.name']], $payload));
    }
}
