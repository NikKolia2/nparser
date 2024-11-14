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
    fileinfo \
    gd

RUN echo 'memory_limit = 3048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /parser

# Copy existing application directory permissions
COPY --chown=www:www . /parser

# Change current user to www
USER www
