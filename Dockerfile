FROM php:7.0-cli

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
 && apt-get install --no-install-recommends -y git unzip libc-client-dev libkrb5-dev \
 && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /app
