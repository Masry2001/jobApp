# ============================================
# Stage 1: Build frontend assets
# ============================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install ALL dependencies including devDependencies
RUN npm ci

# Copy source files needed for build
COPY . .

# Build assets
RUN npm run build

# ============================================
# Stage 2: Production image
# ============================================
FROM dunglas/frankenphp:latest

# Install system dependencies (only what's needed)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    poppler-utils \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

# Install PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    mysqli \
    gd \
    zip \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files first (for caching)
COPY composer.json composer.lock ./

# Install Composer dependencies (production only)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --optimize-autoloader

# Copy the application
COPY . .

# Copy built frontend assets from builder stage
COPY --from=frontend-builder /app/public/build ./public/build

# Copy PHP configuration
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Generate optimized autoload files
RUN composer dump-autoload --optimize --classmap-authoritative

# Create necessary directories and set permissions
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Create entrypoint script
RUN echo '#!/bin/sh\n\
set -e\n\
\n\
echo "Running migrations..."\n\
php artisan migrate --force\n\
\n\
echo "Seeding database..."\n\
php artisan db:seed --force\n\
\n\
echo "Optimizing Laravel..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "Starting FrankenPHP..."\n\
exec frankenphp run --config /etc/caddy/Caddyfile\n\
' > /entrypoint.sh && chmod +x /entrypoint.sh

# Expose port (Railway will set this dynamically)
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD php artisan tinker --execute="echo 'OK';" || exit 1

# Use entrypoint script
ENTRYPOINT ["/entrypoint.sh"]