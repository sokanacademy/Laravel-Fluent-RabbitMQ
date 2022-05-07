<?php

namespace Alirzaj\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;

class RabbitMQExchange
{
    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;
    /**
     * @var bool
     */
    private $durable = false;

    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    public function name(string $name): RabbitMQExchange
    {
        $this->name = $name;

        return $this;
    }

    public function type(string $type): RabbitMQExchange
    {
        $this->type = $type;

        return $this;
    }

    public function durable(): RabbitMQExchange
    {
        $this->durable = true;

        return $this;
    }

    public function declare()
    {
        $this->channel->exchange_declare(
            $this->name,
            $this->type,
            false,
            $this->durable,
            false
        );
    }

    public function __destruct()
    {
        $this->channel->close();
    }
}
