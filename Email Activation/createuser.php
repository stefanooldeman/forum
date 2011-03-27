<?php
require_once("connection.php");
require_once("functions.php");
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
$email = $_POST['email'];
// $actkey = a random processed 40 charcter long string which acts as a registration code to active the account.
$actkey = sha1(time());



$errors = array();
$input = array('username', 'password', 'email');
foreach($input as $value){
	if (!isset($_POST[$value]) || (empty($_POST[$value]) && $_POST[$value] != 0)) { 
		$errors[] = $value; 
	}
}

if ($zoek = mysql_query("SELECT username FROM users WHERE username = '". $_POST["username"]."' LIMIT 1"))
{
	if (mysql_num_rows($zoek) == 1)
	{
		$errors[]= "Username already in use.";
	}
}

	if(empty($errors)){
		$query = "INSERT INTO users (
					username, password, email, regkey) 
					VALUES
					(
					'". $username ."',
					'". $hashed_password ."',
					'". $email ."',
					'". $actkey ."'
					)";
	$result_set = mysql_query($query, $connection);
	//echo $result_set;
	
	if($result_set){
		echo "Registration Successfull, to activate your account, an email has been sent with an activation link. <br />";
		echo "Thank you for registering.";
		// $headers to make HTML usable
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: meskyan@yojimbos-ls.com';
		// the mail that will be sent if it was successfull
		mail($email, 'Activate Your Account', 'Activation. <br /> <a href="http://www.yojimbos-ls.com/expiriments/guestbook/activate.php?actkey='. $actkey .'">Click here to activate</a>]', $headers);

	} else {
		echo "There was an error. " . mysql_error();
	}
	
	} else {
		// Failed, There were errors.
	}
}

mysql_close($connection);
?>


<form action="createuser.php" method="post">
<table>
<tr><td>Create User</td><td></td></tr>
<tr><td>Username:</td><td><input type="text" name="username"></td></tr>
<tr><td>Password:</td><td><input type="password" name="password"></td></tr>
<tr><td>Email:</td><td><input type="text" name="email"></td></tr>
<tr><td></td><td><input type="submit" name="submit" value="Register"></td></tr>
</table>
</form>
</body>
</html>