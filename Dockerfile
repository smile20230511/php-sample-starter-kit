FROM php:8.2.4-apache

RUN apt-get update && apt-get install -y git libonig-dev \
  && docker-php-ext-install pdo_mysql mysqli \
  && a2enmod rewrite