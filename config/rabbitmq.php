<?php

return [
    'host' => env('RABBITMQ_HOST', '127.0.0.1'),
    'port' => env('RABBITMQ_PORT', 5672),
    'user' => env('RABBITMQ_USER', 'guest'),
    'password' => env('RABBITMQ_PASSWORD', 'guest'),
    'vhost' => env('RABBITMQ_VHOST', '/'),
    
    'consumers' => [
//        [
//            'event' => '\App\Events\MyEvent',
//            'routing_key' => 'my_routing_key', // if this event does not use routing key then remove this line
//            'map_into' => '\App\Events\MapIntoEvent', // if you want to use the same event then remove this line
//        ],
    ],
];
