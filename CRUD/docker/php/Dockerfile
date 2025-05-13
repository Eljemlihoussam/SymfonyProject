FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    dos2unix

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration du répertoire de travail
WORKDIR /var/www

# Copie des fichiers du projet
COPY . /var/www/

# Conversion des fins de ligne
RUN find /var/www -type f -name "*.php" -exec dos2unix {} \;
RUN find /var/www -type f -name "*.sh" -exec dos2unix {} \;

# Permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www

# Création du fichier .env par défaut
RUN echo 'APP_ENV=dev' > /var/www/.env && \
    echo 'APP_SECRET=your_secret_here' >> /var/www/.env && \
    echo 'DATABASE_URL="mysql://symfony:password@database:3306/symfony?serverVersion=8.0"' >> /var/www/.env && \
    echo 'MESSENGER_TRANSPORT_DSN=doctrine://default' >> /var/www/.env && \
    echo 'MAILER_DSN=null://null' >> /var/www/.env

# Installation des dépendances
RUN composer install --no-interaction

# Création du répertoire public s'il n'existe pas
RUN mkdir -p /var/www/public

# Création du répertoire des images
RUN mkdir -p /var/www/public/images

# Copie des fichiers de base Symfony
COPY public/index.php /var/www/public/
COPY public/.htaccess /var/www/public/

# Configuration PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Exposer le port 9000
EXPOSE 9000

CMD ["php-fpm"] 