FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    git unzip curl sqlite sqlite-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev libxml2-dev libzip-dev \
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
