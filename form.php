<?php
include('includes/header.php');
include('includes/lang_nl.php');

$fields = array('name' => 'text', 'location' => 'text');
print "<div id='form'>\n<form action='form_submit.php' method='post' onsubmit='new Ajax.Updater('ajax_submit', 'form_submit.php', {asynchronous:true, parameters:Form.serialize(this)}); return false;' \> \n";

	// velden voor het formulier
	
		
	foreach($fields as $fieldname => $fieldtype){
		print "<fieldset><label for='{$fieldname}'>" . $lang_field[$fieldname] . "</name><input type='{$fieldtype}' name='{$fieldname}'/></fieldset> \n";
	}
	
	// submitknopje
	
	print "<fieldset><input type='submit' name='submit' value='{$lang_submit}'></fieldset> \n";

print "</form> \n</div>\n<div id='ajax_submit'></div>"

?>