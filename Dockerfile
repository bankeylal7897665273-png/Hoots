# Base image PHP with Apache Server
FROM php:8.2-apache

# API calls (Firebase/Hugging Face) ke liye cURL install karna
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && docker-php-ext-install curl

# URL Rewrite module on karna
RUN a2enmod rewrite

# Tumhari saari project files ko server ke public folder me copy karna
COPY . /var/www/html/

# Files ko sahi permissions dena taaki upload/edit sahi se kaam kare
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Port 80 open karna (Web traffic ke liye)
EXPOSE 80
