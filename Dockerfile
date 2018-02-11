FROM alpine:3.7

WORKDIR /var/www
EXPOSE 80
ENTRYPOINT [ "/run.sh" ]
CMD ["www"]
ENV TZ Europe/Moscow

################################################################################

RUN mkdir -p /var/www

# Install dependencies
RUN apk add --no-cache \
    php5 php5-cli php5-fpm php5-json php5-gd php5-curl \
    php5-zlib php5-bz2 php5-zip \
    php5-phar php5-openssl \
    php5-xml php5-dom \
    php5-pdo php5-pdo_mysql php5-pdo_sqlite sqlite sqlite-dev \
    nginx supervisor curl tzdata \
    && ln -s /usr/bin/php5 /usr/bin/php
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
    && find /var/www -type f -exec chmod 640 {} \; \
    && chmod 777 -R /var/www/protected/runtime \
    && chmod 777 -R /var/www/assets