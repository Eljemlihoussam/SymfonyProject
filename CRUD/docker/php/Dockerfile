FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    # Pour dos2unix
    dos2unix \
    netcat-openbsd \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Copy PHP-FPM debug configuration
COPY docker/php/php-fpm-debug.conf /usr/local/etc/php-fpm.d/

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all application code (excluding .dockerignore paths like /vendor)
COPY . ./

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Convert line endings (if necessary, adjust path)
# RUN find /var/www/html -type f -name "*.php" -exec dos2unix {} \;
# RUN find /var/www/html -type f -name "*.sh" -exec dos2unix {} \;

# Permissions
RUN chmod -R 755 /var/www/html
RUN chmod -R 777 /var/www/html/var

# Create default .env file (if it doesn't exist locally or isn't copied)
# This might be redundant if using docker-compose environment variables
# RUN if [ ! -f .env ]; then echo 'APP_ENV=dev' > .env && \
#     echo 'APP_SECRET=your_secret_here' >> .env && \
#     echo 'DATABASE_URL="mysql://symfony:password@database:3306/symfony?serverVersion=8.0"' >> .env && \
#     echo 'MESSENGER_TRANSPORT_DSN=doctrine://default' >> .env && \
#     echo 'MAILER_DSN=null://null' >> .env; fi

# Expose port 9000 (PHP-FPM)
EXPOSE 9000

CMD ["php-fpm"] 