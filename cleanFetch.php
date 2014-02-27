<?php
/*
* This file is part of the Migration-Plesk9-IspConfig3 web-application.
*
* (c) EticWeb - Gaudemer sébastien. 
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

/*******************************************************************************
/* Goal of that script :
 * 		delete fetchmail created during migration process, the fetch mail could be safetly deleted after one or two weeks DNS update
 ******************************************************************************/
require_once("web_inf/app_global.php");
if (isset($_SESSION['EW'])) {
	echo "<h1>Nettoyage Fetchmail</h1>";
	try {
		connectSql('root',$mysqlIspServRootPass,'dbispconfig');
	} catch (Exception $e) {
	 	echo "<span style='color:red'>".$e->getMessage()."</span>";
	}
	$sql = "SELECT mailget_id,source_username FROM mail_get WHERE source_server = 'de811.ispfr.net' ORDER BY mailget_id";
	$result = mysql_query($sql);
	if ($result) {
		$nbFetch = mysql_num_rows($result);
		echo $nbFetch."--> Fetch mail to delete<br>";

		set_time_limit ( 600 ); // 10 minutes
		$client = new SoapClient(null, array('location' => $soap_location, 'uri' => $soap_uri, 'trace' => 1, 'exceptions' => 1));
		try {
			// Open SOAP CNX
			if($session_id = $client->login($remoteUserLogin,$remoteUserPass)) {
				echo 'Logged successfull. Session ID:'.$session_id.'<br />';
			}
			while ($row = mysql_fetch_assoc($result)) {
				//* Delete fetchmail record
				$affected_rows = $client->mail_fetchmail_delete($session_id, $row['mailget_id']);
				echo "Number of records that have been deleted: ".$affected_rows." fetchmail --> mailget_id:".$row['mailget_id']." source_username:".$row['source_username']."<br>";
			}

			if($client->logout($session_id)) {
				echo 'Logged out.<br />';
			}
		} catch (SoapFault $e) {
			echo $client->__getLastResponse();
			die('SOAP Error: '.$e->getMessage());
		}
		echo "<hr>".$strForwardAll;
	}
}
?>