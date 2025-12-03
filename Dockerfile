FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer.json and composer.lock
COPY composer.json ./

# Install dependencies and update composer.lock for compatibility with PHP 8.3
RUN composer update --no-dev --optimize-autoloader
RUN composer dump-autoload --optimize

# Copy the rest of the application code
COPY . .

