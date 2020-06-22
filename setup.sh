#!/bin/bash

echo "########## Honest Science ##########"

composer update $0

php artisan cache:clear
php artisan view:clear
php artisan config:cache

echo ""

php artisan inspire

echo ""

echo "############### Done ###############"