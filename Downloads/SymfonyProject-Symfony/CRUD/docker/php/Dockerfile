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
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration du répertoire de travail
WORKDIR /var/www/html

# Configuration PHP
RUN echo "memory_limit=256M" > /usr/local/etc/php/conf.d/custom.ini && \
    echo "upload_max_filesize=20M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "post_max_size=20M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "max_execution_time=30" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "date.timezone=Europe/Paris" >> /usr/local/etc/php/conf.d/custom.ini

# Création de l'utilisateur non-root
RUN useradd -G www-data,root -u 1000 -d /home/dev dev
RUN mkdir -p /home/dev/.composer && \
    chown -R dev:dev /home/dev

# Configuration des permissions
RUN chown -R dev:www-data /var/www/html && \
    chmod -R 775 /var/www/html

# Définition de l'utilisateur par défaut
USER dev 