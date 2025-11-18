FROM php:8.2-cli

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev mariadb-client nodejs npm

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Preparar aplicação
WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Porta padrão do Railway
ENV PORT=8080
EXPOSE 8080

# ⬇️ NOVO COMANDO (SEM artisan serve!)
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public"]
