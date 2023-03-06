<?php

namespace Sokanacademy\RabbitMQ\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Sokanacademy\RabbitMQ\RabbitMQ;

class ConsumeMessages extends Command
{
    protected $signature = 'rabbitmq:consume';

    protected $description = 'consume rabbitmq messages';

    private array $events;

    public function __construct()
    {
        parent::__construct();

        $this->events = collect(config('rabbitmq.consumers'))
            ->map(function ($consume) {
                if (is_string($consume)) {
                    return [
                        'base_event' => $consume,
                        'event' => $consume,
                        'routing_key' => '',
                    ];
                }

                if (is_array($consume) && count($consume) === 2) {
                    return [
                        'base_event' => $consume[0],
                        'event' => $consume[0],
                        'routing_key' => $consume[1],
                    ];
                }

                if (is_array($consume) && count($consume) === 3) {
                    return [
                        'base_event' => $consume[0],
                        'event' => $consume[2],
                        'routing_key' => $consume[1],
                    ];
                }

                throw new InvalidArgumentException('invalid consumers array');
            })
            ->toArray();
    }

    public function handle(RabbitMQ $rabbitmq): int
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

    public function fireEvent(array $payload, string $routingKey)
    {
        $event = Arr::first($this->events, function (array $event) use ($routingKey, $payload) {
            return $payload['event.name'] === class_basename($event['base_event'])
                && Str::is($event['routing_key'] ?? '', $routingKey);
        })['event'];

        event(resolve($event, $payload));
    }
}
