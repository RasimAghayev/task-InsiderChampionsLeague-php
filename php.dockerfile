# Build Stage
ARG PHP_VERSION=8.3
FROM php:${PHP_VERSION}-fpm AS build

RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zip \
    curl \
    unzip \
    git && \
    docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

FROM php:${PHP_VERSION}-fpm

COPY --from=build /usr/local/bin/composer /usr/local/bin/composer
COPY --from=build /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=build /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=build /usr/local/bin/docker-php-ext-enable /usr/local/bin/

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-enable \
    pdo \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

COPY ./server/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

USER root

COPY --chown=www-data:www-data . /var/www/html

RUN mkdir -p /var/log/php && \
    chown -R www-data:www-data /var/log/php && \
    chmod 755 /var/log/php

USER www-data
RUN if [ -f "composer.lock" ]; then \
        composer install --no-dev --optimize-autoloader; \
    else \
        composer update --no-dev --optimize-autoloader; \
    fi && \
    composer clear-cache

HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 \
    CMD php-fpm -t || exit 1

EXPOSE 9000

CMD ["php-fpm"]
