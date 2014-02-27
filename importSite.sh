#!/bin/sh
#echo "Launch shell script ..."
rsync -av  --del --exclude "stats" --exclude "plesk-stat" root@$1:/var/www/vhosts/$2/httpdocs/ /var/www/$2/web
chown -Rf $3:$4 /var/www/$2/web
chown -Rf root:root /var/www/$2/web/stats