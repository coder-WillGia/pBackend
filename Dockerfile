FROM php:8.3-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql bcmath zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Install composer dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Expose port 8000
EXPOSE 8000

# Start Laravel development server
CMD php artisan serve --host=0.0.0.0 --port=8000
