<?php

namespace Sokanacademy\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ
{
    private AMQPStreamConnection $connection;

    private AMQPChannel $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost')
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
