# Use an official PHP image as a base image
FROM php:8.4-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the Laravel application files into the container
COPY ./ /var/www/html

# Core utilites install
RUN apt-get update && \
    apt-get install -y \
        coreutils \
        zip \
        unzip \
        nano \
        iputils-ping \
        curl \
    && rm -rf /var/lib/apt/lists/*

# Easily install PHP extension in Docker containers | https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/install-php-extensions
RUN set -eux \
    && install-php-extensions \
        @composer-2 \
        opcache \
        pdo_mysql \
        redis \
        intl \
        pcntl \
        exif \
        xdebug \
        gd

# Set up Apache configurations
COPY ./docker/config/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite

# Expose port 80 for Apache
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
