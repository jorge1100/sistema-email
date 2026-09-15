FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
git \
curl \
zip \
unzip \
libzip-dev \
libicu-dev \
libpng-dev \
libonig-dev \
libxml2-dev

RUN docker-php-ext-install \
pdo \
pdo_mysql \
bcmath \
intl \
zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
