FROM richarvey/nginx-php-fpm

MAINTAINER youbuwei <youbuwei@doeot.com>

RUN apk --no-cache add gcc \
    && apk --no-cache add make \
    && apk --no-cache add autoconf \
    && apk --no-cache add libc-dev \
    && apk --no-cache add re2c

WORKDIR /usr/src/php/ext/
RUN git clone https://github.com/phpredis/phpredis.git \
	&& docker-php-ext-configure phpredis \
	&& docker-php-ext-install phpredis \
	&& rm -rf phpredis

# Compile Phalcon
ENV PHALCON_VERSION=3.3.1
RUN set -xe && \
        curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
        tar xzf v${PHALCON_VERSION}.tar.gz && cd cphalcon-${PHALCON_VERSION}/build && ./install && \
        echo "extension=phalcon.so" > /usr/local/etc/php/conf.d/phalcon.ini && \
        cd ../.. && rm -rf v${PHALCON_VERSION}.tar.gz cphalcon-${PHALCON_VERSION}

COPY app/ /var/www/html/app
COPY public/ /var/www/html/public
COPY index.html/ /var/www/html/

RUN mkdir -m 777 -p /var/www/html/cache/volt \
    && mkdir -m 777 -p /var/www/html/cache/logs \
    && mkdir -m 777 /var/www/html/files \
    && rm -f index.php

COPY conf/nginx-site.conf /etc/nginx/sites-available/default.conf

ENV MYSQL_HOST=127.0.0.1 \
    MYSQL_PORT=3306 \
    MYSQL_USER=root \
    MYSQL_PASSWORD=youbuwei \
    MYSQL_DB=guanjia16

ENV AMAP_KEY=508c29f814f5d415b41b3f84df48c937

ENV REDIS_HOST=127.0.0.1 \
    REDIS_PORT=6379 \
    REDIS_PASSWORD=''

ENV HPROSE_URL=http://dev.hello.com \
    HPROSE_KEY=xPS5rGW4ysNZhznCJYmH9cqLY4zHCwvp

EXPOSE 80