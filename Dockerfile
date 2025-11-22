FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev mariadb-client nodejs npm

# Extensões PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /app

# Copiar tudo
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependências JS e gerar build
RUN npm install && npm run build

# Otimizações Laravel
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Porta para o Railway
EXPOSE 8080
ENV PORT=8080

# Rodar servidor Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080
