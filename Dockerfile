FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /beerapi

# Copy existing application directory contents
COPY ./beerapi .

RUN composer install --prefer-dist --no-interaction

# run with artisan just for development purposes
CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]
