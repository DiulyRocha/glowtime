FROM php:8.2-cli


# Instalar dependências do sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev mariadb-client nodejs npm \
    && docker-php-ext-install pdo pdo_mysql


# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer


# Preparar aplicação
WORKDIR /app
COPY . .


RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build


ENV PORT=8080
EXPOSE 8080


CMD php -S 0.0.0.0:8080 -t public