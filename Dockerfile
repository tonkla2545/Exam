# ===============================
# Build Stage
# ===============================
FROM php:8.2-cli AS builder

# System dependencies + PHP extensions
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

# Node.js (for Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy dependency files
COPY composer.json composer.lock package.json package-lock.json ./

# Install all dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader
RUN npm ci

# Copy application files
COPY . .

# Finalize composer
RUN composer dump-autoload --optimize --no-dev

# Set environment for Vite build
ENV APP_URL=https://exam-gov.onrender.com
ENV ASSET_URL=https://exam-gov.onrender.com

# Build Vite assets
RUN npm run build

# ===============================
# Runtime Stage
# ===============================
FROM php:8.2-cli

# System dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
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

WORKDIR /var/www

# Copy application from builder
COPY --from=builder /var/www /var/www

# Copy startup script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Remove node_modules and build artifacts
RUN rm -rf node_modules package*.json vite.config.js

# Permissions
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Expose port
EXPOSE 10000

# Start application
CMD ["/start.sh"]