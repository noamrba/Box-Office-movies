# Use the official PHP image with Apache
FROM php:7.4-apache

# Copy your website files to the container's web directory
COPY app/ /var/www/html/

# Enable Apache mod_rewrite if needed
RUN a2enmod rewrite

# Install any PHP extensions your website needs (optional)
# RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expose port 80 for the web server
EXPOSE 80
