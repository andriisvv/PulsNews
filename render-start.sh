#!/usr/bin/env bash
set -e

# Кешуємо конфіг (швидше працює)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Створюємо симлінк для storage
php artisan storage:link || true

# Запускаємо міграції
php artisan migrate --force

# Стартуємо Apache
apache2-foreground