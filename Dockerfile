# Multi-stage Dockerfile for Laravel/Statamic Production
# Optimized for fast builds and reliability

# Stage 1: Install PHP dependencies with Composer
FROM composer:latest AS composer-base

WORKDIR /app

# Copy composer files first (layer caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

# Copy application code
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --classmap-authoritative

# Stage 2: Build frontend assets
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm ci --no-audit --no-fund

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

RUN npm run build

# Stage 3: Final production image
# Use PHP-FPM Alpine and install pre-built PHP extensions from Alpine repos
FROM php:8.3-fpm-alpine

# Install ALL necessary PHP extensions + Nginx + Supervisor
# Using Alpine's pre-built packages (NO compilation needed!)
RUN apk add --no-cache \
    php83-pdo \
    php83-pdo_mysql \
    php83-pdo_sqlite \
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
    nginx \
    supervisor \
    && ln -sf /usr/bin/php83 /usr/bin/php

# Create laravel user
RUN adduser -g laravel -s /bin/sh -D laravel 2>/dev/null || true

# Configure PHP-FPM to listen on 127.0.0.1:9000 (not socket)
RUN echo "[www]" > /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "listen = 127.0.0.1:9000" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "pm.max_children = 20" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "pm.start_servers = 2" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "pm.min_spare_servers = 1" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "pm.max_spare_servers = 3" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Setup Nginx directories
RUN mkdir -p /run/nginx /var/log/nginx

# Copy configs
COPY docker/nginx.default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Copy application from composer-base
COPY --from=composer-base --chown=laravel:laravel /app /var/www/html

# Copy built assets from node-builder
COPY --from=node-builder --chown=laravel:laravel /app/public/build /var/www/html/public/build

# Create all necessary directories with proper permissions
RUN mkdir -p \
    /var/www/html/storage/logs \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/bootstrap/cache \
    /var/www/html/database \
    /var/www/html/cache/lock \
    /var/www/html/cache/stache \
    /var/www/html/cache/stache/indexes \
    /var/www/html/cache/stache/stores \
    && touch /var/www/html/database/database.sqlite \
    && chown -R laravel:laravel /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/database \
    && chmod -R 777 /var/www/html/cache

WORKDIR /var/www/html

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD wget --no-verbose --tries=1 --spider http://127.0.0.1:80/health || exit 1

CMD ["/usr/local/bin/startup.sh"]
