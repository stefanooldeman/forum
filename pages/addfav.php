<?php
confirm_logged_in(); 
if(intval($_GET['id'])) {

	$thread_id = $_GET['id'];

	$query = "SELECT * FROM thread WHERE id = {$thread_id} ";
	$threads = mysql_query($query, $connection);
	confirm_query($query);
	
	while ($thread = mysql_fetch_array($threads)) {
		$thread_title = $thread['title'];
	}
	
	$thread_id = $_GET['id'];
	$user_id = $_SESSION['user_id'];
	$query  = "INSERT INTO fav_thread (  ";
	$query  .= "favthread, favtitle, user_id, date  ";
	$query  .= ") VALUES (  ";
	$query  .= "{$thread_id}, '{$thread_title}', '{$user_id}', NOW() )";
}

if(@mysql_query($query)) {
	redirect_to('user/profile/' . $user_id);
} else {
	print "<li class='error'>Bericht is niet opgeslagen: <b>".mysql_error()."</b></i>\n<br />\n<li>Query :".$query ."</li>";
}