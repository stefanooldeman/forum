<?php
$id = filter_input_(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(isset($id)) {
	$query = 'DELETE FROM inbox WHERE id = ' . $id . ' LIMIT 1';
	$result_set = mysql_query($query);
	confirm_query($result_set);
	if(mysql_affected_rows() == 1) {
		redirect_to('inbox');
	}
}
trigger_error('message with id ' . $id . ' not deleted', E_USER_NOTICE);
redirect_to('threads');
