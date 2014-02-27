<?php
/*
* This file is part of the Migration-Plesk9-IspConfig3 web-application.
*
* (c) EticWeb - Gaudemer sébastien. 
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

function SessionId($longueur, $num_demandeur) {

  $Pool  = "012345678";
  $Pool .= "abcdefghijklmnopqrstuvwxyz";
  $Pool .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $sid="";

  for ($index = 0; $index < $longueur; $index++)
    $sid .= substr($Pool, (rand()%(strlen($Pool))), 1);

  return( $sid );

}

function connectSql($dbusr,$dbpass,$dbname) {
	global $errCnxDb,$errSltDb;
	$conn = @mysql_connect("localhost", $dbusr, $dbpass);
	if (!$conn) {
	    throw new Exception($errCnxDb." <span style='color:red'>" . mysql_error()."</span>");
	}
	if (!mysql_select_db($dbname)) {
	    throw new Exception($errSltDb.$dbname." <span style='color:red'>" . mysql_error()."</span>");
	}
	
	return $conn;
}

function testSoapResult($soapreturn,$debug=false) {
	if ($debug) {
		$test = @is_int(2)?" TRUE ":" FALSE ";
		$test .= @is_int("2")?" TRUE ":" FALSE ";
		$test .= @is_int(array("1","e"))?" TRUE ":" FALSE ";
		throw new Exception( "Debug Info".$test );
	}
	if (empty($soapreturn)) throw new NotfoundException( "Not Found ret(" . $soapreturn . ") Empty result" );
	if (is_int($soapreturn)) return $soapreturn;
	if (!is_array($soapreturn)) throw new NotfoundException( "Not Found ret(" . $soapreturn . ")" );
	return $soapreturn;
}

function addSiteWeb($server_id,$client,$session_id,$client_id,$sys_userid,$sys_groupid,$nom_domaine,$default_group) {
	/*
	alter table web_domain auto_increment=12;
	alter table ftp_user auto_increment=11;
	 */
	global $importStepDomaine1 ,$importStepDomaine2,$importStepSearchOk,$siteQuota;
	echo "----------------------------------------<br/>";
	echo $importStepDomaine1."<br/>";
	$web_domain_id = -1;
	try {
		// Vérification si SiteWeb déjà enregistré
		$soapreturn = testSoapResult($client->client_get_sites_by_user($session_id, $sys_userid, $default_group));
		//print_r($soapreturn);
		if (DEBUGEW) echo "SELECT domain, domain_id, document_root, active FROM web_domain WHERE ( (sys_userid = $sys_userid AND sys_perm_user LIKE '%r%') OR (sys_groupid IN ($default_group) AND sys_perm_group LIKE '%r%') OR sys_perm_other LIKE '%r%') AND type = 'vhost'<br/>";
		foreach ($soapreturn as $site) {
			if ($site['domain'] == $nom_domaine) {
				$web_domain_id = $site['domain_id'];
				echo $importStepSearchOk." web_domain_id=".$web_domain_id."<br/>";
				break;
			}
			if (DEBUGEW) {
				echo " SiteWeb $nom_domaine == ".$site['domain']." : <div style='margin-left:20px'>";
				foreach ($site as $key => $value) {
					echo $key . " --> " . $value . "<br/>";
				}
				echo "</div>";
			}
		}
	} catch (Exception $e) {
	 	throw new Exception("Add SiteWeb Search ".$e->getMessage());
	}
	// ADD Domaine
	if ($web_domain_id == -1) {
		echo $importStepDomaine2."<br/>";
		// Domaine non existant
		$params = array(
			'server_id' => $server_id,
			'ip_address' => '*',
			'domain' => $nom_domaine,
			'type' => 'vhost',
			'parent_domain_id' => 0,
			'vhost_type' => 'name',
			'hd_quota' => $siteQuota,
			'traffic_quota' => -1,
			'cgi' => 'n',
			'ssi' => 'n',
			'suexec' => 'y',
			'errordocs' => 0,
			'is_subdomainwww' => 1,
			'subdomain' => 'www',
			'php' => 'fast-cgi',
			'ruby' => 'n',
			'redirect_type' => '',
			'redirect_path' => '',
			'ssl' => 'n',
			'ssl_state' => '',
			'ssl_locality' => '',
			'ssl_organisation' => '',
			'ssl_organisation_unit' => '',
			'ssl_country' => 'FR',
			'ssl_domain' => $nom_domaine,
			'ssl_request' => '',
			'ssl_cert' => '',
			'ssl_bundle' => '',
			'ssl_action' => '',
			'stats_password' => '',
			'stats_type' => 'webalizer',
			'allow_override' => 'All',
			'apache_directives' => '',
			'php_open_basedir' => '',
			'custom_php_ini' => '',
			'backup_interval' => '',
			'backup_copies' => 1,
			'active' => 'y',
			'traffic_quota_lock' => 'n'
		);
		//echo "SELECT groupid FROM sys_group WHERE client_id = ".intval($client_id);
		try {
			// REMARQUE lors de la création il faut donner le $sys_userid et non le $client_id comme indiqué dans la doc car sinon le domaine est pas rataché au bon compte.
			if (DEBUGEW) echo "SOAP sites_web_domain_add($session_id, client_id:$client_id, sys_userid:$sys_userid)<br/>";
			$soapreturn = testSoapResult($client->sites_web_domain_add($session_id, $client_id, $params));
			$web_domain_id = $soapreturn;
		} catch (Exception $e) {
		 	throw new Exception( "Add SiteWeb ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
		}
	}
	
	return $web_domain_id;
}

function addDatabases($server_id,$client,$session_id,$client_id,$plesk_domid) {
	global $importStepDatabase1,$importStepDatabase2,$importStepDatabase4,$btImportDatabaseStart,$Database_name_prefix,$Database_user_prefix;
	global $mysqlPsaImpUser,$mysqlPsaImpPass,$mysqlPsaImpDbName,$mysqlIspServRootPass;
	global $pleskPanelLogin,$pleskPanelPassword,$pleskServ;
	echo "----------------------------------------<br/>";
	echo $importStepDatabase1."<br/>";
	$database_id = "";
	try {
		$mysqlcon = connectSql($mysqlPsaImpUser,$mysqlPsaImpPass,$mysqlPsaImpDbName);
	} catch (Exception $e) {
	 	throw new Exception( "Add Database ".$e->getMessage() );
	}
	// Recup ancienne database
	$user_dbs = array();
	$user_dbsid = array();
	try {
		// Vérification si Database déjà enregistrées
		$soapreturn = testSoapResult($client->sites_database_get_all_by_user($session_id, $client_id));
		//print_r($soapreturn);
		foreach ($soapreturn as $dbs) {
			$user_dbs[] = $dbs['database_name'];
			$user_dbsid[] = $dbs['database_id'];
			if (DEBUGEW) {
				echo " Database : <div style='margin-left:20px'>";
				foreach ($dbs as $key => $value) {
					echo $key . " --> " . $value . "<br/>";
				}
				echo "</div>";
			}
		}
	} catch (NotfoundException $e) {
	 	echo "--------------(o)-------------------------(o)----------------";
	} catch (Exception $e) {
	 	throw new Exception( "Search Database ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
	}

	$sql = "SELECT db_users.login,accounts.password,data_bases.name as dbname,domains.name FROM db_users left join accounts on db_users.account_id=accounts.id left join data_bases on db_users.db_id=data_bases.id left join domains on data_bases.dom_id=domains.id WHERE domains.id=".$plesk_domid;
	if (DEBUGEW) echo $sql."<br/>";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)) echo $importStepDatabase4."<br/>";
	while ($row = mysql_fetch_assoc($result)) {
		$dbName = substr(str_replace('[CLIENTID]',$client_id,$Database_name_prefix).str_replace('-','_',$row['dbname']),0,64);
		if (!in_array($dbName, $user_dbs)) {
			set_time_limit ( 600 ); // 10 minutes
			$params = array(
				'server_id' => $server_id,
				'type' => 'mysql',
				'database_name' => $dbName,
				'database_user' => substr(str_replace('[CLIENTID]',$client_id,$Database_user_prefix).str_replace('-','_',$row['login']),0,16),
				'database_password' => $row['password'],
				'database_charset' =>  'utf8',
				'remote_access' => 'n', // n disabled - y enabled
				'active' => 'y', // n disabled - y enabled
				'remote_ips' => ''
			);
			try {
				echo $importStepDatabase2."<br/>";
				$soapreturn = testSoapResult($client->sites_database_add($session_id, $client_id, $params));
				$database_id = $soapreturn;
				// Bouton pour appel ajax import DB
				echo '<div style="float:right;text-align:center"><div onmouseover="this.className=\'but7\'" onmouseout="this.className=\'but6\'" class="but6"><a href="#followajax" onclick="ajaxRequestShellDb(\'script=importDb&idDb='.$database_id.'&oldN='.$row['dbname'].'\');">'.$row['dbname'].'</a></div></div><br/>';
			} catch (Exception $e) {
			 	throw new Exception( "Add Database ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
			}
		} else {
			// Bouton pour appel ajax import DB
			echo '<div style="clear:both"></div><div style="float:right;text-align:center"><div onmouseover="this.className=\'but7\'" onmouseout="this.className=\'but6\'" class="but6"><a href="#followajax" onclick="ajaxRequestShellDb(\'script=importDb&idDb='.$user_dbsid[array_search($row['dbname'], $user_dbs)].'\');">'.$row['dbname'].'</a></div></div><br/>';
		}
		set_time_limit ( 600 ); // 10 minutes
		
	}
		
	@mysql_close($mysqlcon);
	
	//return substr($database_id,0,-1);
}

function addFtp($server_id,$client,$session_id,$client_id,$domain_id,$plesk_domid) {
	global $importStepFtp;
	global $mysqlPsaImpUser,$mysqlPsaImpPass,$mysqlPsaImpDbName;
	$document_root = "";
	echo "----------------------------------------<br/>";
	echo $importStepFtp."<br/>";
	try {
		$soapreturn = testSoapResult($client->sites_web_domain_get($session_id, $domain_id));
		$document_root = $soapreturn['document_root'];
		if (DEBUGEW) {
			echo " SiteWeb ".$soapreturn['domain']." info : <div style='margin-left:20px'>";
			foreach ($soapreturn as $key => $value) {
				echo $key . " --> " . $value . "<br/>";
			}
			echo "</div>";
		}
	} catch (Exception $e) {
	 	throw new Exception( "Add Ftp Search Document Root".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
	}
	if ($document_root != "") {
		try {
			$mysqlcon = connectSql($mysqlPsaImpUser,$mysqlPsaImpPass,$mysqlPsaImpDbName);
		} catch (Exception $e) {
			throw new Exception( "Add Ftp ".$e->getMessage() );
		}
			
		$sql = "SELECT domains.name,sys_users.login as ftp_login,accounts.password as ftp_pwd,hosting.* FROM domains left join hosting on domains.id = hosting.dom_id right join sys_users on hosting.sys_user_id=sys_users.id left join accounts  on sys_users.account_id=accounts.id WHERE domains.id=".$plesk_domid;
		if (DEBUGEW) echo $sql."<br/>";
		$result = mysql_query($sql);
		if (mysql_numrows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				$params = array(
					'server_id' => $server_id,
					'parent_domain_id' => $domain_id,
					'username' => $row["ftp_login"],
					'password' => $row["ftp_pwd"],
					'quota_size' => 250,
					'active' => 'y',
					'uid' => 'web'.$domain_id,
					'gid' => 'client'.$client_id,
					'dir' => $document_root,
					'quota_files' => -1,
					'ul_ratio' => -1,
					'dl_ratio' => -1,
					'ul_bandwidth' => -1,
					'dl_bandwidth' => -1
				);
				try {
					$soapreturn = testSoapResult($client->sites_ftp_user_add($session_id, $client_id, $params));
					echo "Add Ftp user ".$row["ftp_login"]." pass ".$row["ftp_pwd"]." on domain ".$row["name"]."<br/>";
					$ftpclient_id = $soapreturn;
				} catch (Exception $e) {
				 	throw new Exception( "Add Ftp ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
				}
			}
		} else throw new Exception( "Add Ftp no record");
		
		@mysql_close($mysqlcon);
	} else throw new Exception( "Add Ftp no DocumentRoot found." );
	
	return $ftpclient_id;
}

function addMails($client,$session_id,$client_id,$nom_domaine,$plesk_domid,$imp_fetchmail,$server_id) {
	global $importStepMail1,$importStepMail2,$importStepMail3,$importStepSearchOk;
	global $mysqlPsaImpUser,$mysqlPsaImpPass,$mysqlPsaImpDbName;
	echo "----------------------------------------<br/>";
	echo $importStepMail1."<br/>";
	$mail_domain_id = -1;
	$return_msg = "";
	try {
		$soapreturn = testSoapResult($client->mail_domain_get_by_domain($session_id, $nom_domaine));
		if (DEBUGEW) {
			echo " MailDomain $nom_domaine == ".$nom_domaine." : <div style='margin-left:20px'>";
			foreach ($soapreturn as $key => $value) {
				echo $key . " --> " . $value . "<br/>";
				if (is_array($value)) {
					echo "<div style='margin-left:20px'>";
					foreach ($value as $subkey => $subvalue) {
						echo $subkey . " --> " . $subvalue . "<br/>";
					}
					echo "</div>";
				}
			}
			echo "</div>";
		}
		$mail_domain_id = $soapreturn[0]['domain_id'];
		echo $importStepSearchOk." mail_domain_id=".$mail_domain_id."<br/>";
	} catch (Exception $e) {
		// Mail Domaine inexistant donc on l'ajoute
	 	echo $importStepMail2."<br/>";
		try {
			$params = array(
				'server_id' => $server_id,
				'domain' => $nom_domaine,
				'active' => 'y'
			);
			$soapreturn = testSoapResult($client->mail_domain_add($session_id, $client_id, $params));
			$mail_domain_id = $soapreturn;
		} catch (Exception $e) {
		 	throw new Exception( "MailDomain Add ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
		}
	}
	if ($mail_domain_id != -1) {
		// Traitement anciens comptes
		try {
			$mysqlcon = connectSql($mysqlPsaImpUser,$mysqlPsaImpPass,$mysqlPsaImpDbName);
		} catch (Exception $e) {
		 	throw new Exception( "Add Mail ".$e->getMessage() );
		}
	
		echo $importStepMail3."<br/>";
		$sql = "SELECT domains.name,mail_name,password,postbox,mbox_quota,autoresponder,redirect,redir_addr,mail_group,mail_redir.address FROM mail left join accounts on account_id=accounts.id left join domains on dom_id=domains.id left join mail_redir on mail.id=mn_id WHERE domains.id=".$plesk_domid;
		if (DEBUGEW) echo $sql."<br/>";
		$result = mysql_query($sql);
		
		while ($row = mysql_fetch_assoc($result)) {
			set_time_limit ( 600 ); // 10 minutes
			if (DEBUGEW) {
				echo " Mails du Domaine $nom_domaine<div style='margin-left:20px'>";
				foreach ($row as $key => $value) {
					echo $key . " --> " . $value . "<br/>";
				}
				echo "</div>";
			}
			/* Création Comptes Alias Forward et Fetch */
			try {
				if ($row['mail_group'] == 'true') {
					// Cas du Forward = Groupe ds Plesk
					$return_msg = addForward($server_id,$client,$session_id,$client_id,$row);
					// TODO store in array email(s) associate to group-forward before to call addForward, because actually we add one forward line for each destination instead of one for multi destination
				} else if ($row['redir_addr'] != '' && $row['redirect'] == 'true') {
					// Cas du Alias = Redirect ds Plesk
					$return_msg = addRedirect($server_id,$client,$session_id,$client_id,$row);
				} else {
					// Compte classique
					$return_msg = addMail($server_id,$client,$session_id,$client_id,$row,$imp_fetchmail);
				}
			} catch (Exception $e) {
				throw new Exception( "Add Mails ".$e->getMessage() );
				//$return_msg .= $e->getMessage()."<br/>";
			}
			if (DEBUGEW) {
				echo $return_msg;
				$return_msg = "";
			}
		}
		
		@mysql_close($mysqlcon);
	}
		
}

function addMail($server_id,$client,$session_id,$client_id,$row,$imp_fetchmail=false) {
	global $mailQuota,$mailAccountPathOnNewserver;
	$return_msg = "";
	//'quota' => is_int($row["mbox_quota"])?$row["mbox_quota"]:'50000',
	//$mailAccountPathOnNewserver.$nom_domaine/".$row["mail_name"]
	$params = array(
		'server_id' => $server_id,
		'email' => $row["mail_name"].'@'.$row["name"],
		'login' => $row["mail_name"].'@'.$row["name"],
		'password' => $row["password"],
		'name' => $row["mail_name"],
		'uid' => '5000',
		'gid' => '5000',
		'maildir' => $mailAccountPathOnNewserver.'/'.$row["name"].'/'.$row["mail_name"],
		'quota' => $mailQuota,
		'cc' => '',
		'homedir' => $mailAccountPathOnNewserver,
		'autoresponder' => 'n',
		'autoresponder_start_date' => '',
		'autoresponder_end_date' => '',
		'autoresponder_text' => '',
		'move_junk' => 'n',
		'custom_mailfilter' => '',
		'postfix' => 'y',
		'access' => 'n',
		'disableimap' => 'n',
		'disablepop3' => 'n',
		'disabledeliver' => 'n',
		'disablesmtp' => 'n'
	);
	try {
		$soapreturn = testSoapResult($client->mail_user_add($session_id, $client_id, $params));
		$return_msg .= " AddMail -> ".$row["mail_name"].'@'.$row["name"]."<br/>";
	} catch (Exception $e) {
		//echo $e->getCode();
		if (false === strpos($e->getMessage(),"email_error_unique")) throw new Exception( $row["mail_name"].'@'.$row["name"]." addMail Message ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
 		else  $return_msg .= " AddMail WARNING 'email_error_unique' for ".$row["mail_name"].'@'.$row["name"]."<br/>";
	}
	if ($imp_fetchmail) {
		// Cas du fetchmail
		$return_msg .= addFetch($server_id,$client,$session_id,$client_id,$row);
	}
	return $return_msg;
}

function addForward($server_id,$client,$session_id,$client_id,$row) {
	$params = array(
		'server_id' => $server_id,
		'source' => $row["mail_name"].'@'.$row["name"],
		'destination' => $row["address"],
		'type' => 'forward',
		'active' => 'y'
	);

	try {
		$soapreturn = testSoapResult($client->mail_forward_add($session_id, $client_id, $params));
	} catch (Exception $e) {
		throw new Exception( $row["mail_name"].'@'.$row["name"]." addForward Message ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
	}
	return " AddForward -> ".$row["mail_name"].'@'.$row["name"]."<br/>";
}

function addRedirect($server_id,$client,$session_id,$client_id,$row) {
	$params = array(
		'server_id' => $server_id,
		'source' => $row["mail_name"].'@'.$row["name"],
		'destination' => $row["redir_addr"],
		'type' => 'alias',
		'active' => 'y'
	);

	try {
		$soapreturn = testSoapResult($client->mail_alias_add($session_id, $client_id, $params));
	} catch (Exception $e) {
		throw new Exception( $row["mail_name"].'@'.$row["name"]." addRedirect Message ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
	}
	return " AddRedirect -> ".$row["mail_name"].'@'.$row["name"]."<br/>";
}

function addFetch($server_id,$client,$session_id,$client_id,$row) {
	global $pleskServ;
	$params = array(
		'server_id' => $server_id,
		'type' => 'imap',
		'source_server' => $pleskServ,
		'source_username' => $row["mail_name"].'@'.$row["name"],
		'source_password' => $row["password"],
		'source_delete' => 'y',
		'destination' => $row["mail_name"].'@'.$row["name"],
		'active' => 'y',
		'source_read_all' => 'n',
	);

	try {
		$soapreturn = testSoapResult($client->mail_fetchmail_add($session_id, $client_id, $params));
	} catch (Exception $e) {
		throw new Exception( $row["mail_name"].'@'.$row["name"]." addFetch Message ".$e->getMessage()."<br/>LastResponse -> ".$client->__getLastResponse() );
	}
	return " AddFetchMail -> ".$row["mail_name"].'@'.$row["name"]."<br/>";
}
?>
