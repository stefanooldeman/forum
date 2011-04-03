<?php
require_once("connection.php");
// Updating Database to activate user via email
$actkey = $_GET['actkey'];

$query = "UPDATE users SET 
			active = 1
			WHERE regkey = '$actkey'";
	$result = mysql_query($query, $connection);
	if (mysql_affected_rows() == 1) {
		// Success
		echo "Your Account has been activated! <br />";
		echo "<a href=\"" . BASE_URL . "threads\">Click here to Log In.</a>";
	} else {
		// Failed
		echo mysql_error();
	}
mysql_close($connection);
?>