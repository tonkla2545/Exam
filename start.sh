#!/bin/sh
set -e

echo "🚀 Running migrations..."
php artisan migrate --force

echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🌐 Starting server..."
# Force HTTPS scheme
export APP_URL=https://exam-gov.onrender.com
exec php artisan serve --host=0.0.0.0 --port=10000