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
$nbFtp = 0;
$nbDb = 0;
$nbMail = 0;
$errMsg = "";
$domid = -1;
$nom_domaine = isset($_GET['domname'])?$_GET['domname']:"";

if (isset($_SESSION['EW'])) {
	$action = isset($_GET['action'])?$_GET['action']:"";
	switch ($action) {
		case "domimport":
			
			$domid = isset($_GET['domid'])?$_GET['domid']:"-1";
			if ($domid!=-1) {
				try {
					connectSql($mysqlPsaImpUser,$mysqlPsaImpPass,$mysqlPsaImpDbName);
				} catch (Exception $e) {
				 	$errMsg .= "<span style='color:red'>".$e->getMessage()."</span>";
				}
				/*
				Liste des domaines avec comptes FTP associés (si hébergement web activé)
				SELECT domains.name,sys_users.login as ftp_login,accounts.password as ftp_pwd,hosting.* FROM domains left join hosting on domains.id = hosting.dom_id left join sys_users on hosting.sys_user_id=sys_users.id left join accounts  on sys_users.account_id=accounts.id order by name
				
				Liste des Databases liées aux domaines
				SELECT db_users.login,accounts.password,data_bases.name,domains.name FROM db_users left join accounts on db_users.account_id=accounts.id left join data_bases on db_users.db_id=data_bases.id left join domains on data_bases.dom_id=domains.id
				
				Exportation des comptes mails (alias,groupe ...)
				SELECT domains.name,mail_name,password,postbox,mbox_quota,autoresponder,redirect,redir_addr,mail_group,mail_redir.address FROM mail left join accounts on account_id=accounts.id left join domains on dom_id=domains.id left join mail_redir on mail.id=mn_id
				 */
				// recherche compte FTP
				$sql = "SELECT domains.name,sys_users.login as ftp_login,accounts.password as ftp_pwd,hosting.* FROM domains left join hosting on domains.id = hosting.dom_id right join sys_users on hosting.sys_user_id=sys_users.id left join accounts  on sys_users.account_id=accounts.id WHERE domains.id=".$domid;
				$result = mysql_query($sql);
				if (!$result) throw new Exception($errContDb." <span style='color:red'>" . mysql_error()."</span>");
				$nbFtp = mysql_num_rows($result);
				//echo $sql."<br/>";
				// recherche compte DB
				$sql = "SELECT db_users.login,accounts.password,data_bases.name as dbname,domains.name FROM db_users left join accounts on db_users.account_id=accounts.id left join data_bases on db_users.db_id=data_bases.id left join domains on data_bases.dom_id=domains.id WHERE domains.id=".$domid;
				$result = mysql_query($sql);
				if (!$result) throw new Exception($errContDb." <span style='color:red'>" . mysql_error()."</span>");
				$nbDb = mysql_num_rows($result);
				if ($nbDb >= 1) {
					$nbDb .= " -> ";
					while ($row = mysql_fetch_assoc($result)) {
						$nbDb .= $row["dbname"].", ";
					}
					$nbDb = substr($nbDb,0,-2);
				}
				//echo $sql."<br/>";
				// recherche compte MAIL
				$sql = "SELECT domains.name,mail_name,password,postbox,mbox_quota,autoresponder,redirect,redir_addr,mail_group,mail_redir.address FROM mail left join accounts on account_id=accounts.id left join domains on dom_id=domains.id left join mail_redir on mail.id=mn_id WHERE domains.id=".$domid;
				$result = mysql_query($sql);
				if (!$result) throw new Exception($errContDb." <span style='color:red'>" . mysql_error()."</span>");
				$nbMail = mysql_num_rows($result);
				//echo $sql."<br/>";
			} else $errMsg .= $errDomIdParam;
			break;
		case "psaimport":
			set_time_limit ( 600 ); // 10 minutes
			$execCmd = "./importPsa.sh $pleskPanelLogin $pleskPanelPassword $pleskServ $mysqlPsaImpUser $mysqlPsaImpPass $mysqlPsaImpDbName";
			$erreurMsg = shell_exec($execCmd);
			$erreurMsg = $execCmd."<br/>".nl2br($erreurMsg);
			break;
		
		default:
			break;
	}
} else $errMsg .= $sessiontimeout;
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
	
	<script type="text/javascript">
	<!--
	window.onload = function() {
		document.forms['importopt'].clientname.focus();
	}
	
	function findObj(n, d) {
	/* vieille methode utilisé avant à la place de getObj peut encore être utiliser pour trouver un element sans ID */
		var p,i,x;
		if(!d) d=document;
		if((p=n.indexOf("?"))>0&&parent.frames.length) {
	    	d=parent.frames[n.substring(p+1)].document;
			n=n.substring(0,p);
		}
		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
		return x;
	}
	
	function ValiderForm(nomForm) {
		if ((x=findObj(nomForm))!=null){
			if(nomForm == 'importopt') {
				if (x.clientname.value.length < 1){
					alert("<?php echo $javaScriptMsg2 ?>");
					x.clientname.focus();
					return;
				}
				x.submit();
			} else {
				x.submit();
			}
		}
		return;
	}

	function keyHandler(e) {
		var pressedKey;
	 	if (document.all)	{ e = window.event; }
	 	if (document.layers || e.which) { pressedKey = e.which; }
	 	if (document.all)	{ pressedKey = e.keyCode; }
	 	keyPressed = String.fromCharCode(pressedKey);
	 	//alert(' Character = ' + keyPressed + ' [Decimal value = ' + pressedKey + '] formEncours = '+formEncours);
		if (keyPressed == "\r" || keyPressed == "\n") {
			ValiderForm('importopt');
		}
	}
	document.onkeypress = keyHandler;
	//-->
	</script>
</head>
<body>
	<?php 
	if (isset($_SESSION['EW'])) {
		switch ($action) {
			case "domimport":
				?>
				<h2>Import <?php echo $nom_domaine ?></h2>
				<?php if ($errMsg != "") { echo $errMsg; } else {?>
				<form name="importopt" enctype="multipart/form-data" action="resultser.php?action=domimport&domid=<?php echo $domid ?>" method="post">
				<input type="hidden" name="domname" value="<?php echo $nom_domaine ?>"/>
				<?php echo $txtClientForImport?><input type="text" name="clientname" value=""/>
				<ul>
					<li><input type="checkbox" name="client" value="1"/> <?php echo $chkBoxImportClient ?> <span style="color:black">Not yet available</span></li>
					<li><input type="checkbox" name="site" value="1"/> <?php echo $chkBoxImportSite ?></li>
					<li><input type="checkbox" name="ftp" value="1"/> <?php echo $chkBoxImportFtp ?> (<?php echo $nbFtp ?>)</li>
					<li><input type="checkbox" name="ftpuserweb" value="1"/> <?php echo $chkBoxImportFtpWebUsers ?> <span style="color:black">Not yet available</span></li>
					<li><input type="checkbox" name="dns" value="1"/> <?php echo $chkBoxImportDns ?> <span style="color:black">Not yet available</span></li>
					<li><input type="checkbox" name="db" value="1"/> <?php echo $chkBoxImportDb ?> (<?php echo $nbDb ?>)</li>
					<li><input type="checkbox" name="mail" value="1"/> <?php echo $chkBoxImportMail ?> (<?php echo $nbMail ?>)</li>
					<li><input type="checkbox" name="fetchmail" value="1"/> <?php echo $chkBoxImportfetchmail ?></li>
				</ul>
				<div style="float:right;text-align:center"><div onmouseover="this.className='but7'" onmouseout="this.className='but6'" class="but6"><a href="javascript:ValiderForm('importopt');"><?php echo $btImportStart ?></a></div></div>
				</form>
				<?php }
				break;
			case "psaimport":
				?>
				<h2>Import de la base de donnée PSA</h2>
				<div style="float:right;text-align:center"><div onmouseover="this.className='but7'" onmouseout="this.className='but6'" class="but6"><a href="javascript:parent.eval('RefreshFromIframe()')"><?php echo $btImportPSAok ?></a></div></div>
				<hr>
				<?php
				echo $erreurMsg;
				break;
			
			default:
				break;
		}
	} else echo $sessiontimeout;
	?>
</body>
</html>