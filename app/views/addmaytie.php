<?php
if(!$authClass->hasIdentity()) {
	redirect_to('index');
}

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$maytieId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if(!isset($action) || !in_array(array('add', 'accept')) {
	redirect_to('threads');
}

$user_id = $authClass->getValue('id')
$username = $authClass->getValue('username');

if(intval($maytieId) && $action == "add") {
	$user_id = $maytieId;
	$query = "SELECT * FROM users WHERE id = {$user_id} ";
	$users = mysql_query($query, $connection);
	confirm_query($query);

	while($user = mysql_fetch_array($users)) {
		$maytiesname = $user['username'];
	}

	$maytie_id = $maytieId;
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
	if(@mysql_query($query)) {
			print "ok!";
	} else{
		print "<li class='error'>there happend something really bad! please contact some admin: <b>".mysql_error()."</b></i>\n<br />\n<li>Query :".$query ."</li>";
	}
}
