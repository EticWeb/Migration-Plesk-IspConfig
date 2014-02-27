<?php
/*
* This file is part of the Migration-Plesk9-IspConfig3 web-application.
*
* (c) EticWeb - Gaudemer sébastien. 
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

require_once("web_inf/app_global.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>:: <?php echo $appName ?> ::</title>	<!-- METAs -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Expires" content="Sun, 01 Jan 1970 00:00:00 GMT" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="imagetoolbar" content="false" />
	<meta http-equiv="Content-Language" content="FR-FR"/>
	<meta name="description" content="Migration tools for migrate from Plesk (plesk 9 , plesk9) to IspConfig (ispconfig 3 , ispconfig3)"/>
	<meta name="keywords" content="migration, plesk9, ispconfig3, webhosting"/>
	<meta name="generator" content="EticWeb SARL"/>
	<meta name="author" content="EticWeb" />
	<meta name="distribution" content="Global"/>
	<meta name="revisit-after" content="14 days"/>
	<meta name="robots" content="index, nofollow"/>
	<meta name="MSSmartTagsPreventParsing" content="true" />
	<meta name="rating" content="general" />
	<meta name="copyright" content="Copyright &copy; 2011 www.etic-web.com" />
	
	<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="STYLESHEET" href="css/smoothbox/smoothbox.css" type="text/css"/>
	
	<script src="script/mootools.v1.00.full.packed.js" type="text/javascript"></script>
	<script src="script/smoothbox/smoothbox.js" type="text/javascript"></script>
	<script language="JavaScript">
	function ajaxRequestShellDb(params){
		showResponse('<?php echo $importStepDatabase3 ?>');
		new Ajax("callshell.php",{postBody:params+'&rdm='+Math.floor(Math.random()*11), onComplete: '', update:'container'}).request();
		return false;
	};
	function ajaxRequestShellSite(params){
		showResponse('<?php echo $importStepSite1 ?>');
		new Ajax("callshell.php",{postBody:params+'&rdm='+Math.floor(Math.random()*11), onComplete: '', update:'container'}).request();
		return false;
	};
	function showResponse(request){
		//var createNodes = document.createTextNode(request);
		try {
	    	document.getElementById('container').innerHTML+=request;
	    	document.getElementById('container').appendChild(document.createElement("br"));
		} catch (e) {
			alert(e);
		}
		return true;
	};
	</script>
</head>
<body>
<?php
if (isset($_SESSION['EW'])) {
	
	$domid = isset($_GET['domid'])?$_GET['domid']:"-1";
	$action = isset($_GET['action'])?$_GET['action']:"";
	$nom_client = isset($_POST['clientname'])?$_POST['clientname']:"";
	$nom_domaine = isset($_POST['domname'])?$_POST['domname']:"";
	$imp_client = false;
	$imp_site = (isset($_POST['site']) && $_POST['site'] == 1)?true:false;
	$imp_ftp = (isset($_POST['ftp']) && $_POST['ftp'] == 1)?true:false;
	$imp_ftpuserweb = (isset($_POST['ftpuserweb']) && $_POST['ftpuserweb'] == 1)?true:false;
	$imp_dns = false;
	$imp_db = (isset($_POST['db']) && $_POST['db'] == 1)?true:false;
	$imp_mail = (isset($_POST['mail']) && $_POST['mail'] == 1)?true:false;
	$imp_fetchmail = (isset($_POST['fetchmail']) && $_POST['fetchmail'] == 1)?true:false;
	
	$client_id = -1;
	
	switch ($action) {
		case "domimport":
			if ($domid!=-1 && $nom_domaine != "") {
				set_time_limit ( 600 ); // 10 minutes
				$client = new SoapClient(null, array('location' => $soap_location, 'uri' => $soap_uri, 'trace' => 1, 'exceptions' => 1));
				try {
					// Open SOAP CNX
					if($session_id = $client->login($remoteUserLogin,$remoteUserPass)) {
						echo $soapMsgCnx.$session_id."<br /><a href='javascript:history.go(-1)'><<--</a><br />";
					}
					// Recherche Client Info
					// Vérification si Client déjà enregistré
					echo $importStepClient."<br/>";; 
					try {
						$soapreturn = testSoapResult($client->client_get_by_username($session_id, $nom_client));
						if (DEBUGEW) {
							echo "SELECT * FROM sys_user WHERE username = '".$nom_client."'<br/>";
							echo " Info User : <div style='margin-left:20px'>";
							foreach ($soapreturn as $key => $value) {
								echo $key . " --> " . $value . "<br/>";
							}
							echo "</div>";
							$serverinfo = testSoapResult($client->server_get_serverid_by_ip($session_id, '213.246.53.54'));
							echo "SERVER Info : <div style='margin-left:20px'>";
							foreach ($serverinfo as $key => $value) {
								if (is_array($value)) {
									foreach ($value as $key2 => $value2) {
										echo $key2 . " --> " . $value2 . "<br/>";
									}
								} else echo $key . "->" . $value . "<br/>";
							}
							echo "</div>";
						}
						$client_id = $soapreturn['client_id'];
						$sys_userid = $soapreturn['sys_userid'];
						$sys_groupid = $soapreturn['sys_groupid'];
						$default_group = $soapreturn['default_group'];
					} catch (Exception $e) {
					 	echo "<span style='color:red'> Client search ".$e->getMessage()."</span><a href='javascript:history.go(-1)'><<--</a>";
					 	die;
					}
					if ( $client_id != -1 ) {
						// SiteWeb création
						if($imp_db || $imp_ftp || $imp_site) $web_domain_id = addSiteWeb($serverId,$client,$session_id,$client_id,$sys_userid,$sys_groupid,$nom_domaine,$default_group);
						
						// Import Base de Données
						if ($imp_db && $web_domain_id != -1) echo addDatabases($$serverId,client,$session_id,$client_id,$domid);
						
						// Création Compte FTP
						if ($imp_ftp && $web_domain_id != -1) addFtp($serverId,$client,$session_id,$client_id,$web_domain_id,$domid);
						
						// Création Compte Mail
						if ($imp_mail) addMails($serverId,$client,$session_id,$client_id,$nom_domaine,$domid,$imp_fetchmail);

						// Import Site Content
						if ($imp_site && $web_domain_id != -1) {
							echo $importStepSite2."<br/>";
							// Bouton pour appel ajax import DB
							echo '<div style="float:right;text-align:center"><div onmouseover="this.className=\'but7\'" onmouseout="this.className=\'but6\'" class="but6"><a href="#followajax" onclick="ajaxRequestShellSite(\'script=importSite&sitename='.$nom_domaine.'&grid=web'.$web_domain_id.'&clid=client'.$client_id.'\');">Import '.$nom_domaine.'</a></div></div><br/>';
							
						}
						
					} // Fin Pas de client trouvé

					// Close SOAP CNX
					if($client->logout($session_id)) {
						echo $soapMsgCnxClose.'<br />';
					}
					?>
					<div style="clear:both"><br /><a href='javascript:history.go(-1)'><<--</a><br />
					<a name="followajax"> ------ ------ ------ ajax resultat ------ ------ ------ </a>
					<div id="container" style="width="100%"></div>
					<?php
					
				} catch (SoapFault $e) {
					echo str_ireplace("&lt;br&gt;&#13;","<br/>",$client->__getLastResponse());
					die('<span style="color:red"> SOAP Error: '.str_ireplace("&lt;br&gt;&#13;","<br/>",$e->getMessage())."</span><br/><a href='javascript:history.go(-1)'><<--</a>");
				} catch (Exception $e) {
				 	echo "<span style='color:red'> Error ".str_ireplace("&lt;br&gt;&#13;","<br/>",$e->getMessage())."</span><br/><a href='javascript:history.go(-1)'><<--</a>";
				 	die;
				}
				
			} else echo $errDomIdParam." Dom : " . $nom_domaine;
			break;
		
		default:
			break;
	}
}
?>
</body>
</html>