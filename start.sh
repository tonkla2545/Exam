#!/bin/sh
set -e

echo "=== DEBUG: Checking build files ==="
ls -lah /var/www/public/build/
echo ""
echo "=== Manifest content ==="
cat /var/www/public/build/manifest.json || echo "No manifest.json"
echo ""

echo "🚀 Running migrations..."
php artisan migrate --force

echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "⚡ Caching..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🌐 Starting server..."
exec php artisan serve --host=0.0.0.0 --port=10000