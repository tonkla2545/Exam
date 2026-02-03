#!/bin/sh
set -e

echo "🚀 Running migrations..."
php artisan migrate --force

echo "⚡ Caching..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🌐 Starting server..."
exec php artisan serve --host=0.0.0.0 --port=10000