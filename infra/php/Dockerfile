FROM php:8.2-fpm

# Installer PDO et PDO_MySQL
RUN docker-php-ext-install pdo pdo_mysql

COPY php/config/php.ini /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y git zip unzip && \
    docker-php-ext-install pdo pdo_mysql
