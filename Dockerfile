# Use the official PHP image with Apache
FROM php:8.2-apache

# Copy all project files into the Apache root directory
COPY . /var/www/html/

# Enable Apache mod_rewrite (useful for clean URLs)
RUN a2enmod rewrite

# Set permissions (optional, in case of 403)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
