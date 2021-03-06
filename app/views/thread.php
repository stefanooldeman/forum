<?php
$categoryName = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRIPPED);
$threadId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$pageNr = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

$pageNavUrl = BASE_URL . 'thread' . (isset($threadId) ? 's' : '') . '/page/%page';

$categories = array(1 => 'discussions', 2 => 'projects', 3 => 'advice', 4 => 'meaningless');
if(isset($categoryName) && in_array($categoryName, $categories)) {
	$list = array_flip($categories);
	$categoryId = $list[$categoryName];
}


$query = "SELECT DATE_FORMAT(`startdate`, '%b %d %y @ %h:%i%p') AS date FROM thread ORDER BY id DESC ";

if(!$date_set2 = mysql_query($query, $connection)){
	trigger_error('query error: ' . $query . "\n" . mysql_error() . ' EOM.', E_USER_ERROR);
}

$dates2 = mysql_fetch_array($date_set2);
$startdate =  $dates2['date'];


if(isset($threadId)) {
	//show the thread page with all the reply's
	/* Set How many results to display per page */
	$pp = "5";

	$query = 'SELECT * FROM comment WHERE thread_id = ' . $threadId;
	$pages_set = mysql_query($query);
	$total = mysql_num_rows($pages_set);
	$numofpages = $total / $pp;

	if (!isset($pageNr)) {
		$page = 1;
	} else {
		$page = $pageNr;
	}
	$limitvalue = $page * $pp - ($pp);

	$comments  = '
		SELECT * FROM comment
		WHERE thread_id = ' . $threadId . '
		ORDER BY id ASC LIMIT ' . $limitvalue . ', ' . $pp;

	$comment_set = mysql_query($comments, $connection);
	confirm_query($comments);

	$numrow = mysql_num_rows($comment_set);

} else {

	/* Set How many results to display per page */
	$pp = "5";

	$query = "SELECT * FROM thread  ORDER BY id DESC";
	$pages_set = mysql_query($query);
	$total = mysql_num_rows($pages_set);
	$numofpages = $total / $pp;
	if(!isset($pageNr)) {
		$page = 1;
	} else {
		$page = $pageNr;
	}
	$limitvalue = $page * $pp - ($pp);

	if(isset($categoryId)) {
		/* Set How many results to display per page */
		$pp = "5";

		$query = "SELECT * FROM thread WHERE category = " . $categoryId . "  ORDER BY id DESC";
		$pages_set = mysql_query($query);
		$total = mysql_num_rows($pages_set);
		$numofpages = $total / $pp;
		if(!isset($pageNr)) {
			$page = 1;
		} else {
			$page = $pageNr;
		}

		$listthreads = "SELECT * FROM thread WHERE category = " . $categoryId . " ORDER BY id DESC LIMIT $limitvalue, $pp";
		$threads = mysql_query($listthreads, $connection);
		confirm_query($listthreads);
	} else{
		$listthreads = "SELECT * FROM thread ORDER BY id DESC LIMIT $limitvalue, $pp";
		$threads = mysql_query($listthreads, $connection);
		confirm_query($listthreads);
	}
}

$info  = 'SELECT * FROM thread ';
if(isset($threadId)){
	$info .= 'WHERE id = ' . $threadId . ' ';
}
$info .= 'LIMIT 1';
$info_set = mysql_query($info, $connection);
confirm_query($info);


// ---------------------------------------------------------------------------------------------------------validating submitted forms
if(isset($_POST['submit'])){
	if(!$authClass->hasIdentity()) {
		redirect_to('index');
	}

	$errors = array();
	$required_fields = array('comment');

	$errors = array_merge($errors, check_required_fields($required_fields));
	$fields_with_lengths = array( );

	$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));


	$comment = mysql_prep(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING));
	$userId = $authClass->getValue('id');
	$username = $authClass->getValue('username');

	if(empty($errors)) {
		$query = 'INSERT INTO comment
			(thread_id, post_by, user_id, comment )
			VALUES
			("' . $threadId . '","' . $username . '",  "' . $userId . '", "' . $comment . '") ';

		if(mysql_query($query)) {
			redirect_to('thread/' . $threadId);
		} else {
			print "<li class='error'>Bericht is niet opgeslagen: <b>".mysql_error()."</b></i>\n<br />\n<li>Query :".$query ."</li>";
		}
	} else {
		if(count($errors) == 1) {
			$message = "There was 1 error in the form.";
		} elseif((count($errors) > 1)){
			$message = "There were " . count($errors) . " errors in the form.";
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------------------output all posts

if(isset($threadId)){
	print "<div id='wrapper'>";
	//head info

	while ($heading = mysql_fetch_array($info_set)) {
		print "<h2 class='pagetitle'>". $heading['title'] ."</h2> There are {$numrow} reply's";
	}
	print "<br />";
	per_page($pageNavUrl, '7');

	print "<div class='orangeline'><span></span></div>";
	while ($comment = mysql_fetch_array($comment_set)) {
		print "<div class='postuser'>
		<div class='postusername'><a href='" . BASE_URL . "user/profile/". $comment['user_id'] ."'>". $comment['post_by']  ."</a></div>
		<div class='postusertime tenpx'>12 minutes ago</div>
		<div class='postuseravatar'></div>
		<div class='postuserbuddy'><a href='" . BASE_URL . "user/friends/add/" . $comment['user_id'] . "' class='tenpx greya'>Add Mayte</a></div>
		<div class='postusermessage'><a href='" . BASE_URL . "inbox/new' class='tenpx greya'>Send Message</a></div>
		</div>
		<div class='postblock'>
		<div class='post'>". nl2br($comment['comment']) ."</div>
		</div>
		<div class='postoptions'>";
		if($authClass->hasIdentity() && ($comment['user_id'] == $authClass->getValue('id'))) {
			print "<a href='edit.php?id={$comment['id']}'>edit</a>";
		}
		print "</div>
		<div class='postseperator'><span></span></div>
		<div class='orangeline'><span></span></div>";
	}


// ------------------------------------------------------------------------------------------------------------------the submit form
	print "<div id='postbox'>";

if (!empty($message)){ 	print "<p class='error'>" . $message . "</p>";}
if (!empty($errors)){ display_errors($errors);}
	print "<form method='post'>";

	print "   <label for='comment-text'>comment:</label><br />";
	print "  <textarea tabindex='4' type='text' id='comment-text' name='comment' rows='10' cols='50'></textarea><br /><br />";

	print "	<input type='submit' value='submit' name='submit'/>";
	print "</form>";
	print "</div></div>";
}
//-----------------------------------------------------------------------------------output all threads
 else {
print "<div id='wrapper'>";

print 'Pages: '.round($numofpages).'<br>';
if(isset($categoryId)) {
	per_page($pageNavUrl, "7");
} else {
	per_page($pageNavUrl, "7");
}

print "<div id='threadheaders'>
	<div class='threadlistelement threadlisttitle'>Thread Title &amp; Category</div>
	<div class='threadlistelement threadlistposted'><a class='greya' href='forum/thread.php/?orderby=dateposted&amp;orderdir=asc'>Started By</a></div>
	<div class='threadlistelement threadlistposted'><a class='greya' href='forum/thread.php/?orderby=lastreply&amp;orderdir=asc'>Last Post</a></div>
	<div class='threadlistelement threadlistposts'><a class='greya' href='forum/thread.php/?orderby=numposts&amp;orderdir=asc'>Posts</a></div>
	<div class='clear'></div>
</div>
<div id='threadlisting'>";


while($thread = mysql_fetch_array($threads)) {
	$id = $thread['id'];

	$lastcomment  ="SELECT * FROM comment WHERE thread_id = $id ORDER BY id DESC LIMIT 1";
	$users_set = mysql_query($lastcomment, $connection);
	confirm_query($lastcomment);

	$query = "SELECT DATE_FORMAT(lastdate, '%b %d %y @ %h:%i%p') AS date FROM comment WHERE thread_id = $id ORDER BY id DESC ";
	$date_set = mysql_query($query, $connection);
	confirm_query($date_set);

	$dates = mysql_fetch_array($date_set);
	$lastdate = $dates['date'];


	$query = "SELECT * FROM comment WHERE thread_id = $id ";
	$countpost = mysql_query($query, $connection);
	confirm_query($countpost);
	$numposts = mysql_num_rows($countpost);

	if($thread['category'] == 1){ $threadcategory = "Discussions";}
	if($thread['category'] == 2){ $threadcategory = "Projects";}
	if($thread['category'] == 3){ $threadcategory = "Advice";}
	if($thread['category'] == 4){ $threadcategory = "Meaningless";}


	print "<div class='thread row1'>
				<div class='threadlistelement threadlisttitle'>
					<b><a href='" . BASE_URL . "thread/". $thread['id'] ."/" . urlencode(stripslashes($thread['title'])) . "'>" . stripslashes($thread['title']) ."</a></b>
					<a href='/thread/134530/Names-to-Guns#end' class='darkgreya tenpx'>#</a>
					<div class='darkgreya ninepx'>". $threadcategory ."</div>
				</div>
				<div class='threadlistelement threadlistposted'>
					<b><a href='profile.php?id=". $thread['user_id'] ."'>". $thread['post_by'] ."</a></b>
					<div class='darkgreya ninepx'>". $startdate ."</div>

				</div>
				<div class='threadlistelement threadlistposted'>";
				while ($comment = mysql_fetch_array($users_set)){
					print "<b><a href='profile.php?id=". $comment['user_id'] ."'>".$comment['post_by']."</a></b>";
				}
				print "<div class='darkgreya ninepx'>". $lastdate ."</div>
				</div>
				<div class='threadlistelement threadlistposts'>$numposts</div>
								<div class='threadlistelement threadlistfaves' id='fave_134530'>
								<a href='" . BASE_URL . "thread/bookmark/" . $id . "'><img alt='Add Favorite?' src='" . MEDIA_URL . "images/heart_add.png' height='16' width='12' /></a>
								</div>
								<div class='clear'></div>
			</div>

			<div class='orangeline'><span></span></div>";
}
print "<br />";
echo 'Pages: '.round($numofpages).'<br>';

if(isset($categoryId)) {
	per_page($pageNavUrl, "7");
} else {
	per_page($pageNavUrl, "7");
}
print "</div>";
//-----------------------------------------------------------------------------------end
}
