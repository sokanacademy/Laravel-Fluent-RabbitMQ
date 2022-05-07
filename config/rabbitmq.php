<?php

return [
    'host' => 'rabbitmq',
    'port' => '5672',
    'user' => 'guest',
    'password' => 'guest',

    'queues' => ['default'],

    'consumers' => [
        // \App\Events\MyEvent
    ]
];
