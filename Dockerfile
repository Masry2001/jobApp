FROM php:8.4-cli

# Install system dependencies including poppler-utils for PDF text extraction
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    poppler-utils \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Verify poppler-utils installation
RUN pdftotext -v

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Fix Git ownership issue
RUN git config --global --add safe.directory /var/www

# Create Laravel directories
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache

EXPOSE 8001 5174