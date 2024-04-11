FROM php:8.1-fpm

COPY ./php/php.ini /usr/local/etc/php/php.ini

RUN apt update && apt upgrade -y
RUN apt-get install sudo
RUN sudo apt-get install -y git vim
RUN sudo apt-get install -y zip unzip

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql