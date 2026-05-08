FROM php:8.3-fpm-alpine
RUN apk add --no-cache libpq-dev nodejs npm
RUN docker-php-ext-install pdo_pgsql
WORKDIR /var/www
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-interaction --optimize-autoloader
RUN npm install && npm run build
RUN chown -R www-data:www-data storage bootstrap/cache
