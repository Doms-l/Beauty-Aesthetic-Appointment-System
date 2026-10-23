
# Build React and Vite assets
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build


# Install Laravel dependencies
FROM composer:2 AS vendor

WORKDIR /app

COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader


# Run Laravel using PHP 8.4 and Nginx
FROM serversideup/php:8.4-fpm-nginx

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV NGINX_WEBROOT=/var/www/html/public

USER root
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache
USER www-data

EXPOSE 8080
