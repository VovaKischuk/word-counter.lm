FROM alpine:3.14 as base

RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv \
    && apk add -U --no-cache \
    # Packages
    php8 \
    php8-dev \
    php8-common \
    php8-gd \
    php8-xmlreader \
    php8-bcmath \
    php8-ctype \
    php8-curl \
    php8-exif \
    php8-iconv \
    php8-intl \
    php8-json \
    php8-mbstring \
    php8-opcache \
    php8-openssl \
    php8-pcntl \
    php8-pdo \
    php8-mysqlnd \
    php8-pdo_mysql \
    php8-pdo_pgsql \
    php8-phar \
    php8-posix \
    php8-session \
    php8-xml \
    php8-xsl \
    php8-zip \
    php8-zlib \
    php8-dom \
    php8-redis \
    php8-fpm \
    php8-sodium \
    php8-tokenizer \
    php8-xmlwriter \
    php8-simplexml \
    php8-fileinfo \
    && apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted php8-pecl-apcu \
    && ln -s /usr/bin/php8 /usr/bin/php

ADD etc/artifacts/rootfs /

CMD ["/usr/sbin/php-fpm8", "-R", "--nodaemonize"]

EXPOSE 9000

WORKDIR /app

FROM base as dev

ARG COMPOSER_VERSION=2.2.7

RUN apk add -U --no-cache \
        php8-pear \
        openssh \
        supervisor \
        autoconf \
        git \
        curl \
        wget \
        make \
        zip \
        php8-xdebug \
    && rm -rf /var/cache/apk/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION}

ADD etc/artifacts/devfs /

EXPOSE 9000
