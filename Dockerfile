FROM php:8.2-apache

# Install extensions (optional)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your project into the web server root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose the port Apache uses
EXPOSE 80
