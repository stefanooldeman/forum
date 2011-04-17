<?php
if(!$authClass->hasIdentity()) {
	redirect_to('index');
}

$userId = $authClass->getValue('id');
if(isset($_GET['id'])) {

	$threadId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	$query = 'SELECT * FROM thread WHERE id = ' . (integer) $threadId;
	$threads = mysql_query($query);
	confirm_query($query);

	while ($thread = mysql_fetch_array($threads)) {
		$threadTitle = $thread['title'];
	}

	$query  = '
		INSERT INTO fav_thread
		(favthread, favtitle, user_id, date)
		VALUES
		("' . $threadId . '", "' . $threadTitle . '", "' . $userId . '", NOW())';

	if(mysql_query($query)) {
	} else {
		echo '<li class='error'>Bericht is niet opgeslagen</li>';
		error_log(mysql_error() . "\n" . $query);
	}
}
redirect_to('user/profile/' . $userId);



