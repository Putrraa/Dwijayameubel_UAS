FROM dunglas/frankenphp:php8.3-bookworm

# System packages + PHP extensions (termasuk gd untuk phpspreadsheet)
RUN apt-get update && apt-get install -y \
        ca-certificates git unzip zip \
        libgd-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libzip-dev libicu-dev \
    && rm -rf /var/lib/apt/lists/* \
    && install-php-extensions gd zip pdo_mysql bcmath exif intl opcache

# Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

ENV COMPOSER_ALLOW_SUPERUSER=1

# PHP deps (layer terpisah agar di-cache saat kode berubah)
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Node deps
COPY package.json package-lock.json* ./
RUN npm install

# Kode aplikasi
COPY . .

# Build assets & setup Laravel
RUN npm run build \
    && npm prune --omit=dev --ignore-scripts \
    && mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Web server config
COPY Caddyfile /Caddyfile

# Startup script: cache + migrate + server (semua artisan optimize di sini, bukan build time)
RUN printf '#!/bin/sh\nset -e\nphp artisan config:cache\nphp artisan event:cache\nphp artisan route:cache\nphp artisan view:cache\nphp artisan migrate --force\nexec frankenphp run --config /Caddyfile\n' > /start.sh \
    && chmod +x /start.sh

CMD ["/start.sh"]
