#!/bin/sh

# Startup script to initialize Laravel/Statamic application
# This runs AFTER environment variables are loaded

set -e

echo "🚀 Starting application initialization..."

# Navigate to application directory
cd /var/www/html

# Create .env file from environment variables (Critical for Laravel!)
echo "📝 Creating .env file from environment variables..."
cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_TIMEZONE="${APP_TIMEZONE:-UTC}"
APP_URL="${APP_URL:-http://localhost}"

APP_LOCALE="${APP_LOCALE:-en}"
APP_FALLBACK_LOCALE="${APP_FALLBACK_LOCALE:-en}"
APP_FAKER_LOCALE="${APP_FAKER_LOCALE:-en_US}"

APP_MAINTENANCE_DRIVER="${APP_MAINTENANCE_DRIVER:-file}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"
LOG_STACK="${LOG_STACK:-single}"
LOG_DEPRECATIONS_CHANNEL="${LOG_DEPRECATIONS_CHANNEL:-null}"
LOG_LEVEL="${LOG_LEVEL:-debug}"

DB_CONNECTION="${DB_CONNECTION:-sqlite}"
DB_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"

SESSION_DRIVER="${SESSION_DRIVER:-file}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"
SESSION_ENCRYPT="${SESSION_ENCRYPT:-false}"
SESSION_PATH="${SESSION_PATH:-/}"
SESSION_DOMAIN="${SESSION_DOMAIN:-null}"

BROADCAST_CONNECTION="${BROADCAST_CONNECTION:-log}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

CACHE_DRIVER="${CACHE_DRIVER:-file}"
CACHE_PREFIX="${CACHE_PREFIX:-}"

MEMCACHED_HOST="${MEMCACHED_HOST:-127.0.0.1}"

REDIS_HOST="${REDIS_HOST:-127.0.0.1}"
REDIS_PASSWORD="${REDIS_PASSWORD:-null}"
REDIS_PORT="${REDIS_PORT:-6379}"

MAIL_MAILER="${MAIL_MAILER:-log}"
MAIL_HOST="${MAIL_HOST:-127.0.0.1}"
MAIL_PORT="${MAIL_PORT:-2525}"
MAIL_USERNAME="${MAIL_USERNAME:-null}"
MAIL_PASSWORD="${MAIL_PASSWORD:-null}"
MAIL_ENCRYPTION="${MAIL_ENCRYPTION:-null}"
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-hello@example.com}"
MAIL_FROM_NAME="${MAIL_FROM_NAME:-\${APP_NAME}}"

VITE_APP_NAME="${VITE_APP_NAME:-\${APP_NAME}}"
EOF

echo "✅ .env file created successfully"

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
