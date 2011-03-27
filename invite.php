<?php
require_once("includes/session.php"); 
require_once("includes/connection.php");
include_once("includes/functions.php");
confirm_logged_in(); 

$query = "INSERT INTO invites SET dateinvited= NOW(), userid = {$_SESSION['user_id']}, email ='{$_POST['email']}', activation='{$code}', status=2";

include("sidebar.php");
print "<div id='wrapper'>";
if (!empty($message)){print display_errors($errors);}
print "<h1>invite a pall</h1>
<form action='sendmail.php?action=invite' method='post'>
<h4>lvl 1./e-mail adress:</h4>
<div class='composefield'>
<input tabindex='1' type='text' name='email' />
</div>
<h4>lvl 3./leave a message:</h4>
<div class='composefield'>
<textarea tabindex='2' type='text' name='comment' rows='5' cols='10' style='height:120px;'></textarea>
</div>
<input tabindex='2' type='hidden' value='1' name='status' />
<div class='btn'>
<input type='submit' value='add pall' name='submit'/>
</div>
</form></div>";
include("includes/footer.php");
?>