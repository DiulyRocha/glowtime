FROM php:8.2-cli

# 1) Extensões do PHP necessárias pro Laravel
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev mariadb-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 2) Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# 3) Node.js 18
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# 4) Pasta da aplicação
WORKDIR /app

# 5) Copiar arquivos de dependência primeiro (cache)
COPY composer.json composer.lock package.json package-lock.json ./

# 6) Instalar dependências PHP e JS
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# 7) Copiar o resto do projeto
COPY . .

# 8) Porta usada pelo Railway
ENV PORT=8080
EXPOSE 8080

# 9) Subir servidor embutido do PHP apontando pra pasta public
CMD php -S 0.0.0.0:$PORT -t public
