FROM php:7.2-fpm

MAINTAINER Tomasz Tarnawski <tarnawski27@gmail.com>

RUN apt-get update && apt-get install -y zlib1g-dev \
    && docker-php-ext-install zip pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
