FROM php:8.2-apache

WORKDIR /var/www

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

#RUN pecl install redis && docker-php-ext-enable redis
#
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql pgsql

#RUN apt-get install -y libssh2-1-dev libssh2-1 \
#    && pecl install ssh2-1.3.1 \
#    && docker-php-ext-enable ssh2

#RUN docker-php-ext-install sockets && docker-php-ext-enable sockets

#RUN apt-get update && apt-get install -y \
#        libfreetype6-dev \
#        libjpeg62-turbo-dev \
#        libpng-dev \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg \
#    && docker-php-ext-install -j$(nproc) gd

#RUN docker-php-ext-configure pcntl --enable-pcntl \
#  && docker-php-ext-install pcntl

#RUN apt-get update && \
#    apt-get install -y graphviz

RUN apt-get -y update \
    && apt-get install -y libicu-dev\
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN  apt-get update \
  && apt-get install -y wget

RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN a2enmod headers
RUN a2enmod rewrite
RUN service apache2 restart

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer