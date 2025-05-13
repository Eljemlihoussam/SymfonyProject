FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers du projet
COPY . .

# Installation des dépendances
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Configuration des permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/var

# Exposition du port
EXPOSE 9000

CMD ["php-fpm"] 