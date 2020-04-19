FROM php:7.3-apache 

# CHANGING ROOT OF SERVER SO IT'S IN PUBLIC
WORKDIR /etc/apache2/sites-available/
# RUN mkdir /var/www/html/camagru/public
RUN sed -Ei "s/(\/var\/www\/html)/\/var\/www\/html\/camagru\/public\//" 000-default.conf

#setting up apache to allow .htaccess
RUN echo '<Directory /var/www/html/camagru/public>\n\tOptions Indexes FollowSymLinks\n\tAllowOverride All\n\tRequire all granted\n</Directory>' >> 000-default.conf

#DOWNLOADING EVERY PACKAGES WE NEED AND THEN RECOMPILING PHP WITH THE GOOD EXTENSIONS
RUN apt-get update && apt-get upgrade -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
		openssl

RUN mkdir /etc/apache2/sites-available/certs
WORKDIR /etc/apache2/sites-available/certs
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout camagru_ssl.key -out camagru_ssl.crt -subj "/C=FR/ST=Paris/L=Paris/CN=camagru.com"
COPY ssl_config.conf /etc/apache2/sites-available/ssl_config.conf
WORKDIR /etc/apache2/sites-available/
RUN cat ssl_config.conf | cat - 000-default.conf > temp && mv temp 000-default.conf
run rm -rf ssl_config.conf

# WORKDIR /etc/apache2/sites-available/
RUN	apt-get install -y --no-install-recommends git zip
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include 
RUN docker-php-ext-install mysqli pdo pdo_mysql gd mbstring exif

RUN a2enmod rewrite
RUN a2enmod ssl
# RUN service apache2 restart

#INSTALLING COMPOSER FROM getcomposer.org
WORKDIR /var/www/html/camagru/
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN cp composer.phar /usr/local/bin/composer
# RUN composer dump-autoload
# RUN composer install

# RUN apt-get install php-xdebug -y
# WORKDIR /usr/local/etc/php
# RUN cp php.ini-development php.ini
# RUN sed -Ei "s/(;extension_dir = \".\/\")/extension_dir = \".\/\"/" php.ini
# RUN sed -Ei "s/(;extension=curl)/extension=curl/" php.ini
# RUN sed -Ei "s/(;extension=mysqli)/extension=mysqli/" php.ini
# RUN sed -Ei "s/(;extension=pdo_mysql)/extension=pdo_mysql/" php.ini
# RUN sed -Ei "s/(;extension=pdo_sqlite)/extension=pdo_sqlite/" php.ini