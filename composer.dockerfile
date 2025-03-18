FROM composer:latest

RUN apk update && \
    apk add --no-cache \
    libjpeg-turbo-dev \
    libexif-dev && \
    docker-php-ext-install exif && \
    apk del libjpeg-turbo-dev libexif-dev
