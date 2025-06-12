# Use the official PHP image with PHP 8.2
FROM php:8.2-apache

# Set the working directory
WORKDIR /var/www/html

# Copy the application files
COPY . /var/www/html/

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Run composer install to install dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose the port Render will use
EXPOSE 80

# Start the PHP server
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]