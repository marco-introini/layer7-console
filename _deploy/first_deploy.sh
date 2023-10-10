#!/bin/bash

# Must be run as root

# Cloned in /var/www/layer7-console

cp nginx.conf /etc/nginx/sites-available/apigwconsole.mintdev.me
ln -s /etc/nginx/sites-available/apigwconsole.mintdev.me /etc/nginx/sites-enabled/
service nginx reload

certbot --nginx

chown -R www-data:www-data /var/www/layer7-console/