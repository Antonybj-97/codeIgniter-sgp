#!/bin/bash

composer install --no-dev --optimize-autoloader --no-interaction --no-progress
php spark cache:clear
php spark optimize
php spark session:migration --create

chmod -R 775 writable/
mkdir -p writable/cache writable/logs writable/session
