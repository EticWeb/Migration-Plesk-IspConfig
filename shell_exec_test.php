<?php
$handle = fopen("testUser.txt", "w+");

$testCmd = "ssh root@de811.ispfr.net 'ls -lt'";
$returnshell = shell_exec($testCmd);
echo 'TEST shell_exec : '.$testCmd."<br/>";
echo nl2br($returnshell)."<br/>";
$testCmd = "ls -lt";
$returnshell = shell_exec($testCmd);
echo 'TEST shell_exec : '.$testCmd."<br/>";
echo nl2br($returnshell)."<br/>";
?>

