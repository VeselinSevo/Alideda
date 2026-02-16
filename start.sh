#!/usr/bin/env bash
set -e

mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache || true

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# MIGRATIONS
php artisan migrate --force || true

# SEEDERS (admin)
php artisan db:seed --force || true

php artisan optimize:clear || true
php artisan view:cache || true

php -S 0.0.0.0:$PORT -t public