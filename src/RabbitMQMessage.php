<?php

namespace Sokanacademy\RabbitMQ;

use Illuminate\Support\Traits\Conditionable;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQMessage
{
    use Conditionable;

    private AMQPChannel $channel;

    private array $payload;

    private bool $persistent = false;

    private string $routingKey = '';

    private string $exchange;

    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    public function withPayload(array $payload = []): RabbitMQMessage
    {
        $this->payload = $payload;

        return $this;
    }

    public function persistent(): RabbitMQMessage
    {
        $this->persistent = true;

        return $this;
    }

    public function route(string $routingKey): RabbitMQMessage
    {
        $this->routingKey = $routingKey;

        return $this;
    }

    public function viaExchange(string $exchange): RabbitMQMessage
    {
        $this->exchange = $exchange;

        return $this;
    }

    public function publish(): void
    {
        $this->channel->basic_publish(
            new AMQPMessage(json_encode($this->payload), $this->properties()),
            $this->exchange,
            $this->routingKey
        );
    }

    private function properties(): array
    {
        $properties = [];

        if ($this->persistent) {
            $properties['delivery_mode'] = AMQPMessage::DELIVERY_MODE_PERSISTENT;
        }

        return $properties;
    }
}
