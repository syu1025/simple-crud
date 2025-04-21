FROM php:8.1-fpm

# 依存関係のインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# PHP拡張機能のインストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリを設定
WORKDIR /var/www

# 重要: composer.jsonを含むアプリケーションファイルをコピー
COPY ./backend /var/www/

# ここでcomposerを実行
RUN composer install --optimize-autoloader --no-dev

# 権限の設定
RUN chmod -R 777 storage bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=$PORT

EXPOSE $PORT