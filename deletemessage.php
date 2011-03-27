<?php
require_once("includes/connection.php");
include_once("includes/functions.php");
if(isset($_GET['id'])){
	$query ="DELETE FROM inbox WHERE id={$_GET['id']} LIMIT 1";
	$result_set = mysql_query($query);
	confirm_query($result_set);
	if(mysql_affected_rows() == 1) {
		redirect_to("inbox.php");
	}
} else {redirect_to("thread.php");}
?>