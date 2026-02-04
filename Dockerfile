# Multi-stage Dockerfile for Production Deployment
# This builds a complete application image with PHP-FPM and Nginx

# Stage 1: Build PHP application - Use Statamic's pre-built image!
FROM statamic/cli:latest AS php-base

ENV PHP_USER=laravel
ENV PHP_GROUP=laravel

# Statamic CLI image already has:
# - PHP 8.3
# - All required extensions (gd, zip, mbstring, intl, xml, bcmath, opcache, redis, etc.)
# - Composer
# This saves 5+ minutes of build time!

# Create user
RUN adduser -g ${PHP_GROUP} -s /bin/sh -D ${PHP_USER} 2>/dev/null || true

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for better layer caching)
COPY composer.json composer.lock ./

# Install composer dependencies (production optimized)
# This layer will be cached if composer files haven't changed
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

# Copy application files
COPY --chown=${PHP_USER}:${PHP_GROUP} . .

# Generate optimized autoloader and run post-autoload-dump
# Some packages need this to register properly
RUN composer dump-autoload --optimize --classmap-authoritative \
    && composer run-script post-autoload-dump || true

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

# Copy package files first (for better layer caching)
COPY package*.json ./

# Install dependencies (this layer will be cached if package.json hasn't changed)
RUN npm ci --no-audit --no-fund

# Copy only necessary files for build (not entire project)
COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

# Build assets
RUN npm run build

# Stage 3: Final production image with PHP and Nginx
# Start from Statamic CLI which already has PHP 8.3 + all extensions
FROM statamic/cli:latest

ENV PHP_USER=laravel
ENV PHP_GROUP=laravel

# Install Nginx and supervisor (much lighter than installing all PHP extensions!)
RUN apk add --no-cache \
    nginx \
    supervisor

# Create user (may already exist in statamic/cli)
RUN adduser -g ${PHP_GROUP} -s /bin/sh -D ${PHP_USER} 2>/dev/null || true

# Setup Nginx
RUN mkdir -p /run/nginx \
    && mkdir -p /var/log/nginx

# Copy nginx configuration
COPY docker/nginx.default.conf /etc/nginx/conf.d/default.conf
# Don't modify nginx.conf user - we'll run nginx as laravel user via supervisor

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisord.conf

# Copy startup script
COPY docker/startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Copy application from php-base stage
COPY --from=php-base --chown=${PHP_USER}:${PHP_GROUP} /var/www/html /var/www/html

# Copy built assets from node-builder stage (entire build directory with manifest)
COPY --from=node-builder --chown=${PHP_USER}:${PHP_GROUP} /app/public/build /var/www/html/public/build

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
    && chown -R ${PHP_USER}:${PHP_GROUP} /var/www/html \
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
