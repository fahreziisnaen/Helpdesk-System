#!/bin/bash

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

php artisan migrate --force
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

php-fpm -D
nginx -g "daemon off;"
