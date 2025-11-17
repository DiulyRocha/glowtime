#!/usr/bin/env bash

# Instala dependências do PHP
composer install --no-dev --optimize-autoloader

# Instala dependências do JS
npm install

# Build do Vite
npm run build

# Otimizações
php artisan config:cache
php artisan route:cache
php artisan view:cache
