FROM php:8.3-apache

# Встановлення системних залежностей (включно з libpq-dev для PostgreSQL)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Встановлення PHP-розширень (з pdo_pgsql для PostgreSQL)
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Увімкнення mod_rewrite для Apache
RUN a2dismod mpm_event 2>/dev/null; a2dismod mpm_worker 2>/dev/null; a2enmod mpm_prefork rewrite; true

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Робоча директорія
WORKDIR /var/www/html

# Копіюємо файли проекту
COPY . /var/www/html

# Встановлюємо залежності
RUN composer install --no-dev --optimize-autoloader

# Налаштування Apache на public як document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Права на storage і bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Стартовий скрипт
COPY render-start.sh /usr/local/bin/render-start.sh
RUN chmod +x /usr/local/bin/render-start.sh

EXPOSE 80

CMD ["/usr/local/bin/render-start.sh"]
