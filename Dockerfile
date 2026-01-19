FROM php:8.4-fpm

WORKDIR /var/www

# -------------------------------
# System dependencies
# -------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev unzip git curl \
    libonig-dev libicu-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# -------------------------------
# PHP extensions
# -------------------------------
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd pdo pdo_mysql pdo_pgsql mbstring zip intl bcmath pcntl opcache

# Redis
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT=-1

# -------------------------------
# Install PHP deps (cache-friendly)
# -------------------------------
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts

# -------------------------------
# Copy application code
# -------------------------------
COPY . .

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Optimize autoload
RUN composer dump-autoload --optimize --classmap-authoritative

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
