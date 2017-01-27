#!/bin/bash

userid=`stat --format=%u /var/www/html/`
[ ${userid} -gt 33 ] && sed -i "s#www-data:x:33#www-data:x:$userid#" /etc/passwd

apt-get install dialog
debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
apt-get install -y mysql-server

echo "Starting MySQL"
/etc/init.d/mysql start
#mysqladmin -u root password root

echo "Starting apache2"
/etc/init.d/apache2 start

tail -f /var/log/apache2/error*
