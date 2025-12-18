FROM php:8.3-cli

# Install system dependencies
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
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-configure intl && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy existing application directory
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# Install npm dependencies and build assets with verbose output
ENV NODE_ENV=production
RUN npm ci && \
    npm run build && \
    ls -la /app/public/build || echo "Build directory not found" && \
    test -f /app/public/build/manifest.json && echo "Manifest found!" || echo "ERROR: Manifest not generated!"

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
