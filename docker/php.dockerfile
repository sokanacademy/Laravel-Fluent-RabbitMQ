FROM php:8.1-fpm-alpine

RUN docker-php-ext-install bcmath sockets

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
