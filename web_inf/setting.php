<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

// Secure this application
$validLogin = "ewUser"; // min 6 char length
$validPass = "passEW"; // min 6 char length

// Local Temporary database (create manualy by you) to import PSA database
$mysqlPsaImpUser = "c0psa";
$mysqlPsaImpPass = "psa";
$mysqlPsaImpDbName = "c0psa";
// Plesk Pannel login needed for import psa database
$pleskPanelLogin = "admin";
$pleskPanelPassword = "YourPleskPanelPassword";
$pleskServ = "myplesk.serveur.net";

// NEW Server MySql root password for backup during import Database process
$mysqlIspServRootPass = "LocalMySqlRootPassWD";
$serverId = 1; // retrieve the server_id field value in table server of dbispconfig database (added in IspConfig panel menu "System->Server IP address")
// NEW Server setting
$ispconfigServ = "myispconfig.serveur.net";
$ispconfigServPort = "443";
$ispconfigHttpType = "https"; // http or https
$webSitePathOnNewserver = "/var/www";
$mailAccountPathOnNewserver = "/var/vmail";

// Default quota setting
$siteQuota = '25600'; // 25Go
$mailQuota = '209715200'; // 200Mo (20*1024*1024)
$Database_name_prefix = 'c[CLIENTID]_';
$Database_user_prefix = 'c[CLIENTID]_';
$FTP_user_prefix = '[CLIENTNAME]_';

// Remote User For Soap Request (create using ispconfig panel)
$remoteUserLogin = "avaliduseraccount";
$remoteUserPass = "avaliduserpassword";

// DO NOT CHANGE
$soap_location = $ispconfigHttpType.'://'.$ispconfigServ.':'.$ispconfigServPort.'/remote/index.php';
$soap_uri = $ispconfigHttpType.'://'.$ispconfigServ.':'.$ispconfigServPort.'/remote/';
define('DEBUGEW',true);
?>
