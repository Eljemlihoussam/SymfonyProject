FROM nginx:alpine

# Copy nginx configuration
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Create directory for public files
RUN mkdir -p /var/www/public

# Set permissions
RUN chown -R nginx:nginx /var/www

# Copier les fichiers publics
COPY public/ /var/www/public/

# Définir les permissions correctes
RUN chmod -R 755 /var/www 