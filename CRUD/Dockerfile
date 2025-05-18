FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Installation des extensions PHP nécessaires à Symfony
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Définition du dossier de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . .

# Installer les dépendances PHP sans exécuter les scripts auto (évite l'erreur symfony-cmd)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Créer les dossiers cache/log requis
RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 var

EXPOSE 9000

CMD ["php-fpm"]
