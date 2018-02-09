FROM php:5.6-apache

RUN apt-get update
RUN apt-get install -y git zlib1g-dev \
 && apt-get install -y sqlite3 libsqlite3-dev \
 && docker-php-ext-install zip \
 && docker-php-ext-install pdo pdo_mysql pdo_sqlite \
 && a2enmod rewrite \
 && curl -sS https://getcomposer.org/installer \
   | php -- --install-dir=/usr/local/bin --filename=composer
   
RUN sed -i 's!/var/www/html!/var/www!g' /etc/apache2/apache2.conf \
 && sed -i 's!/var/www/html!/var/www!g'  /etc/apache2/sites-available/000-default.conf \
 && rm -R /var/www/html

COPY ./protected/config/php.ini /usr/local/etc/php/php.ini
 
WORKDIR /var/www