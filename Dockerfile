FROM php:fpm-alpine

#* default workdir.
WORKDIR /var/www/html

# install SSTMP for default mailing service.
RUN apk update
RUN apk add ssmtp
# install pdo.
RUN docker-php-ext-install pdo_mysql

RUN chmod 640 /etc/ssmtp/ssmtp.conf
RUN chown root:mail /etc/ssmtp/ssmtp.conf

#* Run the composer installation. 
#* if needed update plugin / vendor used
RUN php -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" && \
php composer-setup.php --install-dir=/usr/bin --filename=composer && \
php -r "unlink('composer-setup.php');"

#* copy all of the file in folder to working directory, in this case /var/www/html
COPY . .

RUN composer update
