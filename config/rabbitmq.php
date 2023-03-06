<?php

return [
    'host' => 'rabbitmq',
    'port' => '5672',
    'user' => 'guest',
    'password' => 'guest',

    'consumers' => [
//        [
//            'event' => '\App\Events\MyEvent',
//            'routing_key' => 'my_routing_key', // if this event does not use routing key then remove this line
//            'map_into' => '\App\Events\MapIntoEvent', // if you want to use the same event then remove this line
//        ],
    ],
];
