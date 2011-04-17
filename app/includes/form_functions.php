<?php
function check_required_fields($required_array) {
	$field_errors = array();
	foreach($required_array as $fieldname) {
		if (!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]))) { 
			$field_errors[] = $fieldname; 
		}
	}
	return $field_errors;
}

function check_max_field_lengths($field_length_array) {
	$field_errors = array();
	foreach($field_length_array as $fieldname => $maxlength ) {
		if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) { $field_errors[] = $fieldname; }
	}
	return $field_errors;
}

function display_errors($error_array) {
	global $message;
	print "<p class=\"error\">";
	print "<strong>". $message ."</strong><br />";
	print "Please review the following fields:<br />";
	foreach($error_array as $error) {
		print " - " . $error . "<br />";
	}
	print "</p>";
}

?>