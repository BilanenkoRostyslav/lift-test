FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libpq-dev \
    librabbitmq-dev \
    && pecl install amqp mongodb\
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl bcmath gd \
    && docker-php-ext-enable amqp mongodb\
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.8.8 /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html
COPY . .

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var


EXPOSE 9000

CMD ["php-fpm"]
