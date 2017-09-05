#!/bin/bash

echo "########## Honest Science ##########"

chmod 755 .
chmod 644 public/
chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

composer update $0

php artisan cache:clear
php artisan view:clear
php artisan config:cache

php artisan inspire

echo "############### Done ###############"