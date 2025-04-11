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

# プロジェクト全体をコピー（ディレクトリ構造に合わせて調整）
COPY . /var/www

# 依存関係をインストール
RUN composer install --optimize-autoloader --no-dev

# 権限を設定
RUN chown -R www-data:www-data /var/www

# ストレージディレクトリに書き込み権限を付与
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 環境設定
ENV PORT=8000

# アプリケーションを実行
CMD php artisan serve --host=0.0.0.0 --port=${PORT}

<<<<<<< HEAD
EXPOSE $PORT

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer
=======
EXPOSE ${PORT}
>>>>>>> d28a728ffc151df14d80d87dd6f78ec7644e6de3
