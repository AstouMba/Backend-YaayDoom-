FROM composer:2.7 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts --ignore-platform-req=php

FROM php:8.4-cli-alpine

RUN apk add --no-cache bash postgresql-client postgresql-dev libzip-dev icu-dev oniguruma-dev \
    && docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor
COPY . ./
COPY docker/backend-entrypoint.sh /usr/local/bin/backend-entrypoint.sh

RUN chmod +x /usr/local/bin/backend-entrypoint.sh \
    && mkdir -p storage/framework/{cache,data,sessions,testing,views} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 600 storage/oauth-private.key \
    && chmod 660 storage/oauth-public.key

EXPOSE 8000

ENTRYPOINT ["backend-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
