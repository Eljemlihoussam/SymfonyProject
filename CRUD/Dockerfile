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

# Installation de Composer depuis l'image officielle
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Définition du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers du projet Symfony (ajustable si tu veux ignorer certains fichiers)
COPY . .

# Création des dossiers nécessaires pour Symfony
RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 var

# Installation des dépendances PHP (tu peux retirer --no-scripts si tu veux exécuter les scripts post-install Symfony)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Exposition du port utilisé par PHP-FPM
EXPOSE 9000

# Commande de démarrage
CMD ["php-fpm"]
