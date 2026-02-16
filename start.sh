#!/usr/bin/env bash
set -e

php -v

# Permissions (Railway container)
chmod -R 775 storage bootstrap/cache || true

# Laravel caches (safe in prod; if fails, do not crash)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run migrations on boot (optional; remove if you don't want auto migrate)
php artisan migrate --force || true

# Serve
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}