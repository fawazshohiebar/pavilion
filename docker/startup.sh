#!/bin/sh

# Startup script to initialize Laravel/Statamic application
# This runs AFTER environment variables are loaded

set -e

echo "🚀 Starting application initialization..."

# Navigate to application directory
cd /var/www/html

# Clear all caches to ensure fresh state with production env vars
echo "🧹 Clearing caches..."
php artisan optimize:clear 2>/dev/null || true
php artisan statamic:stache:clear 2>/dev/null || true

# Warm up caches
echo "🔥 Warming up caches..."
php artisan statamic:stache:warm 2>/dev/null || true

# Ensure proper permissions
echo "🔐 Setting permissions..."
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/database

# Ensure SQLite database exists
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "📝 Creating SQLite database..."
    touch /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database/database.sqlite
fi

echo "✅ Application initialization complete!"

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisord.conf
