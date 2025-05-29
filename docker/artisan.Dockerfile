FROM php:8.3-cli-alpine

ENV PHP_GROUP=laravel
ENV PHP_USER=laravel

RUN adduser -g ${PHP_GROUP} -s /bin/sh -D ${PHP_USER}

# Install required PHP extensions for Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Create directory structure
RUN mkdir -p /var/www/html

WORKDIR /var/www/html

# Set proper ownership
RUN chown -R ${PHP_USER}:${PHP_GROUP} /var/www/html

# Switch to non-root user for better security
USER ${PHP_USER}

# Set the entry point to artisan
ENTRYPOINT ["php", "artisan"]
# Default command (can be overridden at runtime)
CMD ["list"]