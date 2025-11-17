FROM ubuntu:22.04

# Atualiza pacotes
RUN apt-get update && apt-get install -y \
    software-properties-common

# Instala PHP 8.2 + extensões
RUN add-apt-repository ppa:ondrej/php -y && apt-get update && apt-get install -y \
    php8.2 \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-xml \
    php8.2-curl \
    php8.2-mbstring \
    php8.2-zip \
    php8.2-bcmath \
    php8.2-gd \
    php8.2-cli \
    nginx \
    git \
    curl \
    unzip \
    zip \
    nodejs \
    npm

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar diretório da aplicação
WORKDIR /var/www/html

# Copiar projeto
COPY . .

# Instalar dependências Laravel
RUN composer install --no-dev --optimize-autoloader

# Build do Vite
RUN npm install && npm run build

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Habilitar PHP-FPM no Nginx
RUN sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.2/fpm/php.ini

# Expõe a porta
EXPOSE 80

# Start services
CMD service php8.2-fpm start && nginx -g "daemon off;"
