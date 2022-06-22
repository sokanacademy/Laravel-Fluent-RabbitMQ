<?php

namespace Sokanacademy\RabbitMQ;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Events\Dispatcher;
use Sokanacademy\RabbitMQ\Support\ShouldPublish;

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
            ->when(
                method_exists($event, 'routingKey'),
                fn (RabbitMQMessage $message) => $message
                ->route($event->routingKey())
            )
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
