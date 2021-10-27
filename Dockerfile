FROM php:7.4-cli

RUN apt-get update -y && apt-get install -y libmcrypt-dev libonig-dev libzip-dev libpq-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring zip pdo_pgsql pgsql

WORKDIR /app
COPY . ./

RUN composer install

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
