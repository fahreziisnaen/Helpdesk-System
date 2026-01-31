FROM node:20 as build

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy built assets from build stage
COPY --from=build /app/public/build /var/www/public/build

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Create system user to run Composer and Artisan Commands
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache \
    && chmod +x /var/www/docker/entrypoint.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000
ENTRYPOINT ["/var/www/docker/entrypoint.sh"]
