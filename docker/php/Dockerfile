FROM php:8.3-fpm

RUN apt-get update -y
RUN apt-get install -y \
    libbz2-dev \
    libxml2-dev \
    libzip-dev \
    libc-client-dev \
    libkrb5-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    libkrb5-dev \
    libicu-dev \
    zlib1g-dev \
    zip \
    ffmpeg \
    libmemcached11 \
    libmemcachedutil2 \
    build-essential \
    libmemcached-dev \
    gnupg2 \
    libpq-dev \
    libpq5 \
    libz-dev

RUN docker-php-ext-install \
    bz2 \
    mysqli \
    opcache \
    pdo_mysql \
    soap \
    zip \
    exif \
    gd

#RUN docker-php-ext-install \
 #   fileinfo