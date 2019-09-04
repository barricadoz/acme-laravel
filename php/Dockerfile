FROM php:7.2.7-fpm-alpine3.7
RUN apk update; \
    apk upgrade;
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql

# RUN apk add nodejs nodejs-npm

RUN cd /usr/local/bin \
    && curl --silent --show-error https://getcomposer.org/installer | php \
    && mv composer.phar composer

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN echo 'extension=pdo_mysql.so' >> /usr/local/etc/php/php.ini

RUN echo 'zend_extension="/usr/local/lib/php/extensions/no-debug-non-zts-20170718/xdebug.so"' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_handler=dbgp' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini
# Set remote_host to IP address of host machine.
# RUN echo 'xdebug.remote_host=192.168.4.131' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_host=192.168.2.88' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.max_nesting_level=300' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_autostart=1' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_connect_back=0' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.idekey="docker"' >> /usr/local/etc/php/php.ini

RUN apk add nodejs nodejs-npm
