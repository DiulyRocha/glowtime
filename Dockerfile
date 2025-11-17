# Etapa 1: Build do PHP + Composer + Node + Vite
FROM php:8.2-fpm AS builder

# Instalar dependências
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar projeto
WORKDIR /app
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependências JS
RUN npm install && npm run build

# Etapa 2: Servidor Nginx + PHP-FPM
FROM nginx:latest

# Copiar arquivos do build
COPY --from=builder /app /var/www/html

# Configurar Nginx
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Expor porta
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
