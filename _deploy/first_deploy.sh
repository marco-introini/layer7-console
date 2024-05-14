#!/bin/bash

# Must be run as root

# Cloned in /var/www/layer7-console

cp nginx.conf /etc/nginx/sites-available/apigwconsole.mintdev.me
ln -s /etc/nginx/sites-available/apigwconsole.mintdev.me /etc/nginx/sites-enabled/
service nginx reload

certbot --nginx

chown -R marco:www-data /var/www/layer7-console/
chmod 777 -R /var/www/layer7-console/storage

echo "* * * * * marco cd /var/www/layer7-console && php artisan schedule:run >> /dev/null 2>&1" >> /etc/crontab

cd /var/www/layer7-console || exit
php artisan storage:link
