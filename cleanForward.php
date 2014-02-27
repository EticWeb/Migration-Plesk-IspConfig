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
 * 		merge multiple forward created for each destination and update the first one with the destination to all over record initialy created
 ******************************************************************************/

require_once("web_inf/app_global.php");
if (isset($_SESSION['EW'])) {
	echo "<h1>Nettoyage Forward</h1>";
	try {
		connectSql('root',$mysqlIspServRootPass,'dbispconfig');
	} catch (Exception $e) {
	 	echo "<span style='color:red'>".$e->getMessage()."</span>";
	}
	$sql = "SELECT * FROM mail_forwarding WHERE source = 'emailforward@domaine.tld' ORDER BY forwarding_id";
	$result = mysql_query($sql);
	if ($result) {
		$nbFwd = mysql_num_rows($result);
		$row = mysql_fetch_assoc($result);
		$fwdIdFirst = $row['forwarding_id'];
		echo $nbFwd."-- first : ".$fwdIdFirst."<br>";
		$strForwardAll=$row['destination']."\r\n";

		

		set_time_limit ( 600 ); // 10 minutes
		$client = new SoapClient(null, array('location' => $soap_location, 'uri' => $soap_uri, 'trace' => 1, 'exceptions' => 1));
		try {
			// Open SOAP CNX
			if($session_id = $client->login($remoteUserLogin,$remoteUserPass)) {
				echo 'Logged successfull. Session ID:'.$session_id.'<br />';
			}
			while ($row = mysql_fetch_assoc($result)) {
				$strForwardAll.=$row['destination']."\r\n";
				//* Delete email forward record
				$affected_rows = $client->mail_forward_delete($session_id, $row['forwarding_id']);
			
				echo "Number of records that have been deleted: ".$affected_rows."-->".$row['destination']."<br>";
				
			}
			//* Get the email forward record
			$mail_forward_record = $client->mail_forward_get($session_id, $fwdIdFirst);
		
			//* Change the destination content
			$mail_forward_record['destination'] = $strForwardAll;
			
			$affected_rows = $client->mail_forward_update($session_id, $mail_forward_record['sys_userid'], $fwdIdFirst, $mail_forward_record);
		
			echo "Number of records that have been changed in the database: ".$affected_rows."<br>";

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