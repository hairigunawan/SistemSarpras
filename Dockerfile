FROM php:8.4-cli

# Install system dependencies + GD + ZIP
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# FIX PERMISSIONS (INI YANG KAMU TANYAKAN)
RUN chmod -R 775 storage bootstrap/cache

# Clear cache (AMAN)
RUN php artisan config:clear || true \
 && php artisan cache:clear || true \
 && php artisan route:clear || true \
 && php artisan view:clear || true

EXPOSE 8080

# IMPORTANT: pakai PORT dari Railway
CMD php artisan serve --host=0.0.0.0 --port=$PORT
