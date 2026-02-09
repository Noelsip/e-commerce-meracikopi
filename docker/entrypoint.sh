#!/bin/sh
set -e

echo "[START] Starting Meracikopi E-Commerce Application..."

# Create required directories
mkdir -p /var/log/supervisor /var/log/php /var/log/nginx
touch /var/www/html/storage/logs/queue.log
touch /var/www/html/storage/logs/scheduler.log
chown -R www-data:www-data /var/www/html/storage/logs

# Configure PORT for Railway (uses dynamic PORT)
if [ -n "$PORT" ]; then
    echo "[CONFIG] Configuring Nginx to use PORT: $PORT"
    sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf
    sed -i "s/listen \[::\]:80;/listen [::]:$PORT;/g" /etc/nginx/http.d/default.conf
fi

# Wait for database to be ready (with shorter timeout for Railway)
echo "[WAIT] Waiting for database to be ready..."
max_retries=15
retry_count=0

while ! php artisan db:monitor --databases=mysql 2>/dev/null; do
    retry_count=$((retry_count + 1))
    if [ $retry_count -ge $max_retries ]; then
        echo "[WARN] Database not ready after $max_retries attempts, continuing anyway..."
        break
    fi
    echo "Waiting for database... attempt $retry_count/$max_retries"
    sleep 2
done

echo "[OK] Moving forward with startup..."

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "[KEY] Generating application key..."
    php artisan key:generate --force
fi

# Run migrations with retry
echo "[MIGRATE] Running database migrations..."
php artisan migrate --force || echo "[WARN] Migration failed, app may still work if already migrated"

# Check if we need to seed the database
echo "[SEED] Checking if database needs seeding..."
user_count=$(php artisan tinker --execute="echo \App\Models\User::count();")
if [ "$user_count" -eq "0" ] || [ "$FORCE_SEED" = "true" ]; then
    echo "[SEED] Running database seeder..."
    php artisan db:seed --force || echo "[WARN] Seeding failed, but app will continue"
else
    echo "[OK] Database already has data, skipping seed"
fi

# Clear and cache configs for production
echo "[OPTIMIZE] Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "[LINK] Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Set correct permissions
echo "[PERM] Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "[DONE] ALL DONE! Starting Nginx & PHP-FPM via Supervisord..."
exec "$@"