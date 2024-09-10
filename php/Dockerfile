FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm\
    libx11-xcb1 \
    libxcomposite1 \
    libxcursor1 \
    libxdamage1 \
    libxext6 \
    libxfixes3 \
    libxi6 \
    libxrandr2 \
    libxtst6 \
    libnss3 \
    libasound2 \
    libatk1.0-0 \
    libatk-bridge2.0-0 \
    libcups2 \
    libdrm2 \
    libdbus-1-3 \
    libgdk-pixbuf2.0-0 \
    libgbm1 \
    libglib2.0-0 \
    libnspr4 \
    libpango1.0-0 \
    libx11-6 \
    libx11-dev \
    libxkbcommon0 \
    ca-certificates \
    fonts-liberation \
    libappindicator1 \
    libatk-bridge2.0-0 \
    libgtk-3-0 \
    libxss1 \
    lsb-release \
    xdg-utils \
    wget \
    libgbm-dev \
    curl 
    

WORKDIR /var/www/html


RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install redis && docker-php-ext-enable redis

COPY ./laravel/composer.json laravel/composer.lock ./
COPY ./laravel/package.json laravel/package-lock.json ./
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache

RUN chmod -R 777 /var/www/html/storage/
RUN chmod -R 777 /var/www/html/bootstrap/cache

RUN composer install --no-interaction --no-plugins --no-scripts

RUN npm install

RUN npm install -g npm@latest\
    && npm i puppeteer

RUN apt-get update && apt-get install -y supervisor
COPY ./php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ça fixe le bug de la commande php artisan reverb:start
RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl


ENTRYPOINT [ "/var/www/html/entry.sh" ]


