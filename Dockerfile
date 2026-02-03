FROM php:8.2-cli

# ===============================
# System dependencies
# ===============================
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# ===============================
# Node.js (for Vite)
# ===============================
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# ===============================
# Composer
# ===============================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# ===============================
# Copy project
# ===============================
COPY . .

# ===============================
# Install PHP deps
# ===============================
RUN composer install --no-dev --optimize-autoloader

# ===============================
# Install JS deps + build Vite
# ===============================
RUN npm install
RUN npm run build

# ===============================
# Permission
# ===============================
RUN chmod -R 777 storage bootstrap/cache

# ===============================
# Expose Render port
# ===============================
EXPOSE 10000

# ===============================
# Start Laravel
# ===============================
CMD php artisan serve --host=0.0.0.0 --port=10000
