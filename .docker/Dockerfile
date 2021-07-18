FROM php:8.0.7-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apk -v --update add \
    zip \
    mc \
    unzip \
    git \
    curl \
    nodejs \
    npm

# Clear cache
RUN rm -rf /var/lib/apt/lists/*

# Install extensions
RUN apk add mysql-client zlib-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev libxml2-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && docker-php-ext-enable mysqli \
    && docker-php-ext-configure mysqli --with-mysql-sock=/var/run/mysqld/mysqld.sock \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc && \
        composer

RUN addgroup -S www && adduser -S www -G www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["php-fpm"]
