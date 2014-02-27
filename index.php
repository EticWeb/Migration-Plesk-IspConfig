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

$erreurLog = 0;
$erreurMsg = "";

$login = isset($_POST['login'])?$_POST['login']:"";
$pass = isset($_POST['pass'])?$_POST['pass']:"";

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
	unset($_SESSION['EW']);
} else if ( isset($_POST['login']) && isset($_POST['pass']) && $validLogin == $login && $validPass == $pass ) {
	//TEST LOGIN PASSWORD
	// On génère un nouveau sid
	$new_sid = SessionId(8, 21);
	// session id
	if (!isset($_SESSION['EW'])) {
		$_SESSION['EW'] = $new_sid;
	}
} else if ($login != "" && $pass != "") {
	$erreurLog = 2;
	$erreurMsg = '<h4>'.$errIdent.'</h4><span style="color:red">'.$errIdentMsg.'</span>';
}
$action = "";
if (isset($_SESSION['EW'])) {
	$action = isset($_GET['action'])?$_GET['action']:"";
	switch ($action) {
		case "psaimport":
			set_time_limit ( 600 ); // 10 minutes
			$execCmd = "./importPsa.sh $pleskPanelLogin $pleskPanelPassword $pleskServ $mysqlPsaImpUser $mysqlPsaImpPass $mysqlPsaImpDbName";
			$erreurMsg = shell_exec($execCmd);
			$erreurMsg = $execCmd."<br/>".nl2br($erreurMsg);
			$erreurLog = 2;
			break;

		default:
			break;
	}
} else $erreurLog = 1;
$menuBas = '<ul id="menufooter">';
$menuBas .= '<li><a target="ewcom" href="http://www.eticweb.com" title="EticWeb conseils informatique et dévellopement durable, spécialiste GREENIT, à grasse alpes maritimes">R&eacute;alisation EticWeb</a></li>';
$menuBas .= '<li><a target="ew-com" href="http://www.etic-web.com" title="Création site internet et hébergement EticWeb alpes maritimes france">Web Création</a></li>';
if (isset($_SESSION['EW'])) $menuBas .= '<li><a href="index.php?logout=1" style="color:red">LOGOUT</a></li>';
$menuBas .= '<li><a href="faq.php?keepThis=true&TB_iframe=true&height=500&width=500" title="Nous contacter" class="smoothbox">Faq</a></li>';
if (isset($_SESSION['EW'])) $menuBas .= '<li><a href="index.php" style="color:red">REFRESH</a></li>';
$menuBas .= '</ul>';

$menudom = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<!-- METAs -->
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
	
	<!-- LINKs -->
	<!-- <link rel="Shortcut Icon" href="img/favicon.ico" type="image/x-icon" /> -->
	<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="STYLESHEET" href="css/smoothbox/smoothbox.css" type="text/css"/>
	
	<!-- SCRIPTs -->	
	<script src="script/mootools.v1.00.full.packed.js" type="text/javascript"></script>
	<script src="script/smoothbox/smoothbox.js" type="text/javascript"></script>
	<script type="text/javascript">

	var accordion;
	var accordionTogglers;
	var accordionContents;
	
	window.onload = function() {
		accordionTogglers = document.getElementsByClassName('accToggler');
		accordionContents = document.getElementsByClassName('accContent');
		accordion = new Fx.Accordion(accordionTogglers, accordionContents, {
			alwaysHide:true,
			show:<?php echo $erreurLog;?>
		});
		if (<?php echo $erreurLog;?> == 1) document.forms['connect'].login.focus();
	}
	</script>
	<script type="text/javascript">
	<!--
	
	var formEncours; /* variable utilisée pour le keypress event gestionnaire pour savoir le formulaire a "submitter" */
	
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
			if(nomForm == 'connect') {
				if (x.login.value.length < 6 || x.pass.value.length < 6){
					alert("<?php echo $javaScriptMsg1 ?>");
					x.login.focus();
					return;
				}
				x.submit();
			} else {
				x.submit();
			}
		}
		return;
	}
	function RefreshFromIframe() {
		TB_remove();
		document.location.href = 'index.php';
	}

	function keyHandler(e) {
		var pressedKey;
	 	if (document.all)	{ e = window.event; }
	 	if (document.layers || e.which) { pressedKey = e.which; }
	 	if (document.all)	{ pressedKey = e.keyCode; }
	 	keyPressed = String.fromCharCode(pressedKey);
	 	//alert(' Character = ' + keyPressed + ' [Decimal value = ' + pressedKey + '] formEncours = '+formEncours);
		if (keyPressed == "\r" || keyPressed == "\n") {
			if (formEncours == 'connect' || formEncours == 'compte') {
				ValiderForm(formEncours);
			}
		}
	}
	document.onkeypress = keyHandler;
	//-->
	</script>
	<style type="text/css">
		.accToggler{
		width:100%;
		}
		.accContent{
		width:100%;
		}
		.content {
		width:80%; /*center hack*/
		margin:20px auto; /*center hack*/
		text-align : center;
		}
		body {
		/*text-align : center;*/
		background: #111 url('images/gradient1.gif') top left repeat-x;
		}
	</style>
	<!-- title -->
	<title>:: <?php echo $appName ?> ::</title>
	
</head>
<body>
<h1 style="float:left;">
	<a href="http://etic-web.com/">
		<span class="company"><?php echo $infoApp;?></span>
	</a><?php echo $titreActivitee;?><sup><?php echo $supTitre;?></sup>
</h1>
<div id="dockMouseArea">
&nbsp;<br />&nbsp;<br />
</div>
<div class="content">
	<div>
		<h4><?php echo $copyright ?></h4>
		<p class="accToggler"><?php echo $accord1 ?></p>
		<div  class='accContent' style='text-align:left;'>
		<?php echo $accord1txt;
		echo $accord1txt0;
		// verif rep /tmp/migra
		if (!is_dir('/tmp/migra')) echo $accord1txt1;
		// verif session active
		if (!isset($_SESSION["EW"])) echo $accord1txt2;
		// verif modif config ds fichier web_inf/setting.php
		if ($pleskPanelPassword == "") echo $accord1txt3;
		if (isset($_SESSION["EW"])) {
			try {
				connectSql($mysqlPsaImpUser,$mysqlPsaImpPass,$mysqlPsaImpDbName);
			} catch (Exception $e) {
			 	echo $accord1txt4;
			 	echo "<span style='color:red'>".$e->getMessage()."</span>";
			}
			try {
			 	$sql = "SELECT domains.name,domains.id,cname,account_id FROM domains left join clients on cl_id=clients.id ORDER BY domains.name";
				$result = mysql_query($sql);
				if (!$result) throw new Exception($errContDb." <span style='color:red'>" . mysql_error()."</span>");
				$cpt=1;
				while ($row = mysql_fetch_assoc($result)) {
					if ($cpt == 1) $menudom .= "<div style='float:left;text-align:left;margin:2px;min-width:300px'>";
					// Pour eviter de ré-importer 2 fois le même domaine on test si déjà prséent
					if (!is_dir($webSitePathOnNewserver.'/'.$row['name']))
						$menudom .= '<a href="javascript:TB_show(\''.$shadowImportTit.' <b>'.$row['name'].'</b>\', \'webser.php?action=domimport&domname='.$row['name'].'&domid='.$row['id'].'&keepThis=true&TB_iframe=true&height=400&width=550\')">'.$row['name']."</a><br/>";
					else
						$menudom .= '<small>'.$row['name'].'<a href="javascript:TB_show(\''.$shadowImportTit.' <b>'.$row['name'].'</b>\', \'webser.php?action=domimport&domname='.$row['name'].'&domid='.$row['id'].'&keepThis=true&TB_iframe=true&height=400&width=550\')"> !!!</a></small><br/>';
	        		if (is_int($cpt/4)) {
						$menudom .= "</div>";
						$cpt = 1;
					} else $cpt++;
				}
				if (!is_int($cpt/4) && $cpt != 1) $menudom .= "</div>";
			} catch (Exception $e) {
			 	echo $accord1txt5;
				echo "<span style='color:red'>".$e->getMessage()."</span>";
			}
			echo $accord1txt6;
		}
		?>
		</div>
		<?php if (!isset($_SESSION["EW"])) { ?>
		<p class="accToggler"><?php echo $accord2 ?></p>
		<div class="accContent">
			<form name="connect" enctype="multipart/form-data" action="index.php" method="post">
			<table cellspacing="2" cellpadding="0" border="0"  width="100%">
			<tr>
				<td align="right" width="50%">
					<?php echo $accord2txt1 ?> :&nbsp;
				</td>
				<td align="left" width="50%">
					<input type="text" name="login" value="<?php echo $login?>" size="12" maxlength="12" onfocus="formEncours='connect'"/>
				</td>
			</tr>
			<tr>
				<td align="right">
					<?php echo $accord2txt2 ?> :&nbsp;
				</td>
				<td align="left">
					<input type="password" name="pass" value="" size="12" maxlength="12" onfocus="formEncours='connect'"/>
				</td>
			</tr>
			</table>
			</form>
			<?php // <div onmouseover="this.className='but7'" onmouseout="this.className='but6'" class="but6"><a href="javascript:ValiderForm('connect')" title="Identification">Entrer</a></div> ?>
			<div style="float:right;"><div onmouseover="this.className='but7'" onmouseout="this.className='but6'" class="but6"><a href="javascript:ValiderForm('connect');" title="Identification admin"><?php echo $bIdentification ?></a></div></div>
		</div>
		<?php } ?>
		<p class="accToggler"><?php echo $accord3 ?></p>
		<div class="accContent" style="text-align:left;">
		<?php 
		if ($erreurLog == 2) {
			echo $erreurMsg;
		}
		$testCmd = "ssh root@".$pleskServ." 'ls -lt'";
		$returnshell = shell_exec($testCmd);
		echo 'TEST shell_exec : '.$testCmd."<br/>";
		echo nl2br($returnshell)."<br/>";
		if ($returnshell == "") echo "<blink><span style='color:red'>$errShelExecSSH</span></blink><br/>";
		$testCmd = "ls -lt";
		$returnshell2 = shell_exec($testCmd);
		echo 'TEST shell_exec : '.$testCmd."<br/>";
		echo nl2br($returnshell2)."<br/>";
		if ($returnshell.$returnshell2 == "") echo "<blink><span style='color:red'>$errShelExec</span></blink><br/>";
		if ($action == "psaimport" && $returnshell == "") echo "<blink><span style='color:red'>$accord3txt</span></blink><br/>";
		if (DEBUGEW) {
			?><center>
			<iframe name="phpinfo" src="infdebug.php" width="800" height="400" frameborder="1" ></iframe>
			</center><?php
		}
		?>
		</div>
		<p class="accToggler"><?php echo $accord4 ?></p>
		<div class="accContent"><?php echo $menudom ?></div>
		</div>
	</div>
	<div class="wraper"></div>
	<div id="footer"><?php echo $menuBas;?></div>
</div>
</body>
</html>

