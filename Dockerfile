# Base image met PHP en extensions
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer installeren
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Werkdirectory
WORKDIR /var/www

# Kopieer Laravel code
COPY . .

# Composer dependencies installeren
RUN composer install --no-dev --optimize-autoloader

# Storage & cache rechten
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

LABEL org.opencontainers.image.source="https://github.com/brian2112s/summamove"


# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
