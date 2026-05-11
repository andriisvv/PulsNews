#!/usr/bin/env bash
set -o errexit

# Встановлюємо PHP-залежності
composer install --no-dev --working-dir=/var/www/html --optimize-autoloader

# Налаштовуємо Laravel для продакшну
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# Запускаємо міграції
php artisan migrate --force