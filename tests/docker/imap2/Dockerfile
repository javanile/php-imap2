FROM php:7.0-cli

RUN	( pecl install -f xdebug-2.7.2 || pecl install -f xdebug-2.5.5 ) && docker-php-ext-enable xdebug

WORKDIR /app
