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
if (isset($_SESSION['EW'])) {
	$script = isset($_REQUEST['script'])?$_REQUEST['script']:"";
	switch ($script) {
		case "importSite":
			$nom_domaine = isset($_REQUEST['sitename'])?$_REQUEST['sitename']:"-1";
			$grid = isset($_REQUEST['grid'])?$_REQUEST['grid']:"-1";
			$clid = isset($_REQUEST['clid'])?$_REQUEST['clid']:"-1";
			if ( $nom_domaine != -1 ) {
				set_time_limit ( 600 ); // 10 minutes
				$execCmd = "./$script.sh $pleskServ $nom_domaine $grid $clid";
				$erreurMsg = shell_exec($execCmd);
				$erreurMsg = $execCmd."<br/>".nl2br($erreurMsg)."<br/>";
				echo $erreurMsg;
				echo "<blink><span style='color:red'>$importShellExecNotWorkingSite</span></blink><br/>";
			} else echo "Bad request Site.";
			break;
		case "importDb":
			$database_id = isset($_REQUEST['idDb'])?$_REQUEST['idDb']:"-1";
			$oldDbName = isset($_REQUEST['oldN'])?$_REQUEST['oldN']:"";
			if ( $database_id != -1 ) {
				set_time_limit ( 600 ); // 10 minutes
				$client = new SoapClient(null, array('location' => $soap_location, 'uri' => $soap_uri, 'trace' => 1, 'exceptions' => 1));
				try {
					// Open SOAP CNX
					if($session_id = $client->login($remoteUserLogin,$remoteUserPass)) {
						echo $soapMsgCnx.$session_id."<br /><a href='javascript:history.go(-1)'><<--</a><br />";
					}
					
					echo $importStepDatabase2."<br/>";
					$soapreturn = testSoapResult($client->sites_database_get($session_id, $database_id));
					
					$execCmd = "./$script.sh $pleskPanelLogin $pleskPanelPassword $pleskServ ".$soapreturn['database_user']." ".$soapreturn['database_password']." ".$soapreturn['database_name']." $mysqlIspServRootPass $oldDbName";
					$erreurMsg = shell_exec($execCmd);
					$erreurMsg = $execCmd."<br/>".nl2br($erreurMsg)."<br/>";
					// Close SOAP CNX
					if($client->logout($session_id)) {
						echo $soapMsgCnxClose.'<br />';
					}
					echo $erreurMsg;
					echo "<blink><span style='color:red'>$importShellExecNotWorkingDatabase</span></blink><br/>";
					
				} catch (Exception $e) {
				 	echo "<span style='color:red'> CallShell Database ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse()."</span>";
				}
			} else echo "Bad request DB.";
			break;
		default:
			echo "Bad request.";
			break;
	}
} else echo $sessiontimeout;
?>