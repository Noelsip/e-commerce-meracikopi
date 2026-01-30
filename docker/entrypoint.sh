#!/bin/sh
set -e

echo "🚀 Starting Meracikopi E-Commerce Application..."

# Create required directories
mkdir -p /var/log/supervisor /var/log/php /var/log/nginx

# Tunggu sebentar agar MySQL siap (opsional di Railway karena ada healthcheck)
echo "⏳ Waiting for services to stabilize..."
sleep 5

echo "✅ Moving forward with startup..."

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

echo "✅ Application ready! Starting services..."

# Execute the main command
exec "$@"
