FROM php:7.0-cli

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
 && apt-get install --no-install-recommends -y git unzip libc-client-dev libkrb5-dev \
 && docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && rm -rf /var/lib/apt/lists/*

RUN	( pecl install -f xdebug-2.7.2 || pecl install -f xdebug-2.5.5 ) && docker-php-ext-enable xdebug

WORKDIR /app
