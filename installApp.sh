#!/bin/sh
rsync -av  --del --exclude "remoting_client" --exclude "ISPCF3_DIST"  --exclude "installApp.sh" --exclude "recupIspconfigServ.sh" /Applications/MAMP/htdocs/Migration-Plesk9-IspConfig3 root@$1:/var/www/apps/
#ssh root@$1 chown -Rf ispapps:ispapps /var/www/apps/Migration-Plesk9-IspConfig3
ssh root@$1 chown -Rf www-data:www-data /var/www/apps/Migration-Plesk9-IspConfig3