<?php // Connect	
$connection = mysql_connect("localhost", "", "");
if (!$connection){
	die("Database connection failed: ". mysql_error());
}
// DB Select
$db_select = mysql_select_db("", $connection);
if (!$db_select){
	die("Database selection failed: ". mysql_error());
} ?>