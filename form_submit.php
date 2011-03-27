<?php
include('includes/header.php');
include('includes/lang_nl.php');

$fields = array('name' => 'text', 'location' => 'text');
print "<h3>gesubmit!</h3>";
	
print "<ul>";
foreach($fields as $fieldname => $fieldtype){
	if(($_POST[$fieldname]) != NULL){
		print "<li>{$lang_field[$fieldname]} = {$_POST[$fieldname]}</li>";
	}else{
		print "<li>{$lang_field[$fieldname]} = NULL!</li>";
	}
}
print "</ul>";
?>