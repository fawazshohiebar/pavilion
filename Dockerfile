# Multi-stage Dockerfile for Production Deployment
# This builds a complete application image with PHP-FPM and Nginx

# Stage 1: Build PHP application
FROM php:8.3-fpm-alpine AS php-base

ENV PHP_USER=laravel
ENV PHP_GROUP=laravel

# Install build dependencies first
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers \
    && apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    gd \
    mbstring \
    intl \
    xml \
    bcmath \
    opcache \
    pcntl \
    posix \
    sockets \
    && apk del .build-deps

# Install Redis extension (optional but recommended for production)
RUN apk add --no-cache --virtual .redis-build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .redis-build-deps

# Configure PHP-FPM
RUN adduser -g ${PHP_GROUP} -s /bin/sh -D ${PHP_USER}
RUN sed -i "s/user = www-data/user = ${PHP_USER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${PHP_GROUP}/g" /usr/local/etc/php-fpm.d/www.conf

# Copy PHP production configuration
COPY docker/php-production.ini /usr/local/etc/php/conf.d/php-production.ini

# Set working directory
WORKDIR /var/www/html

# Copy composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY --chown=${PHP_USER}:${PHP_GROUP} . .

# Install composer dependencies (production optimized)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# Run post-autoload-dump scripts
RUN composer run-script post-autoload-dump

# Clear all caches to ensure fresh state in production
RUN php artisan config:clear || true \
    && php artisan cache:clear || true \
    && php artisan view:clear || true \
    && php artisan route:clear || true \
    && php artisan statamic:stache:clear || true \
    && rm -rf bootstrap/cache/*.php || true \
    && rm -rf storage/framework/cache/data/* || true \
    && rm -rf storage/framework/views/* || true

# Set proper permissions
RUN chown -R ${PHP_USER}:${PHP_GROUP} /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/public

# Stage 2: Build Node.js assets
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy application files needed for build
COPY . .

# Build assets
RUN npm run build

# Stage 3: Final production image with Nginx
FROM nginx:stable-alpine

ENV NGINX_USER=laravel
ENV NGINX_GROUP=laravel

# Install PHP-FPM and supervisor with ALL necessary extensions
RUN apk add --no-cache \
    php83 \
    php83-fpm \
    php83-pdo \
    php83-pdo_mysql \
    php83-pdo_sqlite \
    php83-sqlite3 \
    php83-mysqli \
    php83-zip \
    php83-gd \
    php83-mbstring \
    php83-intl \
    php83-xml \
    php83-bcmath \
    php83-opcache \
    php83-session \
    php83-tokenizer \
    php83-fileinfo \
    php83-ctype \
    php83-dom \
    php83-xmlwriter \
    php83-xmlreader \
    php83-simplexml \
    php83-iconv \
    php83-curl \
    php83-openssl \
    php83-pecl-redis \
    php83-posix \
    php83-pcntl \
    php83-sockets \
    supervisor \
    && ln -sf /usr/bin/php83 /usr/bin/php

# Create user
RUN adduser -g ${NGINX_GROUP} -s /bin/sh -D ${NGINX_USER}

# Copy nginx configuration
COPY docker/nginx.default.conf /etc/nginx/conf.d/default.conf
RUN sed -i "s/user nginx/user ${NGINX_USER}/g" /etc/nginx/nginx.conf

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisord.conf

# Copy startup script
COPY docker/startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Copy application from php-base stage
COPY --from=php-base --chown=${NGINX_USER}:${NGINX_GROUP} /var/www/html /var/www/html

# Copy built assets from node-builder stage
COPY --from=node-builder --chown=${NGINX_USER}:${NGINX_GROUP} /app/public/build /var/www/html/public/build

# Create necessary directories and ensure proper permissions
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/html/database \
    && mkdir -p /var/www/html/cache/lock \
    && mkdir -p /var/www/html/cache/stache \
    && mkdir -p /var/www/html/cache/stache/indexes \
    && mkdir -p /var/www/html/cache/stache/stores \
    && touch /var/www/html/database/database.sqlite \
    && chown -R ${NGINX_USER}:${NGINX_GROUP} /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/database \
    && chmod -R 777 /var/www/html/cache

# Expose port
EXPOSE 80

# Health check - use /health endpoint which returns 200 OK
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD wget --no-verbose --tries=1 --spider http://127.0.0.1:80/health || exit 1

# Use startup script to initialize app and then start supervisor
CMD ["/usr/local/bin/startup.sh"]
