FROM php:8.3-cli

# Install system dependencies including Node 20
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    ca-certificates \
    gnupg

# Install Node.js 20.x
RUN mkdir -p /etc/apt/keyrings && \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && \
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list && \
    apt-get update && \
    apt-get install -y nodejs

# Verify Node and npm versions
RUN node --version && npm --version

# Install PHP extensions
RUN docker-php-ext-configure intl && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy package files first for better caching
COPY package*.json ./

# Install npm dependencies
RUN npm ci --verbose

# Copy all source files
COPY . .

# Build assets with Vite - with proper error handling
RUN echo "========================================" && \
    echo "Building Vite assets..." && \
    echo "========================================" && \
    npm run build 2>&1 | tee build.log && \
    echo "========================================" && \
    echo "Checking build output..." && \
    echo "========================================" && \
    ls -lah /app/public/build/ || echo "ERROR: Build directory not found!" && \
    if [ -f /app/public/build/manifest.json ]; then \
        echo "✓ SUCCESS: Manifest generated!"; \
        echo "Manifest contents:"; \
        cat /app/public/build/manifest.json | head -20; \
    else \
        echo "✗ ERROR: Manifest not found!"; \
        echo "Build log:"; \
        cat build.log; \
        exit 1; \
    fi

# Install composer dependencies after npm build
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# Ensure build directory exists and has correct permissions
RUN mkdir -p /app/public/build && \
    chmod -R 755 /app/public

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Set permissions
RUN chmod -R 755 /app/storage /app/bootstrap/cache

# Expose port
EXPOSE 8080

# Start application
CMD php artisan migrate --force || true && \
    php artisan config:clear && \
    php artisan storage:link || true && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
