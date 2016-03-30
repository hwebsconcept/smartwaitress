<?php
header('Access-Control-Allow-Origin: *');
	
$hostname_connect = "truparse.ipagemysql.com";
$database_connect = "smartwaitress";
$username_connect = "smartwaitress";
$password_connect = "Henry01.";
$connect = mysql_pconnect($hostname_connect, $username_connect, $password_connect) or trigger_error(mysql_error(),E_USER_ERROR); 
$db=mysql_select_db($database_connect,$connect);	
?>