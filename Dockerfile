FROM php:8.1-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN echo "upload_max_filesize = 20M\npost_max_size = 20M" > /usr/local/etc/php/conf.d/uploads.ini
