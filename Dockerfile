FROM php:8.2-apache

# Install system deps
RUN apt-get update && apt-get install -y         libzip-dev zip unzip git         && docker-php-ext-install zip         && a2enmod rewrite

# Set working dir
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"         && php composer-setup.php --install-dir=/usr/local/bin --filename=composer         && rm composer-setup.php

# Install PHP deps
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader || true

# Laravel permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Env + key
RUN cp -n .env.example .env || true && php artisan key:generate || true

EXPOSE 80
CMD ["apache2-foreground"]
