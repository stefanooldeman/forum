<?php
require_once("includes/connection.php");
include_once("includes/functions.php");

if(isset($_POST['submit'])){
// check velden
include_once("includes/form_functions.php");
	$errors = array();
	$required_fields = array('privite', 'author', 'email', 'subject', 'comment');
	
	$errors = array_merge($errors, check_required_fields($required_fields));
	$fields_with_lengths = array('subject' => 40);
	
	$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

	
	$name = mysql_prep($_POST['author']);
	$email = mysql_prep($_POST['email']);
	$email = mysql_prep($_POST['email']);  
	$privite = mysql_prep($_POST['privite']);
	$url = mysql_prep($_POST['url']);
	$subject = mysql_prep($_POST['subject']);
	$comment = $_POST['comment'];

if (empty($errors)){
	$query = "INSERT INTO comment (
				privite, name, email, url, subject, comment
				) VALUES (
				{$privite}, '{$name}', '{$email}', '{$url}', '{$subject}', '{$comment}' 
	)";
	// stap 4 - SQL uitvoeren
	if($query){
		redirect_to('thread');
	} else{
		print "<li class='error'>Bericht is niet opgeslagen: <b>".mysql_error()."</b></i>
		<li>Query :".$query ."</li>";
		}
	} else{
		if (count($errors) == 1) {
		$message = "There was 1 error in the form.";
		} else{
		$message = "There were " . count($errors) . " errors in the form.";
		}
	}
}
?>