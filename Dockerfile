# 1️⃣ Usa PHP 8.2 com Apache
FROM php:8.2-apache

# 2️⃣ Instala dependências necessárias
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# 3️⃣ Habilita mod_rewrite para URLs amigáveis
RUN a2enmod rewrite

# 4️⃣ Configura o Apache para servir a pasta /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# 5️⃣ Copia o projeto para o container
WORKDIR /var/www/html
COPY . .

# 6️⃣ Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7️⃣ Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# 8️⃣ Corrige permissões
RUN chown -R www-data:www-data storage bootstrap/cache

# 9️⃣ Expõe a porta 80 (HTTP)
EXPOSE 80

# 🔟 Inicia o Apache
CMD ["apache2-foreground"]
