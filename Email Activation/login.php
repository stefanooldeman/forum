<?php
require_once("session.php");
require_once("connection.php");
require_once("functions.php");
logged_in();
?>
<html>
<head>
</head>
<body>
<?php

if(isset($_POST['submit'])){

$username = $_POST['username'];
$password = $_POST['password'];
$hashed_password = sha1($password);

			// Check database to see if username and the hashed password exist there.
			$query = "SELECT id, username ";
			$query .= "FROM users ";
			$query .= "WHERE username = '{$username}' ";
			$query .= "AND password = '{$hashed_password}' ";
			$query .= "LIMIT 1";
			$result_set = mysql_query($query);

			if (mysql_num_rows($result_set) == 1) {
				// username/password authenticated
				// and only 1 match
				$found_user = mysql_fetch_array($result_set);
				$_SESSION['user_id'] = $found_user['id'];
				$_SESSION['username'] = $found_user['username'];
				
				echo "You are now logged in, " . $_SESSION['username'] . "<br />";
				echo "Redirecting to the comment page... please wait.";
				echo "<meta http-equiv=\"refresh\" content=\"4;url=index.php\">";
			} else {
				// username/password combo was not found in the database
				echo "Username/password combination incorrect.<br />
					Please make sure your caps lock key is off and try again.";
			}
		} else {
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
mysql_close($connection);

if(!isset($_SESSION['user_id'])){?>
<form action="<?php $PHP_SELF ?>" method="post">
<table>
<tr><td>Login</td><td></td></tr>
<tr><td>Username:</td><td><input type="text" name="username"></td></tr>
<tr><td>Password:</td><td><input type="password" name="password"></td></tr>
<tr><td></td><td><input type="submit" name="submit" value="Login"></td></tr>
</table>
</form>
</body>
</html>
<?php } ?>