FROM php:7.4-cli

RUN apt-get update -y && apt-get install -y libmcrypt-dev libonig-dev libzip-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring zip

WORKDIR /app

COPY composer.* ./
RUN composer install --no-dev --no-scripts

COPY . /app

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
