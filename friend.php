<?php
//alles werk!
require_once("includes/session.php"); 
require_once("includes/connection.php");
include_once("includes/functions.php");
$query = "SELECT * FROM mayties WHERE user_id = {$_GET['id']} ORDER BY date DESC";
$maytie_info = mysql_query($query, $connection);
confirm_query($query);
$count = mysql_num_rows($maytie_info);

$request_done = mysql_fetch_object($maytie_info);

if($_GET['id'] == $_SESSION['user_id']){
	$query = "SELECT * FROM mayties WHERE maytie_id= {$_GET['id']} ORDER BY date DESC";
	$mayties_set = mysql_query($query, $connection);
	confirm_query($query);
	$checkrequest = mysql_num_rows($mayties_set);
}

if($_GET['id'] == $_SESSION['user_id']){
	print "<h4>requests:</h4>";
	if($checkrequest > 0){
		print "you have $checkrequest requests by:<br /><br />";
		print "usernames";
	} else {
		print "no requests";
	}

	while($maytie = mysql_fetch_array($mayties_set)){
		$confirm = $maytie['confirm'];
		if ($confirm == 0){
			print "<a href='profile.php?id=". $maytie['user_id'] ."'>". $maytie['username'] . "</a> did a request!";
			print "<br /> accept as a friend?<br /><a href='addmaytie.php?action=accept&id=". $_SESSION['user_id']."' class='greya'>yeah!</a><br />";
			print "<hr />";
		} else{
			print "you did a request<br />waiting on confirm by:<br /><a href='profile.php?id=". $maytie['maytie_id'] ."'>".$maytie['maytiesname']."</a><br />";
			print "<hr />";
		}
	}
}
while($friends = mysql_fetch_array($maytie_info)){$confirm = $friends['confirm'];}
print "<h4>mayties:</h4>";
if($count >= 1 && $confirm == 1){
	print "this user is friends with:<br />";
	//the while won't work! damn!
	while($friends = mysql_fetch_array($maytie_info)){
		print $friends['maytiesname'];
		print "<br />";
	}
} else{
	print "this user is friends with nobody";
	print "<hr />";
}

include("includes/footer.php");
?>