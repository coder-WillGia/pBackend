# --- ETAPA 1: Construccion (Descarga de dependencias con Composer) ---
FROM composer:2.8 AS builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-plugins \
    --no-scripts

# --- ETAPA 2: Produccion (Servidor PHP ultra ligero basado en Alpine) ---
FROM php:8.4-fpm-alpine
WORKDIR /var/www

# Instalar dependencias basicas del sistema y extensiones de MySQL
RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Copiar el codigo fuente del proyecto (excluyendo lo ignorado en .dockerignore)
COPY . .

# Copiar las dependencias optimizadas de PHP desde la Etapa 1
COPY --from=builder /app/vendor ./vendor

# Ajustar permisos para la cache y almacenamiento de Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Cambiar al usuario de ejecucion segura por defecto en Alpine
USER www-data

# Levantar servidor HTTP de Laravel escuchando directamente y de forma obligatoria en la variable de entorno PORT
CMD php artisan serve --host=0.0.0.0 --port=$PORT
