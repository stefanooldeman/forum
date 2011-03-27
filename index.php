<?php
require_once("includes/session.php"); 
require_once("includes/connection.php");
include_once("includes/functions.php");

redirect_to("thread.php");
$listthreads = "SELECT * FROM thread ORDER BY id DESC";
$threads = mysql_query($listthreads, $connection);
confirm_query($listthreads);



include("includes/header.php");
print "<h5>Threads</h5>\n";
while ($thread = mysql_fetch_array($threads)){
	print "<a href='thread.php?id={$thread['id']}'>". $thread['title'] ."</a>\n";
		
	$listusers = "SELECT * FROM users WHERE id = {$thread['user_id']}";
	$users = mysql_query($listusers, $connection);
	confirm_query($listusers);
		
	print " <a href='user.php?profile=";
	while ($user = mysql_fetch_array($users)){	print $user['username']; } 
	print "'>". $thread['post_by'] ."</a><br>\n";
}

include("includes/footer.php");
?>