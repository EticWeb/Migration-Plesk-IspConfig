<?php
/*
* This file is part of the Migration-Plesk9-IspConfig3 web-application.
*
* (c) EticWeb - Gaudemer sébastien. 
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

session_start();

require_once("web_inf/setting.php");

$lang = explode(',',$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
//var_dump($lang);
switch ($lang[0]) {
    case 'fr':
    case 'fr-fr':
        require_once('web_inf/lang/fr.php');
        break;
    default:
        require_once('web_inf/lang/en.php');
        break;
}
require_once("lib/NotfoundException.php");
require_once("lib/utilitaire.php");
?>
