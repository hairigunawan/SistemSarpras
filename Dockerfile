FROM php:8.4-cli

# Install system dependencies + GD + ZIP
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080
