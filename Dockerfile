FROM dunglas/frankenphp:latest

# Instalar dependências do sistema
RUN apk add --no-cache bash git curl oniguruma-dev libxml2-dev mariadb-client nodejs npm

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

WORKDIR /app

COPY . .

# Instalar Composer e dependências
RUN composer install --no-dev --optimize-autoloader

# Instalar Node e buildar assets
RUN npm install && npm run build

# Garantir permissões de storage
RUN chmod -R 777 storage bootstrap/cache

# Porta usada pelo Railway
ENV PORT=8080
EXPOSE 8080

# Servir Laravel com FrankenPHP (MUITO mais rápido que artisan)
CMD ["php", "artisan", "frankenphp:serve", "--host=0.0.0.0", "--port=8080"]
