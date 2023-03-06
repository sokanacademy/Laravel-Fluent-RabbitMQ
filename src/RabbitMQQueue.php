<?php

namespace Sokanacademy\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;

class RabbitMQQueue
{
    private AMQPChannel $channel;

    private bool $durable = false;

    private string $name;

    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    public function durable(): RabbitMQQueue
    {
        $this->durable = true;

        return $this;
    }

    public function name(string $name): RabbitMQQueue
    {
        $this->name = $name;

        return $this;
    }

    public function declare(): RabbitMQQueue
    {
        $this->channel->queue_declare(
            $this->name,
            false,
            $this->durable,
            false,
            false
        );

        return $this;
    }

    public function bindTo(string $exchange, string $route = ''): void
    {
        $this->channel->queue_bind($this->name, $exchange, $route);
    }
}
