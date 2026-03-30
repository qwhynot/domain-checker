FROM php:8.4-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev nginx supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# Copy app source
COPY src/ .

# PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build frontend assets
RUN npm ci && npm run build && rm -rf node_modules

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# PHP config
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Nginx config (production: fastcgi → 127.0.0.1:9000)
RUN sed -i '/sites-enabled/d' /etc/nginx/nginx.conf && rm -f /etc/nginx/conf.d/*
COPY docker/nginx/railway.conf /etc/nginx/conf.d/laravel.conf
RUN nginx -t

# Supervisor config
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Entrypoint
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
