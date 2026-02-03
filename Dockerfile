FROM php:8.2-cli

# ===============================
# System dependencies + PHP extensions
# ===============================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        gd \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ===============================
# Node.js (for Vite)
# ===============================
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ===============================
# Composer
# ===============================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# ===============================
# Copy composer files first (cache optimization)
# ===============================
COPY composer.json composer.lock ./

# ===============================
# Install PHP dependencies
# ===============================
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader

# ===============================
# Copy package files (cache optimization)
# ===============================
COPY package*.json ./

# ===============================
# Install JS dependencies
# ===============================
RUN npm ci --only=production

# ===============================
# Copy rest of the project
# ===============================
COPY . .

# ===============================
# Finalize composer autoload
# ===============================
RUN composer dump-autoload --optimize --no-dev

# ===============================
# Build Vite assets
# ===============================
RUN npm run build

# ===============================
# Clean up
# ===============================
RUN rm -rf node_modules \
    && npm cache clean --force

# ===============================
# Permissions
# ===============================
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# ===============================
# Expose Render port
# ===============================
EXPOSE 10000

# ===============================
# Start Laravel
# ===============================
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=10000