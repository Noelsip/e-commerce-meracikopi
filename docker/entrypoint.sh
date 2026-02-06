#!/bin/sh
set -e

echo "🚀 Starting Meracikopi E-Commerce Application..."

# Create required directories
mkdir -p /var/log/supervisor /var/log/php /var/log/nginx

# Tunggu sebentar agar MySQL siap (opsional di Railway karena ada healthcheck)
echo "⏳ Waiting for services to stabilize..."
sleep 5

echo "✅ Moving forward with startup..."

# Create .env if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo "📝 Creating .env file from environment variables..."
    cat > /var/www/html/.env <<EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-meracikopi}"
DB_USERNAME="${DB_USERNAME:-root}"
DB_PASSWORD="${DB_PASSWORD:-}"

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

REDIS_HOST="${REDIS_HOST:-redis}"
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
EOF
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "📦 Running database migrations..."
php artisan migrate --force

# Clear and cache configs for production
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Set correct permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "🚀 ALL DONE! Starting Nginx & PHP-FPM via Supervisord..."
exec "$@"
