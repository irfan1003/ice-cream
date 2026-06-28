FROM php:8.3-fpm

# Install dependensi sistem Linux, Chromium, dan libzip-dev
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    chromium \
    gnupg \
    libicu-dev \
    libzip-dev


RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql gd zip intl

# Install NodeJS dan NPM secara global di server
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install ekstensi PHP (Sekarang ditambah 'zip' di bagian akhir)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Ambil Composer terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set folder kerja di dalam server
WORKDIR /var/www

# Copy seluruh projek Anda ke server
COPY . /var/www

# Jalankan instalasi composer untuk produksi
RUN composer install --no-dev --optimize-autoloader

# Atur hak akses folder storage agar bisa menulis PDF dan gambar produk
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
