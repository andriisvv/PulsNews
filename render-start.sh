#!/usr/bin/env bash
set -e

# ─── ФІКС: "More than one MPM loaded" (AH00534) ───
a2dismod mpm_event mpm_worker 2>/dev/null || true
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*
a2enmod mpm_prefork 2>/dev/null || true
rm -f /var/run/apache2/apache2.pid

# ─── Railway передає динамічний PORT; локально за замовчуванням 80 ───
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# ─── Міграції бази даних ───
php artisan migrate --force

# ─── Початкове наповнення новинами (лише якщо БД порожня) ───
php artisan tinker --execute="
if (\App\Models\News::count() === 0) {
    Artisan::call('db:seed', ['--class' => 'NewsSeeder', '--force' => true]);
    echo 'Seeded news';
} else {
    echo 'News already present';
}
"

# ─── Створення адміністратора (логін/пароль беруться з env-змінних) ───
php artisan tinker --execute="
\$email = env('ADMIN_EMAIL', 'admin@pulsnews.com');
\$password = env('ADMIN_PASSWORD', 'PulseAdmin2026!');
if (!\App\Models\User::where('email', \$email)->exists()) {
    \App\Models\User::create(['name' => 'Admin', 'email' => \$email, 'password' => bcrypt(\$password)]);
    echo 'Admin created: ' . \$email;
} else {
    echo 'Admin already exists';
}
"

# ─── Симлінк для завантажених зображень ───
php artisan storage:link || true

# ─── Кешування конфігурації для продакшну ───
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ─── Старт Apache ───
apache2-foreground
