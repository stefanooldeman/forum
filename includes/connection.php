<?php // Connect

if(true) {
	include 'config_mirror.php';
} else {
	include 'config.php';
}

$connection = mysql_connect("localhost", $mysql['db_user'], $mysql['db_password']);
if (!$connection){
	die("Database connection failed: ". mysql_error());
}
// DB Select
$db_select = mysql_select_db($mysql['db_name'], $connection);
if (!$db_select){
	die("Database selection failed: ". mysql_error());
} ?>