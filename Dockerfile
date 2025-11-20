FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    unzip libzip-dev libpng-dev libonig-dev libxml2-dev curl git \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Instalar Node.js (necessário para Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependências JS e gerar build
RUN npm install && npm run build

CMD ["php-fpm"]
