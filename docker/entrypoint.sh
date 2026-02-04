#!/bin/sh
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "${GREEN}Starting Laravel application setup...${NC}"

# Wait for database to be ready (if using external DB)
if [ ! -z "$DB_HOST" ]; then
    echo "${YELLOW}Waiting for database to be ready...${NC}"
    until nc -z -v -w30 $DB_HOST ${DB_PORT:-3306}
    do
        echo "${YELLOW}Waiting for database connection...${NC}"
        sleep 5
    done
    echo "${GREEN}Database is ready!${NC}"
fi

# Ensure proper permissions
echo "${YELLOW}Setting file permissions...${NC}"
chown -R laravel:laravel /var/www/html/storage
chown -R laravel:laravel /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Create directories if they don't exist
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs

# Cache configuration if not in development
if [ "$APP_ENV" != "local" ] && [ "$APP_ENV" != "development" ]; then
    echo "${YELLOW}Caching configuration...${NC}"
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Run migrations (optional - uncomment if needed)
# echo "${YELLOW}Running database migrations...${NC}"
# php artisan migrate --force --no-interaction

# Create storage link if it doesn't exist
if [ ! -L /var/www/html/public/storage ]; then
    echo "${YELLOW}Creating storage symlink...${NC}"
    php artisan storage:link
fi

# Clear Statamic caches
echo "${YELLOW}Clearing Statamic caches...${NC}"
php artisan statamic:stache:clear || true
php artisan statamic:static:clear || true

echo "${GREEN}Application setup complete!${NC}"

# Execute the main command
exec "$@"
