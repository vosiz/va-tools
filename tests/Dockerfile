ARG PHP_VERSION=8.2

FROM composer:2 AS composer

FROM php:${PHP_VERSION}-cli-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer
