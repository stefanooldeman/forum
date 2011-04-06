<?php
confirm_logged_in(); 

if(isset($_GET['action'])){
	$action = $_GET['action'];
} elseif(isset($_GET['id'])){
	$_GET['id'] = $_GET['id'];
} else {
	redirect_to('threads');
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if(intval($_GET['id']) && $action == "add"){
	$user_id = $_GET['id'];
	$query = "SELECT * FROM users WHERE id = {$user_id} ";
	$users = mysql_query($query, $connection);
	confirm_query($query);
	
	while ($user = mysql_fetch_array($users)){
	$maytiesname = $user['username'];}
	$maytie_id = $_GET['id'];
	$user_id = $_SESSION['user_id'];
	$query  = "INSERT INTO mayties (  ";
	$query  .= "maytie_id, maytiesname, user_id, username, date  ";
	$query  .= ") VALUES (  ";
	$query  .= "{$maytie_id}, '{$maytiesname}', '{$user_id}', '{$username}', NOW() )";

	if(@mysql_query($query)){
		redirect_to('user/profile/' . $user_id);
	} else{
		print "<li class='error'>there happend something really bad! please contact some admin: <b>".mysql_error()."</b></i>\n<br />\n<li>Query :".$query ."</li>";
	}
}
if ($action == 'accept'){
$query  = "UPDATE mayties SET confirm = '1' WHERE maytie_id = {$user_id} LIMIT 1";
	if(@mysql_query($query)){
			print "ok!";
	} else{
		print "<li class='error'>there happend something really bad! please contact some admin: <b>".mysql_error()."</b></i>\n<br />\n<li>Query :".$query ."</li>";
	}
}