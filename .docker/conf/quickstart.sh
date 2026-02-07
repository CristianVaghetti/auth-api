#!/bin/bash

set -e

if [ ! -d "/var/www/html/vendor" ]; then
    /bin/echo "<pre>" > /var/www/html/public/install.html

    php /var/www/html/composer.phar self-update 2>> /var/www/html/public/install.html
    php /var/www/html/composer.phar install -d /var/www/html  2>> /var/www/html/public/install.html 

    /bin/rm -f /var/www/html/public/install.html

fi

sleep 5

php -f /var/www/html/artisan optimize

supervisord -c /etc/supervisor/supervisord.conf

