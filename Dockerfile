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

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy package files
COPY package*.json ./
RUN npm install

# Copy composer files
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader

# Copy ALL application files
COPY . .

# Dump autoload
RUN composer dump-autoload --optimize --no-dev

# Build assets and show output
RUN npm run build && \
    echo "=== BUILD COMPLETE ===" && \
    ls -lah public/ && \
    ls -lah public/build/ && \
    cat public/build/manifest.json

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

# Copy EVERYTHING from builder
COPY --from=builder /var/www /var/www

# Verify files exist
RUN echo "=== CHECKING FILES ===" && \
    ls -lah /var/www/public/build/ && \
    test -f /var/www/public/build/manifest.json && \
    echo "✓ Manifest exists!"

# Copy startup script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Clean up
RUN rm -rf node_modules

# Permissions
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000

CMD ["/start.sh"]