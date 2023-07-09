#!/bin/bash
chown -R www-data:www-data storage

echo "Execute main:"
docker-php-entrypoint $@
echo "Main Done"

php-fpm -F

exec $@