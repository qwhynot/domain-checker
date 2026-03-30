FROM php:8.4-fpm

# Встановлення системних залежностей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Встановлення Redis розширення
RUN pecl install redis \
    && docker-php-ext-enable redis

# Встановлення Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Встановлення Node.js та npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Налаштування робочої директорії
WORKDIR /var/www

# Налаштування прав доступу
RUN chown -R www-data:www-data /var/www

# Очищення кешу
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Копіювання PHP конфігурації
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
