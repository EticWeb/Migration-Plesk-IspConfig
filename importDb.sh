#!/bin/sh
# params :
#	1	$pleskPanelLogin
#	2	$pleskPanelPassword
#	3	$pleskServ
#	4	$mysqlDbImpUser
#	5	$mysqlDbImpPass
#	6	$mysqlDbImpDbName
#	7	$mysqlIspServRootPass
#	8	$oldDbName
# backup ispconfig database
#echo "Launch shell script ..."
mysqldump -uroot -p$7 --opt dbispconfig -h localhost > /tmp/migra/dbispconfig_$(whoami)_$(date +%d%m%y%h%m).sql
cmdremote="mysqldump -u"$1" -p"$2" --opt "$8" -h localhost > /tmp/migra/"$6".sql"
#echo "Transfert -> "${cmdremote}
ssh root@$3 ${cmdremote}
#echo "Transfert -> scp root@"$3":/tmp/migra/"$6".sql /tmp/migra/"$6".sql"
scp root@$3:/tmp/migra/$6.sql /tmp/migra/$6.sql
#echo "Import psa DB -> mysql -u"$4" -p"$5" -h localhost -D "$6" < /tmp/migra/"$6".sql"
mysql -u$4 -p$5 -h localhost -D $6 < /tmp/migra/$6.sql