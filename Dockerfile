# Use the image with PHP 7.2 and Apache as base image 
FROM php:7.2-apache

# Copy the content of the current directory to /var/www/html in the image
COPY . /var/www/html

# Open up port 80 in the container
EXPOSE 80