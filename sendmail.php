<?php
include 'includes/config_mirror.php';
require_once("includes/session.php"); 
require_once("includes/connection.php");
include_once("includes/functions.php");

if(isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
//	redirect_to('threads');
}

//----------------------------------------user info
$name = "stefano";
$from = $_SESSION['username'];
$inbox = BASE_URL . 'inbox.php';
$company = "audicious.com";
$mailto = 'stefano.oldeman@gmail.com';
/*
$mktime = date("%X");
$date = date("%a ".."", $mktime); 
*/
//------------------------------------------mail info

$subject = "PM from $from";
$message = "hey dude";
$headers = "From: Audicious.com <no-reply@". $_SERVER['SERVER_NAME'] .">\r\n";
include("sidebar.php");

print "<div id='wrapper'>";
if($action == "pm") {
	$mail  = "hi $name\n\n";
	$mail .= "$from wrote a pm to you.\n\nView it in your inbox at\n $inbox\n\n";
	$mail .= "Greetings the email monkey @ $company";
	
	exit(var_dump($mailto, $subject, $mail, $headers));
	
	if(mail($mailto, $subject, $mail, $headers)) {
		print "A mail has been send to your pal!";
	} else {
		print "notify for pm is not send.";
	}
}
if($action == "invite") {
	if(isset($_POST['submit'])) {
		$fieldnames = array('email', 'comment');
		foreach($fieldnames as $postfield) {
			$$postfield = mysql_prep($_POST[$postfield]);
		}

		include_once("includes/form_functions.php");
		$errors = array();
		$required_fields = array('email', 'comment');

		$errors = array_merge($errors, check_required_fields($required_fields));

		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

		if(empty($errors)) {
			$query  = "INSERT INTO invites ( ";
			$query .= "title, user_id, post_by, category ,status ";
			$query .= " )VALUES( ";
			$query .= "'{$title}', '{$user_id}', '{$post_by}', '{$category}', '{$status}' )";


			if(mysql_query($query)) {
				$message = "Great, your invite has been send.";
			} else {
				$message = "Whoops something went wrong!<br />";
				print $sub_query . mysql_error();
			}

		} else {
			if (count($errors) == 1) {
				$message = "Sorry you forgot a field.";
			} elseif((count($errors) > 1)) {
				$message = "Uuhhm.. you forgot " . count($errors) . " fields";
			}
		}
	}
	$mail  = "hi $name\n\n";
	$mail .= "$from invited you to audicious.com\n\n";
	$mail .= "chec\n\n";
	$mail .= "Greetings the email monkey @ $company";

	if(mail($mailto, $subject, $mail, $headers)) {
		print "A mail has been send to your pal!";
	} else {
		print "notify for pm is not send.";
	}
}
print "</div>";