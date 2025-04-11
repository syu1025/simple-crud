FROM php:8.2-fpm

# システムの依存関係をインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# PHP 拡張をインストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer をインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# アプリケーションディレクトリを作成
WORKDIR /var/www

# バックエンドフォルダの内容をコピー
COPY ./backend /var/www

# 依存関係をインストール
RUN composer install --optimize-autoloader --no-dev

# 権限を設定
RUN chown -R www-data:www-data /var/www

# キャッシュを生成
RUN php artisan config:cache && \
    php artisan route:cache

# アプリケーションを実行
CMD sh -c "php artisan serve --host=0.0.0.0 --port=\${PORT}"

EXPOSE $PORT

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer
