<?php
require_once("includes/session.php"); 
require_once("includes/connection.php");
include_once("includes/functions.php");

if (!logged_in()) {
	redirect_to('thread');
}

if(isset($_GET['id'])){
	$query = "SELECT * FROM users WHERE id= {$_GET['id']} LIMIT 1";
	$profile_set = mysql_query($query, $connection);
	confirm_query($query);
}
else{
	redirect_to('threads');
}

include("sidebar.php");
print "<div id='wrapper'>";
while ($user = mysql_fetch_array($profile_set)){
$username = $user['username'];
$story = $user['story'];
$picture = $user['picture'];
$user_id = $user['id'];
}
$query = "SELECT * FROM fav_thread WHERE user_id= {$_GET['id']} ORDER BY date DESC";
$favorites_set = mysql_query($query, $connection);
confirm_query($query);
$countfavs = mysql_num_rows($favorites_set);

$query = "SELECT * FROM mayties WHERE maytie_id= {$_GET['id']} ORDER BY date DESC";

$maytie_info = mysql_query($query, $connection);
confirm_query($query);
$count = mysql_num_rows($maytie_info);

if($_GET['id'] == $_SESSION['user_id']){
$query = "SELECT * FROM mayties WHERE maytie_id= {$_GET['id']} ORDER BY date DESC";
$mayties_set = mysql_query($query, $connection);
confirm_query($query);
}
print "<div id='culmmain'><h1>". $username ." is cool</h1>";
if($_SESSION['user_id'] !== $user_id ) {
	print "<a href='addmaytie.php?action=add&id=". $user_id ."'>add as maytie</a>";
}
print "<div id='proposts'>
    <h4>bookmarks:</h4>";
	while($fav = mysql_fetch_array($favorites_set)){
		print "<a href='thread.php?id=". $fav['favthread'] ."'>". $fav['favtitle'] . "</a><br />";
	}
	print "<h4>mayties:</h4>";
if($_GET['id'] == $_SESSION['user_id']){
	while($maytie = mysql_fetch_array($mayties_set)){
		if ($maytie['confirm'] == 0){
			if($maytie['maytie_id'] == $_SESSION['user_id']){
				print "<a href='profile.php?id=". $maytie['user_id'] ."'>". $maytie['username'] . "</a> did a request!";
				print "<br /> accept as a friend?<br /><a href='addmaytie.php?action=accept&id=". $_SESSION['user_id']."' class='greya'>yeah!</a><br />";
			} else{
				print "you did a request<br />waiting on confirm by:<a href='profile.php?id=". $maytie['maytie_id'] ."'>".$maytie['maytiesname']."</a><br />";
			}
			print "<br />";
		} else {
			print "no requests";
		}
	}
}
	while($maytie = mysql_fetch_array($maytie_info)){
		if ($maytie['confirm'] == 1){
				print "<a href='profile.php?id=". $maytie['user_id'] ."'>". $maytie['username'] . "</a>";
		} else {
			print "you got no friends";
		}
	}
	print " </div>
</div>

<div id='culmright'>
	<div id='proimg'>\n";
	if(empty($picture)){
	 print "<div class='nopic'><a href='imgupload.php' class='greya'>upload picture</a></div>";
	} else{
		print "<img src='". $picture ."' alt='Hey im ". $username ."' />\n";
	}
	print "</div>\n<div id='prostory'>
	<h1>I'm saying:</h1>
    <p>". $story ."</p>
    </div>    
</div>";
print "</div>";
include("includes/footer.php");
?>