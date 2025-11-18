FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev mariadb-client nodejs npm

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

ENV PORT=8080
EXPOSE 8080

CMD php -S 0.0.0.0:$PORT -t public
