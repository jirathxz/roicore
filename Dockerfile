# Production Dockerfile for ROiCORE on Render
FROM php:8.3-apache

# Install system dependencies and required PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Configure Apache DocumentRoot to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && echo "<Directory /var/www/html/public>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy composer definition and install production dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# Copy application source code
COPY . .

# Generate optimized autoloader and set permissions
RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p storage/data storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage \
    && chmod +x /var/www/html/docker-entrypoint.sh

# Expose default HTTP port
EXPOSE 80

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
