<?php
require_once("includes/connection.php");
include_once("includes/functions.php");
//$_GET['id'] > 0
if(isset($_GET['id'])){
	$query ="DELETE FROM comment WHERE id={$_GET['id']} LIMIT 1";
	$project_set = mysql_query($query);
	confirm_query($project_set);
	if(mysql_affected_rows() == 1) {
		redirect_to('comments');
		include("includes/header.php");
	} else{
		print "<ul>\n";
		print "<li>Whooops!</li>\n";
		print "<ul>\n";
		print "<li>Bad thing has happend:<b>". mysql_error() ."</b></li>\n";
		print "<li>The Page is not found</li>\n";
		print "</ul></ul>\n";
	}
} else {
	print "Page not found" . " ";
	print "<br />";
	print "<a href='index.php'>get your ass back</a>";
}
require("includes/footer.php");
?>