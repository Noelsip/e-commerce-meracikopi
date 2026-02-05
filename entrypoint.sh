#!/bin/sh
set -e

echo "🚀 Starting Meracikopi E-Commerce Application..."

# Create required directories
mkdir -p /var/log/supervisor /var/log/php /var/log/nginx
touch /var/www/html/storage/logs/queue.log
touch /var/www/html/storage/logs/scheduler.log
chown -R www-data:www-data /var/www/html/storage/logs

# Configure PORT for Railway (uses dynamic PORT)
if [ -n "$PORT" ]; then
    echo "📌 Configuring Nginx to use PORT: $PORT"
    sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf
    sed -i "s/listen \[::\]:80;/listen [::]:$PORT;/g" /etc/nginx/http.d/default.conf
fi

# Wait for database to be ready (with shorter timeout for Railway)
echo "⏳ Waiting for database to be ready..."
max_retries=15
retry_count=0

while ! php artisan db:monitor --databases=mysql 2>/dev/null; do
    retry_count=$((retry_count + 1))
    if [ $retry_count -ge $max_retries ]; then
        echo "⚠️ Database not ready after $max_retries attempts, continuing anyway..."
        break
    fi
    echo "Waiting for database... attempt $retry_count/$max_retries"
    sleep 2
done

echo "✅ Moving forward with startup..."

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run migrations with retry
echo "📦 Running database migrations..."
php artisan migrate --force || echo "⚠️ Migration failed, app may still work if already migrated"

# Run database seeder (add this part)
echo "🌱 Running database seeder..."
php artisan db:seed --force || echo "⚠️ Seeding failed, but app will continue"

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