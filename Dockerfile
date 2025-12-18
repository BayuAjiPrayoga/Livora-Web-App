FROM php:8.3-cli AS builder

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
    ls -lah /app/public/build/.vite/ 2>/dev/null || true && \
    if [ -f /app/public/build/manifest.json ]; then \
        echo "✓ SUCCESS: Manifest at correct location!"; \
        cat /app/public/build/manifest.json | head -20; \
    elif [ -f /app/public/build/.vite/manifest.json ]; then \
        echo "⚠ Manifest in .vite folder, copying to root..."; \
        cp /app/public/build/.vite/manifest.json /app/public/build/manifest.json && \
        echo "✓ Manifest copied successfully!"; \
    else \
        echo "✗ ERROR: Manifest not found anywhere!"; \
        echo "Build log:"; \
        cat build.log; \
        exit 1; \
    fi

# Install composer dependencies after npm build
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Final stage - use FPM for better performance
FROM php:8.3-fpm

# Install runtime dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    nginx \
    supervisor \
    default-mysql-client \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure intl && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

WORKDIR /app

# Copy built application from builder
COPY --from=builder /app /app

# Create www-data user directories and set proper ownership
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache && \
    chmod -R 755 /app/public

# Copy nginx config
COPY <<EOF /etc/nginx/sites-available/default
server {
    listen 8080;
    server_name _;
    root /app/public;
    index index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
EOF

# Copy supervisor config
COPY <<EOF /etc/supervisor/conf.d/supervisord.conf
[supervisord]
nodaemon=true
user=root

[program:php-fpm]
command=/usr/local/sbin/php-fpm --nodaemonize
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=/usr/sbin/nginx -g 'daemon off;'
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
EOF

# Expose port
EXPOSE 8080

# Start script
CMD php artisan migrate --force || true && \
    php artisan config:clear && \
    php artisan storage:link || true && \
    /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
