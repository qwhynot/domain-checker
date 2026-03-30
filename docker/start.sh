#!/bin/bash

cd /var/www

# Create supervisor log directory
mkdir -p /var/log/supervisor

# Wait for DB to be ready (max 30 seconds)
echo "Waiting for database..."
for i in $(seq 1 30); do
    php artisan db:show --no-interaction > /dev/null 2>&1 && break
    echo "Attempt $i/30 — DB not ready, retrying in 1s..."
    sleep 1
done

# Run migrations
php artisan migrate --force || echo "Migration failed, continuing..."

# Cache for production
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start nginx + php-fpm via supervisor
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
