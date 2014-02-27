<?php
$sessiontimeout = "<blink><em style='color:red'>Your session expired, please re-connect.</em></blink>";

$errShelExec = "Eror PHP function 'shell_exec' doesn't work!";
$errShelExecSSH = "Error at the time of the attempt at opening of a SSH connection without request of password (generate the public key for www-data or ispapps user and install there on the old server)!";
$errIdent = "Impossible identification.";
$errIdentMsg = "Login or Password incorrect!";
$errCnxDb = "Impossible to connect to the database";
$errSltDb = "Impossible to selec the database";
$errContDb = "Your database seems incomplete";
$errDomIdParam = "Domain id incorrect!";

$javaScriptMsg1 = "Your Login or Password seems incorrect!";
$javaScriptMsg2 = "Client Name seems incorrect!";

$appName = "Migration-Plesk9-IspConfig3";
$infoApp = "Application to migrate from plesk server (version 9) to ispconfig (version 3).";
$titreActivitee = "Beta 1";
$supTitre = "en cours de dev";
$copyright = "Application made by EticWeb, EticWeb decline any responsibility related to the loss of data or abnormal operation for your services following the use of this application";

$accord1 = "Explication & Configuration - Initialisation";
$accord1txt = "This application (to be used at your own risks) goal is to automate a certain number of tasks to realize during a migration of server.<br />
		She addresses herself all to the users of the control Plesk panel (v 9) wishing to migrate towards the open solution Ispconfig source (v3).<br />
		<span style='color:red'>Prerequisite : To have to install <strong>IspConfig3</strong> on your new server and to add the public key of the user <b>ispapps or www-data</b> on your old server to be able to make a SSH without request for password (<a target='new' href='http://eticweb.info/2009/04/28/faire-du-rsync-ou-ssh-sans-demande-de-mot-de-passe/'>see this article</a>).</span><br/>";
$accord1txt0 = "To use this application you will have : <ol style='margin-left:40px'>";
$accord1txt1 = "<li>Create folder <strong>/tmp/migra</strong> on your both serveurs <blink><em style='color:red'>\"mkdir /tmp/migra\"</em></blink></li>";
$accord1txt2 = "<li><blink><em style='color:red'>Connect</em></blink> by filling the form below.</li>";
$accord1txt3 = "<li>Edit the file <strong>web_inf/setting.php</strong> <blink><em style='color:red'>\"vi /folderofMigration-Ples9-IspConfig3/web_inf/setting.php\"</em></blink></li> to make safe the access to this application</li>";
$accord1txt4 = "<li>Create DataBase using IspConfig panel we will import inside the Plesk 'psa' database <strong>CAUTION</strong> you have to specify in file web_inf/setting.php the connection information to connect to that new database (ispconfig value you specify) and don't forget to specify the admin connection data you use when connecting on the plesk pannel.</li>";
$accord1txt5 = "<li>Imort Plesk Pannel database <strong>PSA</strong> by clicking <a href='index.php?action=psaimport' title='import psa db'>>>this link<<</a>.</li>";
$accord1txt6 = "<li>Choose domain to migrate, a dialbox will inform you on the progression, and the <strong>Web site, Database(s), Mail(s) account(s)</strong> will be added automatically using SOAP <strong>IspConfig</strong> API</li>
		</ol>";
$accord2 = "Identification";
$accord2txt1 = "Login";
$accord2txt2 = "Password";
$accord3 = "Debug - Informations";
$accord3txt = "Copy the line to call the script <strong>\"./importPsa.sh $pleskPanelLogin $pleskPanelPassword $pleskServ $mysqlPsaImpUser $mysqlPsaImpPass $mysqlPsaImpDbName\"</strong> into a terminal on your new server, you have to move inside the folder <strong>/var/www/apps</strong>";
$accord4 = "Choose Domain/Site";

$chkBoxImportClient = "Record Customer";
$chkBoxImportSite = "Copy old site contents";
$chkBoxImportFtp = "Add FTP account";
$chkBoxImportFtpWebUsers = "Add FTP accounts of Web users (notion Plesk)";
$chkBoxImportDns = "Import DNS records";
$chkBoxImportDb = "Create and import Database";
$chkBoxImportMail = "Create mails accounts, alias...";
$chkBoxImportfetchmail = "Set up the \"fetchmail\" accounts";
$txtClientForImport = "Enter here the name of the customer to which will be attached the site/domain (this customer must exist on your new server)";

$btImportStart = "Start Import";
$btImportPSAok = "Next step";
$bIdentification = "Connection";

$shadowImportTit = "Import site, customer, database, mail accounts... of domain";

$shadowResultmail = "Your e-mail is sent.";

$soapMsgCnx = "Logged successfull. Session ID:";
$soapMsgCnxClose = "Connection closed.";

$importStepClient = "Looking for customer information.";
$importStepDomaine1 = "Looking for already registered WebSite.";
$importStepDomaine2 = "Create WebSite.";
$importStepSearchOk = "Match found";
$importStepFtp = "Create principal FTP account";
$importStepDatabase1 = "Retrieve already registered DataBases";
$importStepDatabase2 = "Create Database";
$importStepDatabase3 = "Import Database (be patient)...";
$importStepDatabase4 = "<blink><em style='color:red'>Click the bouton(s) below to start database(s) import.</em></blink>";
$importStepMail1 = "Looking for already registered Mail Domain";
$importStepMail2 = "Create Mail Domain";
$importStepMail3 = "Create Mail account(s)";
$importStepSite1 = "Import site content (be patient)...";
$importStepSite2 = "<blink><em style='color:red'>Click the bouton(s) below to start import of web site content from your old server.</em></blink>";
$shellWarning = "CAUTION vous devez être dans le dossier d'installation de l'application Migration-Plesk9-IsspConfig3 ou changer le chemin d'appel au script";
$importShellExecNotWorkingSite = "Si le contenu de votre site n'est pas copié sur votre nouveau serveur, copiez la ligne ci-dessus et exécuter la depuis une session SSH sur votre nouveau serveur ($shellWarning).";
$importShellExecNotWorkingDatabase = "Si le contenu de votre ou vos base(s) de données ne sont pas copiée(s) sur votre nouveau serveur, copiez la ligne ci-dessus et exécuter la depuis une session SSH sur votre nouveau serveur ($shellWarning).";

$faqStr1 = "CAUTION IspConfig only allows the following char (a-z, A-Z, 0-9 and the undescore) into the Database name and Database User account name, so we automaticaly replace the - by _ into the bot variables. <br />Length: 2 - 64 for the database name and 16 for the database user name.";
$faqAlert1 = "Really erased your request ?";
$faqAlert2 = "Thank you to full fill the mandatory fields.";
$faqAlert3 = "Please correct the following errors:";
$faqAlert4 = "must contain a number betwen '+min+' and '+max+'";
$faqAlert5 = "must contain a number";
$faqAlert6 = "must contain a valid e-mail addres";
$faqTitle = "Contact EticWeb";
$faqTitle1 = "To contact the company EticWeb.";
$faqTitle2 = "(* Fields Mandatory)";
$faqField1 = "Name:";
$faqField2 = "Firstanme:";
$faqField3 = "(*) Mail:";
$faqField4 = "Message :";
$faqBouton1 = "Clear";
$faqBouton2 = "Send";
?>