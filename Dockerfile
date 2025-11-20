FROM php:8.2-cli

# Instalar extensões e dependências
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev mariadb-client nodejs npm

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Definir pasta da aplicação
WORKDIR /app

# Copiar tudo
COPY . .

# Instalar dependências PHP e JS
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Porta padrão do Railway
ENV PORT=8080
EXPOSE 8080

# Iniciar a aplicação corretamente (RODA MIGRATIONS AQUI!)
CMD php artisan migrate --force && php -S 0.0.0.0:$PORT -t public
