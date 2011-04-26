<?php
if(!$authClass->hasIdentity()) {
	redirect_to('index');
}
$user_id = $authClass->getValue('id');
$query = "SELECT * FROM inbox WHERE user_id = $user_id ORDER BY date DESC";
$inbox_set = mysql_query($query, $connection);
confirm_query($inbox_set);

$query = "SELECT * FROM inbox WHERE readed = 0 AND user_id = $user_id";
$read_set = mysql_query($query, $connection);
confirm_query($read_set);
$numreaded = mysql_num_rows($read_set);

$query = "SELECT user_id, DATE_FORMAT(date, '%b %d %y @ %h:%i%p') AS date FROM inbox ";
$messageId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(isset($messageId)) {
	$query .= "WHERE id =  '{$messageId}' LIMIT 1";
} else {
	$query .= "WHERE user_id =  $user_id ORDER BY date DESC";
}

$date_set = mysql_query($query, $connection);
confirm_query($query);
$post = mysql_fetch_array($date_set);
	$date = $post['date'];

print "<div id='Lwrapper'>";

if($numreaded == 1){print "<h1>Whoo one new message = )</h1>";}
elseif($numreaded > 1){print "<h1>Whoo  new messages = D</h1>";}
elseif($numreaded > 10){print "<h1>Hey go clean up your inbox Mr.populair";}
else{print "<h1>Ahw nothing new = (</h1>";}
print "<div id='inboxoptions'>
<ul>
<li style='margin-right:2px;'><img src='" . MEDIA_URL . "images/email.png' /></li><li><a href='" . BASE_URL . "inbox' class='bluea'>inbox</a></li>
<li style='margin-right:2px;'><img src='" . MEDIA_URL . "images/comment_edit.png' /></li><li><a href='" . BASE_URL . "inbox/new' class='bluea'>Create pm</a></li>
<li style='margin-right:2px;'><img src='" . MEDIA_URL . "images/bin.png' /></li><li><a href='' class='bluea'>Trash Can</a></li>";
if(isset($messageId)){
	print "<li style='margin-right:2px;'><img src='" . MEDIA_URL . "images/email_delete.png' /></li><li><a href='" . BASE_URL . "inbox/delete/". $messageId ."' class='bluea'>Delete</a></li>";
}
print "</ul>
</div>";
if(isset($messageId)){
	$query = "SELECT * FROM inbox WHERE id = '{$messageId}' LIMIT 1";
	$info_set = mysql_query($query, $connection);
	confirm_query($inbox_set);

	while ($inbox = mysql_fetch_array($info_set)){

		if($inbox['readed'] == 0){
			$query = "UPDATE inbox SET readed = 1, date = '{$inbox['date']}' WHERE id = {$messageId} ";
			$result = mysql_query($query, $connection);
			if (mysql_affected_rows() == 0) {print "error, there broke something in the system";}
		}

		$post_by = $inbox['post_by'];
		$query = "SELECT * FROM users WHERE id = $post_by LIMIT 1";
		$user_set = mysql_query($query, $connection);
		confirm_query($query);

		while ($usernames = mysql_fetch_array($user_set)){
			$post_by = $usernames['username'];
			$post_by_id = $usernames['id'];
		}


	print "<div id='inboxheader'>
		<div class='inboxinfo'>
			<span class='bluea'>Subject:</span> <span class='white'>". $inbox['subject'] ."</span>
			<span class='sepie elevenpx'>|</span>
	        <span class='bluea'>From:</span> <span class='white'>". $post_by ."</span>
			<span class='sepie elevenpx'>|</span>
	        <span class='bluea'>Recieved:</span> <span class='white'>";
			print $date;
			print "</span>
		</div>
	</div>
	<div class='inboxmes'>
		<p>". $inbox['message'] ."</p>
	</div>";
	}
} else{
	print "<div id='inboxheader'>
			<div class='inboxsubject'><a href='#' class='bluea'>Subject</a></div>
	        <div class='inboxfrom'><a href='#' class='bluea'>From</a></div>
	        <div class='inboxrecieved'><a href='#' class='bluea'>Recieved</a></div>
	    </div>";
	while ($inbox = mysql_fetch_array($inbox_set)){

		$post_id = $inbox['post_by'];
		$query = "SELECT * FROM users WHERE id = $post_id LIMIT 1";
		$user_set = mysql_query($query, $connection);
		confirm_query($query);
		$usernames = mysql_fetch_array($user_set);
	$query = "SELECT DATE_FORMAT(date, '%b %d %y @ %h:%i%p') AS date FROM inbox ";
	$query .= "WHERE id = '{$inbox['id']}' LIMIT 1";
		$date_set = mysql_query($query, $connection);
		confirm_query($query);


	print "<div class='inbox "; if($inbox['readed'] == 1){ print "pmrow2";} else {print "pmrow1";} print "'>";
	print "
	<div class='inboxsubject'><a href='" . BASE_URL . "inbox/" . $inbox['id'] . "' class='blacka'>" . stripslashes($inbox['subject']) . "</a></div>
	<div class='inboxfrom'><a href='" . BASE_URL . "user/profile/" . $usernames['id'] . "' class='blacka'>". stripslashes($usernames['username']) ."</a></div>
	<div class='inboxrecieved'><span class='blacka'>";
	while ($post = mysql_fetch_array($date_set)) {
		print $post['date'];
	}
	print "</span></div>
	</div>";
	}
}
print "</div>";
