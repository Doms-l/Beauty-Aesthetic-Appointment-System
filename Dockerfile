
# Build React and Vite assets
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

# Laravel PHP application
FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .
COPY --from=frontend /app/public/build ./public/build

ENV WEBROOT=/var/www/html/public
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV RUN_SCRIPTS=true
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 80

CMD ["/start.sh"]
