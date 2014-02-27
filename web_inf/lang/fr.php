<?php
$sessiontimeout = "<blink><em style='color:red'>Votre session a éxpirée merci de vous ré-identifiez.</em></blink>";

$errShelExec = "Erreur la function PHP : 'shell_exec' ne fonctionne pas!";
$errShelExecSSH = "Erreur lors de la tentative d'ouverture d'une connexion SSH sans demande de mot de passe (générer la clef public pour le user www-data ou ispapps et installer là sur l'ancien serveur)!";
$errIdent = "Identification impossible.";
$errIdentMsg = "Login ou Mot de passe incorrect!";
$errCnxDb = "Impossible de se connecter à  la base de données";
$errSltDb = "Impossible de sélectionner la base ";
$errContDb = "Votre base de donnée semble incomplète";
$errDomIdParam = "Domaine id incorrect!";

$javaScriptMsg1 = "Votre identifiant ou votre mot de passe est surement incorrect!";
$javaScriptMsg2 = "Nom de client surement incorrect!";

$appName = "Migration-Plesk9-IspConfig3";
$infoApp = "Application pour migrer d'un serveur plesk (version 9) vers ispconfig (version 3).";
$titreActivitee = "Beta 1";
$supTitre = "en cours de dev";
$copyright = "Application réalisée pa EticWeb, EticWeb décline toute responsabilité liées à la perte de données ou dysfonctionnement de vos services suite à l'utilisation de cette application";

$accord1 = "Explication & Configuration - Initialisation";
$accord1txt = "Cette application (à utiliser à vos propres risques) à pour but d'automatiser un certain nombre de tâches réaliser au cours d'une migration de serveur.<br />
		Elle s'adresse à tous les utilisateurs du control pannel Plesk (v 9) désirant migrer vers la solution open source Ispconfig (v3).<br />
		<span style='color:red'>Prérequis : Avoir installer <strong>IspConfig3</strong> sur votre nouveau serveur et ajouter la clef publique de l'utilisateur <b>ispapps ou www-data</b> sur votre ancien serveur pour pouvoir faire un ssh sans demande de mot de passe (<a target='new' href='http://eticweb.info/2009/04/28/faire-du-rsync-ou-ssh-sans-demande-de-mot-de-passe/'>voir cet article</a>).</span><br/>";
$accord1txt0 = "Pour utiliser cette application vous allez devoir : <ol style='margin-left:40px'>";
$accord1txt1 = "<li>Créer un dossier <strong>/tmp/migra</strong> sur vos 2 serveurs <blink><em style='color:red'>\"mkdir /tmp/migra\"</em></blink></li> ATTENTION il faut que l'utilisateur www-data ou ispapps en soit le propriétaire sur votre nouveau serveur";
$accord1txt2 = "<li>Vous <blink><em style='color:red'>Identifiez</em></blink> ci dessous.</li>";
$accord1txt3 = "<li>Modifier le fichier <strong>web_inf/setting.php</strong> <blink><em style='color:red'>\"vi /folderofMigration-Ples9-IspConfig3/web_inf/setting.php\"</em></blink></li> pour sécuriser l'accès à cette application</li>";
$accord1txt4 = "<li>Créer une Base de donnée avec IspConfig afin d'importer la base de données utilisée par Plesk Pannel (nommé psa) <strong>ATTENTION</strong> indiquez dans le fichier web_inf/setting.php les infos pour se connecter à cette base de donnée d'import ainsi que renseigner dans ce fichier vos login et mot de passe d'accès au plesk pannel.</li>";
$accord1txt5 = "<li>Imorter la Base de donnée Plesk Pannel nommé <strong>PSA</strong> en cliquant sur ce <a href=\"javascript:TB_show('<b>Importer la base de donnée PSA</b>', 'webser.php?action=psaimport&keepThis=true&TB_iframe=true&height=400&width=550')\">>>lien<<</a></li>";
$accord1txt6 = "<li>Choisir un domaine à migrer, une dialbox vous informera de l'avancement, et le(s) <strong>Sites Web, Base de donnée(s), Compte(s) Mail(s)</strong> seront ajouter automatiquement via SOAP <strong>IspConfig</strong> API</li>
		</ol>";
$accord2 = "Identification";
$accord2txt1 = "Login";
$accord2txt2 = "Mot de passe";
$accord3 = "Debug - Informations";
$accord3txt = "Copier la ligne d'appel au script <strong>\"./importPsa.sh $pleskPanelLogin $pleskPanelPassword $pleskServ $mysqlPsaImpUser $mysqlPsaImpPass $mysqlPsaImpDbName\"</strong> dans un terminal ouvert sur votre nouveau serveur en étant dans le dossier <strong>/var/www/apps</strong>";
$accord4 = "Choisir un Domaine/Site";

$chkBoxImportClient = "Enregistrer Client";
$chkBoxImportSite = "Recopier contenu ancien site";
$chkBoxImportFtp = "Ajouter compte FTP";
$chkBoxImportFtpWebUsers = "Ajouter compte FTP des utilisateurs Web (notion Plesk)";
$chkBoxImportDns = "Importer Enregistrements DNS";
$chkBoxImportDb = "Créer et importer Bases de données";
$chkBoxImportMail = "Créer les comptes mails, alias";
$chkBoxImportfetchmail = "Mettre en place les comptes \"fetchmail\"";
$txtClientForImport = "Saisir ici le nom du client auquel sera attaché le site/domaine (ce client doit exister sur votre nouveau serveur)";

$btImportStart = "Lancer Import";
$btImportPSAok = "Etapes suivantes";
$bIdentification = "Connexion";

$shadowImportTit = "Importer le site, client, database, compte mail... du domaine";

$shadowResultmail = "Votre mail est envoyé.";

$soapMsgCnx = "Connexion ok. ID Session :";
$soapMsgCnxClose = "Connexion fermée.";

$importStepClient = "Recherche information client.";
$importStepDomaine1 = "Recherche si SiteWeb existant.";
$importStepDomaine2 = "Création du SiteWeb.";
$importStepSearchOk = "Correspondance trouvée";
$importStepFtp = "Création Compte FTP principal";
$importStepDatabase1 = "Récupération Base(s) de donnée(s) éxistante";
$importStepDatabase2 = "Création Base(s) de donnée(s)";
$importStepDatabase3 = "Import de la Base de donnée (soyez patient)...";
$importStepDatabase4 = "<blink><em style='color:red'>Cliquez sur le(s) bouton(s) ci dessous pour démarrer l'import du contenu de(s) Base(s) de Données.</em></blink>";
$importStepMail1 = "Recherche si MailDomain existant";
$importStepMail2 = "Création MailDomain";
$importStepMail3 = "Création Compte(s) Mail";
$importStepSite1 = "Import du contenu du site (soyez patient)...";
$importStepSite2 = "<blink><em style='color:red'>Cliquez sur le bouton ci dessous pour démarrer l'import du contenu de votre site depuis votre ancien serveur.</em></blink>";
$shellWarning = "ATTENTION vous devez être dans le dossier d'installation de l'application Migration-Plesk9-IsspConfig3 ou changer le chemin d'appel au script";
$importShellExecNotWorkingSite = "Si le contenu de votre site n'est pas copié sur votre nouveau serveur, copiez la ligne ci-dessus et exécuter la depuis une session SSH sur votre nouveau serveur ($shellWarning).";
$importShellExecNotWorkingDatabase = "Si le contenu de votre ou vos base(s) de données ne sont pas copiée(s) sur votre nouveau serveur, copiez la ligne ci-dessus et exécuter la depuis une session SSH sur votre nouveau serveur ($shellWarning).";

$faqStr1 = "ATTENTION IspConfig n'autorise que les caractères (a-z, A-Z, 0-9 et le tiret bas '_') dans le nom de la Base de données ainsi que pour le nom Utilisateur, nous remplacerons le caractère - par un _ automatiquement dans ces deux variables. <br />Longueur: 2 - 64 car pour le nom de la DB et 16 car max pour le nom utilisateur.";
$faqAlert1 = "Voulez vous vraiment effacer votre demande ?";
$faqAlert2 = "Merci de renseigner les champs obligatoires.";
$faqAlert3 = "Veuillez corriger les erreurs suivantes:";
$faqAlert4 = "doit contenir un nombre entre '+min+' et '+max+'";
$faqAlert5 = "doit contenir un nombre";
$faqAlert6 = "doit contenir une adress e-mail";
$faqTitle = "Contactez EticWeb";
$faqTitle1 = "Pour contacter la société EticWeb.";
$faqTitle2 = "(*Renseignements Obligatoires)";
$faqField1 = "Nom:";
$faqField2 = "Prénom:";
$faqField3 = "(*) Email:";
$faqField4 = "Message :";
$faqBouton1 = "Effacer";
$faqBouton2 = "Envoyer";
?>