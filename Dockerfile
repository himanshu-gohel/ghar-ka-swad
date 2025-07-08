# Use the official PHP image with Apache
FROM php:8.2-apache

# Copy all files to Apache’s default HTML directory
COPY . /var/www/html/

# Expose port 80 (for web server)
EXPOSE 80
