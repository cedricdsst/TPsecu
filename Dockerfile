# Dockerfile
FROM php:8.2-apache

# Install PHP extensions (e.g., MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Default workdir will be set per app instance
WORKDIR /var/www/html

# Ensure permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
