FROM composer:2

ENV COMPOSER_USER=laravel
ENV COMPOSER_GROUP=laravel

RUN adduser -g ${COMPOSER_GROUP} -s /bin/sh -D ${COMPOSER_USER}