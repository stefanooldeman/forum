<?php
require_once("includes/connection.php");
include_once("includes/functions.php");

// Send Form
if(isset($_POST['submit'])){

$query = "SELECT * FROM comment";
$comment_set = mysql_query($query, $connection);
if (!$query){
	die("Database selection failed: ". mysql_error());
}

	//stap 3 - data d.m.v. SQL opvragen
				$privite =$_POST['privite'];
				$name =$_POST['author'];
				$email =$_POST['email'];
				$url =$_POST['url'];
				$subject =$_POST['subject'];
				$comment =$_POST['comment'];
		
		$query = "INSERT INTO comment (
					privite, name, email, url, subject, comment
				) VALUES (
					{$privite}, '{$name}', '{$email}', '{$url}', '{$subject}', '{$comment}' 
				)";
	// stap 4 - SQL uitvoeren

	if(@mysql_query($query)) 
	{
		header("Location:  comments.php");
		exit;
	}
	else
	{
		print "<i class='error'>Bericht is niet opgeslagen: <b>".mysql_error()."</b></i>
		<li>Query :".$query ."</li>";
	}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Guest Book</title>
<script type="text/javascript" language="javascript" src="/js/lib/prototype.js"></script>
<script type="text/javascript" language="javascript" src="/js/src/scriptaculous.js"></script>
<script type="text/javascript" language="javascript" src="/js/jsvalidate_beta04.js"></script>
<style>
.error{
color:#FF0000;
text-decoration:underline;
}
</style>
</head>
<body>
<br />
<?php
print "<br /><br />";
print "<form action='".($_SERVER['PHP_SELF'])."' method='post'>";
print "    <label for='comment-author'>name:</label><br />";
print "   <input tabindex='1' type='text' id='comment-author' name='author' /><br /><br />";
    
print "   <label for='comment-email'>e-mail:</label><br />";
print "   <input tabindex='2' type='text' id='comment-email' name='email' /><br /><br />";
        
print "   <label for='comment-url'>URL:</label><br />";
print "  <input tabindex='2' type='text' id='comment-url' name='url' /><br /><br />";
    
print "   <label for='comment-subject'>subject:</label><br />";
print "   <input tabindex='3' type='text' id='comment-subject' name='subject' /><br /><br />";

print "<p>Privite:\n";
print " <input type='radio'  name='privite' value='0'";  print"/> No\n";
print " <input type='radio'  name='privite' value='1'";  print"/> Yes\n";
print "</p>\n";

print "   <label for='comment-text'>comment:</label><br />";
print "  <textarea tabindex='4' type='text' id='comment-text' name='comment' rows='10' cols='50'></textarea><br /><br />";

print "	<input type='submit' value='submit' name='submit'/>";
print "</form>";
?>
</div>
</body>
</html>
