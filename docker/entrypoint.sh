#!/bin/bash

ENTRYPOINT_DIR="$(dirname "$0")"
PROJECT_ROOT="$ENTRYPOINT_DIR/../src" # Should be /var/www

# Navigate to project root
cd /var/www

# Force delete ANY stale cache files immediately
rm -rf bootstrap/cache/*.php

# Strip Windows CRLF line endings from .env if it exists
if [ -f .env ]; then
    sed -i 's/\r//g' .env
fi

# Ensure .env exists
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Ensure .env is writable
chmod 666 .env || true

# Check for empty APP_KEY
# If APP_KEY is missing or empty, generate it.
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generating new APP_KEY..."
    # Ensure line exists for artisan to replace
    if ! grep -q "^APP_KEY=" .env; then
        echo "APP_KEY=" >> .env
    fi
    php artisan key:generate --force
fi

# IMPORTANT: Export the key to the current process so artisan migrate can see it
export APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f 2)

# Clear everything
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Wait for MySQL to be truly ready
echo "Waiting for database connection..."
until php -r "try { new PDO('mysql:host=db;port=3306;dbname=helpdesk_system', 'helpdesk', 'user_password'); exit(0); } catch (Exception \$e) { exit(1); }"; do
    sleep 2
    echo "Still waiting for database..."
done
echo "Database is ready!"

php artisan migrate --force
php artisan db:seed

# Generate symlink
php artisan storage:link

# Final permissions for standard Laravel operation
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "System ready. Starting PHP-FPM..."
exec php-fpm
