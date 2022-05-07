<?php

namespace Alirzaj\RabbitMQ;

use Alirzaj\RabbitMQ\Support\ShouldPublish;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Events\Dispatcher;

class RabbitMQDispatcher extends Dispatcher
{
    /**
     * Fire an event and call the listeners.
     *
     * @param string|object $event
     * @param mixed $payload
     * @param bool $halt
     * @return array|null
     */
    public function dispatch($event, $payload = [], $halt = false)
    {
        if (! $event instanceof ShouldPublish) {
            return parent::dispatch($event, $payload, $halt);
        }

        /** @var RabbitMQ $rabbitmq */
        $rabbitmq = resolve(RabbitMQ::class);

        $rabbitmq
            ->message()
            ->persistent()
            ->viaExchange(class_basename($event))
            ->withPayload(
                array_map(
                    function ($property) {
                        return $this->formatProperty($property);
                    },
                    call_user_func('get_object_vars', $event)
                ) + ['event.name' => class_basename($event)]
            )
            ->publish();

        return parent::dispatch($event, $payload, $halt);
    }

    private function formatProperty($property)
    {
        if ($property instanceof Arrayable) {
            return $property->toArray();
        }

        return $property;
    }
}
