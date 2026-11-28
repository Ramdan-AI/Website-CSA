FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    git unzip curl \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev oniguruma-dev libxml2-dev libzip-dev \
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html
