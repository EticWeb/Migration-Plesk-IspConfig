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
<script language="JavaScript">
<!--
function go(){
	document.forms.contact.submit();
} 

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' <?php echo $faqAlert6 ?>.\n';
      } else if (test!='R') {
        if (isNaN(val)) errors+='- '+nm+' <?php echo $faqAlert5 ?>.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (val<min || max<val) errors+='- '+nm+' <?php echo $faqAlert4 ?>.\n';
    } } } 
	else if (test.charAt(0) == 'R') errors += '- '+nm+' = Obligatoire.\n'; 
	}
  } 
  if (errors) {alert('<?php echo $faqAlert3 ?>\n'+errors);}
  	
  document.MM_returnValue = (errors == '');
}

//-->
</script>
<title><?php echo $faqTitle ?></title>
</head>

<body>
<?php echo $accord1txt ?>
<?php echo $faqStr1 ?>
<form onReset="return confirm('<?php echo $faqAlert1 ?>')" name="contact" method="post" action="mailform.php">
    <h4><?php echo $faqTitle1 ?></h4>
    <blockquote>
    <table>
        <tbody>
            <tr>
                <td><?php echo $faqField1 ?></td>
                <td><input type="text" name="fname" size="50" maxlength="100" /></td>
            </tr>
            <tr>
                <td><?php echo $faqField2 ?></td>
                <td><input type="text" name="lname" size="50" maxlength="100" /></td>
            </tr>
            <tr>
                <td><?php echo $faqField3 ?></td>
                <td><input type="text" name="email" size="50" maxlength="100" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><?php echo $faqField4 ?></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><textarea name="message" cols="50" rows="8"></textarea></td>
            </tr>
        </tbody>
    </table>
    </blockquote>
    <p align="right"><a href="javascript:document.contact.reset();"><?php echo $faqBouton1 ?></a>&nbsp;&nbsp;<a href="javascript:MM_validateForm('Message','','R','email','','RisEmail');if (document.MM_returnValue) go(); else alert('<?php echo $faqAlert2 ?>');"><?php echo $faqBouton2 ?></a></p>
</form>
<p><strong><?php echo $faqTitle2 ?></strong></p>
</body>
</html>