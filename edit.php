<?php
require_once("includes/session.php"); 
require_once("includes/connection.php");
include_once("includes/functions.php");
confirm_logged_in(); 
if(isset($_GET['id'])){
	$query = "SELECT post_by, user_id, comment FROM comment WHERE id = {$_GET['id']} LIMIT 1";
	$result = mysql_query($query, $connection);
	confirm_query($query);
} else {
	redirect_to("index.php");
}

if(isset($_POST['submit'])){
	include_once("includes/form_functions.php");
	$errors = array();
	$required_fields = array('comment');
	$errors = array_merge($errors, check_required_fields($required_fields));
	$content = mysql_prep($_POST['comment']);
	if (empty($errors)){
		
		$query = "UPDATE comment SET comment = '{$content}' WHERE id = {$_GET['id']}";
		$result = mysql_query($query);
		
		if (mysql_affected_rows() == 1){
				$message = "The page was successfully updated.";
			} else{
				$message = "The page could not be updated.";
				$message .= "<br />" . mysql_error();
			}
		} else{
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else{
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
}
include("sidebar.php");

print "<div id='wrapper'>";
print "<h1>Add or change your reply</h1>";
if (!empty($message)){print display_errors($errors);}
while (@$comment = mysql_fetch_array($result)){
	if($_SESSION['user_id'] ==	$comment['user_id']){
		print "<div class='composefield'>\n<form action='edit.php?id={$_GET['id']}' method='post'>";
		print "  <textarea tabindex='4' type='text' id='comment-text' name='comment' rows='10' cols='50'>".$comment['comment'] ."</textarea>\n<br /><br /><input type='submit' value='sumbit' name='submit'/>\n</div>";

		print "</form>";
	} else { redirect_to("thread.php");}
}
print "</div>";
include("includes/footer.php");
?>