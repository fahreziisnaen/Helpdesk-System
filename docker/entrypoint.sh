#!/bin/bash

ENTRYPOINT_DIR="$(dirname "$0")"
PROJECT_ROOT="$ENTRYPOINT_DIR/../src" # Should be /var/www

# Navigate to project root
cd /var/www

# Force delete ANY stale cache files immediately
rm -f bootstrap/cache/*.php

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

# Check for empty APP_KEY or missing line
# We use a more robust way to check for a valid key length
KEY_VAL=$(grep "^APP_KEY=" .env | cut -d '=' -f 2 | tr -d ' ' | tr -d '\r')
if [ -z "$KEY_VAL" ] || [ ${#KEY_VAL} -lt 40 ]; then
    echo "Generating new APP_KEY..."
    # Ensure line exists
    if ! grep -q "^APP_KEY=" .env; then
        echo "" >> .env
        echo "APP_KEY=" >> .env
    fi
    php artisan key:generate --force
fi

# Clear and rebuild cache to be absolutely sure
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
