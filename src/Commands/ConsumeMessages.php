<?php

namespace Alirzaj\RabbitMQ\Commands;

use Alirzaj\RabbitMQ\RabbitMQ;
use Illuminate\Console\Command;

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
            ->mapWithKeys(function (string $event) {
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
            $queue->bindTo(class_basename($event));
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
