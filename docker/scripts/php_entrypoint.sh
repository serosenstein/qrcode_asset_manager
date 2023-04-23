#/bin/sh
apt-get update -y 
apt-get upgrade -y
apt-get install -y qrencode
docker-php-ext-install pdo_mysql
/usr/local/bin/php -t /usr/src/app/ -S 0.0.0.0:80
