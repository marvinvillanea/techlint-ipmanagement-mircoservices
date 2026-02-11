FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libxml2-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]
