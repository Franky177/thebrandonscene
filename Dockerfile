# Dockerfile for Brandon Scene Project
FROM php:8.1-apache
COPY ./html/ /var/www/html/
COPY ./php/ /var/www/html/
EXPOSE 80
