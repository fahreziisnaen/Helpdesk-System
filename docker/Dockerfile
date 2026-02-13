FROM node:20 as build

WORKDIR /app
COPY src/package.json src/package-lock.json ./
RUN npm ci
COPY src .
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

# Copy composer files first for layer caching
COPY src/composer.json src/composer.lock ./

# Install PHP dependencies (this layer will be cached unless composer files change)
RUN composer install --no-scripts --no-autoloader --no-dev

# Copy existing application directory contents
COPY src /var/www

# Run composer dump-autoload now that source is copied
RUN composer dump-autoload --optimize --no-dev

# Copy built assets from build stage
COPY --from=build /app/public/build /var/www/public/build

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Create system user to run Composer and Artisan Commands
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
