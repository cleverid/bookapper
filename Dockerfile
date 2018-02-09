FROM alpine:3.7

WORKDIR /var/www
EXPOSE 80
ENTRYPOINT [ "/run.sh" ]
CMD ["www"]

################################################################################

RUN mkdir -p /var/www

# Install dependencies
RUN apk add --no-cache \
    php7 php7-fpm php7-session php7-mbstring php7-xml php7-json php7-gd php7-curl \
    php7-zlib php7-bz2 php7-zip \
    php7-phar php7-openssl php7-opcache \
    php7-pdo php7-pdo_mysql php7-pdo_sqlite sqlite sqlite-dev \
    nginx supervisor curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# composer install vendor
#add cash to composer
ADD composer.json /tmp/composer.json
RUN cd /tmp && composer install
RUN mkdir -p /var/www && cp -a /tmp/vendor /var/www
#close cash composer
ADD . /var/www

# Copy configuration
COPY ./docker/php/etc /etc/

# Copy main script
COPY ./docker/run.sh /run.sh
RUN chmod u+rwx /run.sh

# Copy project
ADD . /var/www

# Set permision
RUN set -x \
    && chown -R root:nobody /var/www \
    && find /var/www -type d -exec chmod 750 {} \; \
    && find /var/www -type f -exec chmod 640 {} \;