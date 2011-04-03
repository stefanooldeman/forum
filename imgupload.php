<?php
require 'includes/config_mirror.php';
require_once("includes/session.php"); 
require_once("includes/connection.php");
include_once("includes/functions.php");
confirm_logged_in(); 

if(isset($_POST['submit'])){
	
	$req_fields = array('file');
	$allowedextensions = array('jpeg', 'jpg', 'JPEG', 'JPG', 'png');
	$dest_dir = "uploads/";
	$file = $_FILES['file'];
	$user_id = $_SESSION['user_id'];
	
	//user gegevens ophalen
	$query = "SELECT * FROM users WHERE id = {$_SESSION['user_id']} LIMIT 1";
	$info = mysql_query($query, $connection);
	confirm_query($query);
	
	while($user = mysql_fetch_array($info)){
	$username = $user['username'];
	}

//-----------------------------------------------	
	$errors = array();
	
	foreach($req_fields as $field){
		if(empty($file['name'])){
			$errors[0] = $field;
		}
	}
	
	$extension = pathinfo($file['name']);
	$extension = strtolower($extension["extension"]);

	foreach($allowedextensions as $ext){
		if(strcasecmp($ext, $extension) == 0){
			$extension_check = true;
		} 
	}

	if(@!$extension_check){
		$errors[0] = 'extension';
	}
	
	if(!empty($errors)){
		print "<h2>Onbekende extensie:</h2><ul>";
		foreach($errors as $error){
			print "<li>you bestand is een: <em>". $file['type'] ."</em></li>";
			print "<li>you can only upload images</li>";
		}
		
		print "</ul>";
		
	}else{
		$filename = $username . $user_id . ".jpg";
		$srcfile = $file['tmp_name'];
		$url = $dest_dir . $filename;
		//exit( print_r(pathinfo($dest_dir . $filename)));
				
		if(move_uploaded_file ($srcfile, $dest_dir . $filename)){
			//clear the results of the file check in cache
			$url = $dest_dir . $filename;
			
			$query  = "UPDATE users SET picture = '{$url}' WHERE id = {$user_id}";
			
			print "<br /><br />". $query;
			if(mysql_query($query)){
				print "query succes!";
			}
			else{
				print "query fail!";
			}
			clearstatcache();
			print "<br />";
			print "yay!";
		}else{
			print "'move_uploaded_file #FAIL '. copy(" . $srcfile . " , " . $dest_dir . $filename . ")" ;
			print $file['tmp_name'];
		}
		
		
		
	}
}

print "<h1>Upload een Afbeelding</h1>";

print "<form method='post' enctype='multipart/form-data'>
<input type='file' name='file'>\n
<input type='submit' name='submit'>\n
<form>";