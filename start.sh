#!/usr/bin/env bash
set -e

# Ensure storage dirs exist (IMPORTANT for view:cache)
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Optional permissions (Railway obicno radi i bez, ali nek stoji)
chmod -R 775 storage bootstrap/cache || true

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# If APP_KEY is missing, generate (Railway obicno imas u Variables)
php artisan key:generate --force || true

# Cache after folders exist
php artisan view:cache

# Migracije (ako koristis DB)
php artisan migrate --force

# Start server (ako koristis php -S; ako koristis nginx, drugačije)
php -S 0.0.0.0:${PORT:-8000} -t public