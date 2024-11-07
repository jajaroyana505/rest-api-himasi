# Gunakan image PHP dengan Apache
FROM php:7.4-apache

# Install ekstensi yang diperlukan (misalnya mysqli untuk MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Salin semua file dari direktori lokal ke dalam /var/www/html di dalam container
COPY . /var/www/html

# Atur izin direktori cache dan writable
RUN chown -R www-data:www-data /var/www/html/application/cache /var/www/html/application/logs

# Aktifkan mod_rewrite Apache untuk CodeIgniter
RUN a2enmod rewrite

# Atur direktori kerja
WORKDIR /var/www/html
