#!/bin/sh
rsync -avd root@$1:/usr/local/ispconfig /Applications/MAMP/htdocs/Migration-Plesk9-IspConfig3/ISPCF3_DIST/
chown -Rf sgaudemer:admin /var/www/apps/Migration-Plesk9-IspConfig3/ISPCF3_DIST