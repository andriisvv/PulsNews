#!/usr/bin/env bash
set -e

# Кешуємо конфіг
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Створюємо симлінк для storage
php artisan storage:link || true

# Запускаємо міграції
php artisan migrate --force

# Створюємо адмін-користувача (якщо ще немає)
php artisan tinker --execute="
if (!\App\Models\User::where('email', 'admin@pulsnews.com')->exists()) {
    \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@pulsnews.com',
        'password' => bcrypt('PulseAdmin2026!'),
    ]);
    echo 'Admin user created';
} else {
    echo 'Admin user already exists';
}
"

# Стартуємо Apache
apache2-foreground