FROM php:8.2-cli

# Extensões necessárias
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev mariadb-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Node
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app

# Primeiro copia TODO o projeto
COPY . .

# Só agora instala dependências
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# Config Railway
ENV PORT=8080
EXPOSE 8080

# Permite rodar o script de migração
RUN chmod +x /app/railway-migrate.sh


# Servidor PHP embutido apontando para /public
CMD sh /app/railway-migrate.sh && php -S 0.0.0.0:$PORT -t public


