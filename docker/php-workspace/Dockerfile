FROM php:8-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j4 gd

ENV PHP_INI_DIR /usr/local/etc/php

RUN chmod -R 0777 /var/log
RUN chmod -R 0777 /usr/local/var/log
RUN chmod -R 0777 /run/


RUN apt update && \
    apt upgrade -y && \
    apt install -y libpq-dev libtidy-dev libcurl4-openssl-dev libssl-dev libxml2-dev libldb-dev libldap2-dev libonig-dev libzip-dev && \
    ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so && ln -s /usr/lib/x86_64-linux-gnu/liblber.so /usr/lib/liblber.so

RUN docker-php-ext-install mysqli pdo_mysql mbstring exif pcntl bcmath gd 
RUN docker-php-ext-install pgsql ldap tidy soap
RUN docker-php-ext-install zip

RUN apt update && \
    apt upgrade -y && \
    apt-get install iputils-ping -y && \
	apt-get install net-tools -y && \
	apt-get install git -y && \
    apt-get install sshpass -y

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer global require phpunit/phpunit
# RUN useradd -G www-data,root -u 1000 -d /home/www-data www-data
RUN mkdir -p /home/www-data/.composer && \
    chown -R www-data:www-data /home/www-data
RUN usermod -G www-data,root www-data

RUN echo "==========================================================" && \
    echo "  user: www-data  " && \
    echo "  uid: 1000  " && \
    echo "=========================================================="