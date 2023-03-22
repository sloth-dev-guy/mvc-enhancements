FROM php:8.1-cli-alpine

LABEL maintainer="Telmo Rafael <sloth.dev.guy@gmail.com>"

ARG DEV_USER_ID=1000

RUN apk add --no-cache $PHPIZE_DEPS git zip unzip bash curl-dev openssl-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql \
    && pecl install xdebug-3.1.2 mongodb \
    && docker-php-ext-enable xdebug mongodb \
    && apk del --no-cache $PHPIZE_DEPS \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

RUN adduser -s /bin/bash -D -h /app -G www-data -u $DEV_USER_ID code

USER code

ENTRYPOINT ["docker-php-entrypoint"]
