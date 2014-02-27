#!/bin/sh
# params :
#	1	$pleskPanelLogin
#	2	$pleskPanelPassword
#	3	$pleskServ
#	4	$mysqlPsaImpUser
#	5	$mysqlPsaImpPass
#	6	$mysqlPsaImpDbName
#echo "Launch shell script ..."
cmdremote="mysqldump -u"$1" -p"$2" --opt psa -h localhost > /tmp/migra/psa.sql"
#echo ${cmdremote}
ssh root@$3 ${cmdremote}
#echo "Transfert ->  scp root@"$3":/tmp/migra/psa.sql /tmp/migra/psa.sql"
scp root@$3:/tmp/migra/psa.sql /tmp/migra/psa.sql
#echo "Import psa DB -> mysql -u"$4" -p"$5" -h localhost -D "$6" < /tmp/migra/psa.sql"
mysql -u$4 -p$5 -h localhost -D $6 < /tmp/migra/psa.sql