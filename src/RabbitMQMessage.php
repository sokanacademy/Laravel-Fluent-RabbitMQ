<?php

namespace Alirzaj\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQMessage
{
    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * @var array
     */
    private $payload;

    /**
     * @var bool
     */
    private $persistent = false;

    /**
     * @var string
     */
    private $routingKey = '';

    /**
     * @var string
     */
    private $exchange;

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

    public function viaExchange(string $exchange)
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
