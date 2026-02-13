#!/bin/bash

ENTRYPOINT_DIR="$(dirname "$0")"
PROJECT_ROOT="$ENTRYPOINT_DIR/../src" # Should be /var/www

# Navigate to project root
cd /var/www

if [ ! -f .env ]; then
    cp .env.example .env
fi

# Check for empty APP_KEY handling Windows CRLF line endings
CURRENT_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f 2 | tr -d '\r' | tr -d ' ')
if [ -z "$CURRENT_KEY" ]; then
    php artisan key:generate
fi

php artisan migrate --force
php artisan db:seed
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
php artisan storage:link

# Fix permissions for storage and cache (crucial for bind mounts)
chmod -R 777 /var/www/storage
chmod -R 777 /var/www/bootstrap/cache

exec php-fpm
