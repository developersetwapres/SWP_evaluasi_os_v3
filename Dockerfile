FROM alpine:3.24
LABEL Maintainer="Pusat Pengembangan dan Layanan Sistem Informasi <setnegapps@setneg.go.id>"

WORKDIR /var/www

RUN apk add --no-cache \
    curl \
    nginx \
    nodejs \
    npm \
    supervisor \
    tzdata \
    postgresql-client

RUN apk add --no-cache \
    php85 \
    php85-fpm \
    php85-phar \
    php85-session \
    php85-openssl \
    php85-opcache

RUN apk add --no-cache \
    php85-pdo \
    php85-pdo_mysql \
    php85-mysqli \
    php85-pdo_pgsql \
    php85-pgsql

RUN apk add --no-cache \
    php85-bcmath \
    php85-ctype \
    php85-curl \
    php85-dom \
    php85-fileinfo \
    php85-gd \
    php85-iconv \
    php85-intl \
    php85-ldap \
    php85-mbstring \
    php85-simplexml \
    php85-tokenizer \
    php85-xml \
    php85-xmlreader \
    php85-xmlwriter \
    php85-zip

RUN apk add --no-cache \
    php85-pecl-imagick

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

ENV TZ Asia/Jakarta

RUN mkdir /data-setneg-point

COPY ./config/nginx.conf /etc/nginx/nginx.conf
COPY ./config/fpm-pool.conf /etc/php85/php-fpm.d/www.conf
COPY ./config/php.ini /etc/php84/conf.d/custom.ini
COPY ./config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY src/ /var/www

COPY ./config/.env /var/www

RUN ln -s /usr/bin/php85 /usr/bin/php
RUN composer install --optimize-autoloader --no-dev
RUN php artisan storage:link

RUN npm install --legacy-peer-deps
RUN npm run build

RUN addgroup -g 1945 -S pplsi && adduser -u 1945 -S pplsi -G pplsi
RUN chown -R pplsi:pplsi /var/www /run /var/lib/nginx /var/log/nginx /data-setneg-point
USER pplsi

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping