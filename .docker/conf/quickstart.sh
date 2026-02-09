#!/bin/bash

set -e

if [ ! -d "/var/www/html/vendor" ]; then
    /bin/echo "<pre>" > /var/www/html/public/install.html

    composer self-update 2>> /var/www/html/public/install.html
    composer install -d /var/www/html  2>> /var/www/html/public/install.html 

    /bin/rm -f /var/www/html/public/install.html

fi

if [ ! -f storage/keys/jwt-private.pem ] || [ ! -f storage/keys/jwt-public.pem ]; then
  mkdir -p storage/keys
  openssl genrsa -out storage/keys/jwt-private.pem 4096
  openssl rsa -in storage/keys/jwt-private.pem -pubout -out storage/keys/jwt-public.pem

fi

sleep 5

php -f /var/www/html/artisan optimize

supervisord -c /etc/supervisor/supervisord.conf

