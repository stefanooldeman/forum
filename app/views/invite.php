<?php
if(!$authClass->hasIdentity()) {
	redirect_to('index');
}

//$userId = $authClass->getValue('id');
//$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); //@fixme is this filter constant safe enough?
//$code  = '12345678'; //@fixme i added this value hard coded, because there was no sign of any post variable
//$query = "INSERT INTO invites SET dateinvited= NOW(), userid = {$userId}, email ='{$email}', activation='{$code}', status=2";

if(!empty($message)) {
	echo display_errors($errors);
}

echo "<div id='wrapper'> <h1>invite a pall</h1>
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
