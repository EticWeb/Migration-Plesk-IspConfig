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
require_once("lib/phpmailer/class.phpmailer.php");
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
	<meta name="description" content="Application de migration Plesk 9 vers IspConfig 3"/>
	<meta name="keywords" content="plesk, control panel, ispconfig"/>
	<meta name="generator" content="EticWeb SARL"/>
	<meta name="author" content="EticWeb" />
	<meta name="distribution" content="Global"/>
	<meta name="revisit-after" content="14 days"/>
	<meta name="robots" content="index, follow"/>
	<meta name="MSSmartTagsPreventParsing" content="true" />
	<meta name="rating" content="general" />
	<meta name="copyright" content="Copyright &copy; 2011 www.eticweb.com" />
	<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" charset="utf-8" />
<title><?php echo $faqTitle ?></title>
</head>
<?php
$laDate = date("m/d/Y H:i:s");

$ip = getHostByAddr($_SERVER['REMOTE_ADDR']);

$eol="\r\n";

$leMessage = "Un utilisateur de <b>Migration-Plesk9-IspConfig3</b> vous laisse un message".$eol.
			"Nom : ".$_POST['fname'].$eol.
			"Prénom : ".$_POST['lname'].$eol.
			"Email : ".$_POST['email'].$eol.
			"Voici le message de votre visiteur :".$eol.
			"------------------------------".$eol.
			$_POST['message'].$eol.
			"Logged Info :".$eol.
			"------------------------------".$eol.
			"Using : ".$_SERVER['HTTP_USER_AGENT'].$eol.
			"Hostname : ".$ip.$eol.
			"Date et Heure :  ".$laDate.$eol.
			"Addresse IP : ".$_SERVER['REMOTE_ADDR'];


$mail = new PHPMailer();
$mail->SetLanguage("fr","lib/phpmailer/language/");

//$mail->IsSMTP();	// set mailer to use SMTP
$mail->IsMail();
$mail->Host = $_SERVER['HTTP_HOST'];  // specify main and backup server
//$mail->CharSet = "iso-8859-15";
$mail->CharSet = "utf-8";
//$mail->SMTPAuth = true;     // turn on SMTP authentication
//$mail->Username = $smtpUser;  // SMTP username
//$mail->Password = $smtpPass; // SMTP password
//print_r($_SERVER);

$mail->From = "webform@".$_SERVER['HTTP_HOST'];
$mail->FromName = "Formulaire contact Migration-Ples9-IspConfig3";
$mail->AddAddress("contact@eticweb.com", "Migration-Plesk9-IspConfig3");// name is optional
$mail->AddReplyTo($_POST['email'], $_POST['fname']." ".$_POST['lname']);

$mail->WordWrap = 5000;                               // set word wrap to 50 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "Contact on Migration-Ples9-IspConfig3";
$mail->Body    = str_replace($eol,'<br>',$leMessage);
$mail->AltBody = str_replace('<b>','"',str_replace('</b>','"',$leMessage));

if(trim($leMessage)=="" || !$mail->Send())
{
   echo "Message could not be sent. Or no message to send<p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}
?>

<body><div style="margin-top:150px;text-align:center">
<?php echo $shadowResultmail ?>
</div>
</body>
</html>
