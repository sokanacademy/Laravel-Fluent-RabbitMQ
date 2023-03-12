# A package to work with RabbitMQ in an elegant way.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sokanacademy/laravel-fluent-rabbitmq.svg?style=flat-square)](https://packagist.org/packages/sokanacademy/laravel-fluent-rabbitmq)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sokanacademy/laravel-fluent-rabbitmq/run-tests?label=tests)](https://github.com/sokanacademy/laravel-fluent-rabbitmq/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/sokanacademy/laravel-fluent-rabbitmq/Check%20&%20fix%20styling?label=code%20style)](https://github.com/sokanacademy/laravel-fluent-rabbitmq/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sokanacademy/laravel-fluent-rabbitmq.svg?style=flat-square)](https://packagist.org/packages/sokanacademy/laravel-fluent-rabbitmq)

This package allows your laravel applications to easily communicate with each other in an event driven way.

One service can publish an event and another one can consume the event and take actions accordingly.

## Installation

You can install the package via composer:

```bash
composer require sokanacademy/laravel-fluent-rabbitmq:0.12
```

Then you should publish the package config with running this command:

```bash
php artisan vendor:publish --tag="laravel-fluent-rabbitmq-config"
```

This is the contents of the published config file:

```php
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
```

## Usage

### Mark an event to be published on RabbitMQ

The only thing you must do is to make sure your event implements `Sokanacademy\RabbitMQ\Support\ShouldPublish` interface
and that's it.
All of the event's public properties will be published, and you can have access to them in your consumer. Make sure these properties are primitive or Arrayable.

If you want your event to be published using a routing key, then consider adding routingKey method to your event:

```php
    public function routingKey(): string
    {
        return 'routing_key';
    }
```

### declare exchanges in rabbitmq server

When a laravel application wants to publish events, you must run this command to create appropriate exchanges on
RabbitMQ.
For each event it will create an exchange with the name of event class.
You can read more on exchanges types [here](https://www.rabbitmq.com/tutorials/amqp-concepts.html).

The default type for exchanges will be 'fanout'. If you want to alter the type of exchange for an event you can add this
property to your event:

```php
    private static string $exchangeType = 'topic';
```

## Consume events from RabbitMQ
In the `rabbitmq.php` config file you should list all the events you want to consume.

```php
    'consumers' => [
//        [
//            'event' => '\App\Events\MyEvent',
//            'routing_key' => 'my_routing_key', // if this event does not use routing key then remove this line
//            'map_into' => '\App\Events\MapIntoEvent', // if you want to use the same event then remove this line
//        ],
    ],
```
If you have same event in both services (publisher and consumer) then you can omit the map_into option for the event.

Then you can start consuming events with the following command:

```bash
php artisan rabbitmq:consume
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sokanacademy](https://github.com/sokanacademy)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
