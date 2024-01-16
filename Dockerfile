# DockerHubのNodejsのコンテナを指定
FROM node:20-bullseye-slim as node-builder


# 作業ディレクトリを指定
# RUN mkdir /var/www/html
# npmコマンドにてコンパイルと圧縮を実行
# COPY . ./var/www/html
# RUN cd /var/www/html && npm ci && npm run prod
COPY . ./app
RUN cd /app && npm install && npm run build

# DockerHubのphp apacheのコンテナを指定
FROM php:8.1.5-apache

WORKDIR /var/www/html

# 必要モジュールをインストール
# 必要なパッケージのインストールとDocker PHP Extensionsのインストール
RUN apt-get update && apt-get upgrade -y && \
  apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip && \
  docker-php-ext-configure gd --with-freetype --with-jpeg && \
  docker-php-ext-install gd pdo pdo_mysql && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/*

# PHP標準の拡張モジュールをインストール、有効化
RUN docker-php-ext-install -j "$(nproc)" opcache && docker-php-ext-enable opcache

RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY . ./
COPY --from=node-builder /app/public ./public
RUN update-alternatives --set php /usr/bin/php8.1
RUN composer install
RUN chown -Rf www-data:www-data ./