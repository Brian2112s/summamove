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
    nginx \
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

# Nginx configuratie
RUN rm /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/sites-enabled/default

# Kopieer .env
COPY .env /var/www/.env

# Kopieer CA-certificaat
COPY ca.pem /var/www/ca.pem

RUN chown www-data:www-data /var/www/ca.pem

# Label
LABEL org.opencontainers.image.source="https://github.com/brian2112s/summamove"

# Expose HTTP port
EXPOSE 80

# Start PHP-FPM en Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
