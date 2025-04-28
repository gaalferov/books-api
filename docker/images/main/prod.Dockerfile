FROM --platform=linux/x86_64 php:8.3-fpm-alpine

RUN apk --no-cache add shadow

RUN apk update && \
    apk upgrade && \
    apk add --no-cache \
        linux-headers \
        $PHPIZE_DEPS \
        nginx \
        icu-dev \
        git \
        openssh-client \
        openssl-dev \
        zlib-dev \
        libzip-dev \
        curl \
        gnupg \
        yarn \
        bash \
        libxml2-dev \
        libpq-dev \
        htop

# apcu
RUN pecl channel-update pecl.php.net \
    && pecl install apcu \
    && docker-php-ext-enable apcu

# other libraries
RUN docker-php-ext-configure intl \
    && docker-php-ext-install \
        intl \
        zip \
        bcmath \
        pdo \
        pdo_mysql \
        soap \
        opcache

# Install Socket extension
RUN docker-php-ext-configure sockets \
    && docker-php-ext-install sockets \
    && docker-php-ext-enable sockets

# Install Postgre PDO
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pdo_pgsql pgsql && \
    docker-php-ext-enable pdo_pgsql

# AMQP (uncomment if needed)
#RUN pecl install amqp \
#    && docker-php-ext-enable amqp

#cachetool
COPY ./docker/conf/php/cachetool.yml /etc/cachetool.yml
RUN curl -sLO https://github.com/gordalina/cachetool/releases/latest/download/cachetool.phar \
    && chmod +x cachetool.phar \
    && mv cachetool.phar /usr/local/bin/cachetool

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /var/www/html

# implement changes required to run NGINX as an unprivileged user
RUN  mkdir /var/cache/nginx && \
     mkdir -p /etc/nginx && \
     mkdir -p /etc/nginx/ssl/ && \
     mkdir -p /etc/nginx/sites-available/ && \
     mkdir -p /etc/nginx/sites-enabled/

COPY ./api /var/www/html
COPY ./docker/conf/nginx/nginx-site.conf /etc/nginx/conf.d/default.conf
COPY ./docker/conf/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/conf/php/php-fpm-www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /var/www/html/var && \
    mkdir -p /var/www/html/vendor

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

WORKDIR /var/www/html

RUN git config --global --add safe.directory '*'

# cache directory
RUN usermod -u 1000 -g www-data www-data  \
    && chown -R www-data:www-data /var/cache/nginx \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/lib/nginx \
    && chmod -R 755 /etc/nginx \
    && chmod -R 755 /usr/local/etc/php-fpm.d \
    && chmod -R 777 /var/lib/nginx/logs \
    && chmod -R 777 /var/cache/nginx \
    && chmod -R 777 /var/www/html/var

# composer
RUN composer clearcache \
    && composer install --no-interaction --no-scripts \
    && composer dumpautoload -o

# configure php opcache after all php extensions are installed
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
COPY ./docker/conf/php/opcache.blacklist.txt /usr/local/etc/php/conf.d/opcache.blacklist.txt
COPY ./docker/conf/php/prod.opcache.ini $PHP_INI_DIR/conf.d/opcache.ini
COPY ./docker/conf/php/apcu.ini /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini


STOPSIGNAL SIGTERM
USER www-data
EXPOSE 8080

CMD /bin/bash -c "nginx && php-fpm"
