FROM php:7.4-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN rm /etc/apt/preferences.d/no-debian-php

RUN apt-get update && apt-get install -y \
        php-common \
        php-curl \
        php-json \
        php-mbstring \
        php-mysql \
        php-xml \
        php-zip