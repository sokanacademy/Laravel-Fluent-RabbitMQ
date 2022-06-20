<?php

namespace Sokanacademy\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password')
        );

        $this->channel = $this->connection->channel();
    }

    public function queue(): RabbitMQQueue
    {
        return new RabbitMQQueue($this->channel);
    }

    public function exchange(): RabbitMQExchange
    {
        return new RabbitMQExchange($this->connection);
    }

    public function message(): RabbitMQMessage
    {
        return new RabbitMQMessage($this->channel);
    }

    public function consume(): RabbitMQConsumer
    {
        return new RabbitMQConsumer($this->channel);
    }

    public function __destruct()
    {
        $this->connection->close();
        $this->channel->close();
    }
}
