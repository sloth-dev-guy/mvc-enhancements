FROM php:8.2-cli-alpine

LABEL maintainer="Telmo Rafael <sloth.dev.guy@gmail.com>"

ARG DEV_USER_ID=1000

RUN apk add --no-cache $PHPIZE_DEPS git zip unzip bash curl-dev openssl-dev \
    && apk add --update linux-headers \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && pecl install xdebug mongodb \
    && docker-php-ext-enable xdebug mongodb \
    && apk del --no-cache $PHPIZE_DEPS \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

RUN adduser -s /bin/bash -D -h /app -G www-data -u $DEV_USER_ID code

USER code

ENTRYPOINT ["docker-php-entrypoint"]
