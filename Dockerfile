FROM php:8.2-fpm

# Instalar dependências
RUN apt-get update && apt-get install -y \
    unzip libzip-dev libpng-dev libonig-dev libxml2-dev curl git \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Instalar Node
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Copiar projeto
WORKDIR /var/www/html
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependências JS e gerar build
RUN npm install
RUN npm run build

# Copiar o build do Vite para o Public
RUN cp -r public/build /var/www/html/public/build

# Permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expor porta
EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
