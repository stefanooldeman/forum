<?php
require_once("includes/session.php");
require_once("includes/connection.php");
include_once("includes/functions.php");

if(!$authClass->hasIdentity()) {
	redirect_to('index');
}

if(isset($_POST['submit'])){
	$fieldnames = array('title', 'status', 'comment');
	foreach($fieldnames as $postfield){
		$$postfield = mysql_prep($_POST[$postfield]);
	}

	include_once("includes/form_functions.php");
	$errors = array();
	$required_fields = array('title', 'comment');

	$errors = array_merge($errors, check_required_fields($required_fields));
	$fields_with_lengths = array('title' => 70);

	$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

	if(empty($errors)){
} else{
	if (count($errors) == 1) {
		$message = "You fool, you forgot a field.";
		} elseif((count($errors) > 1)){
		$message = "You fool, you forgot " . count($errors) . " fields.";
		}
	}
}
include("sidebar.php");

print "<div id='wrapper'>";
if (!empty($message)){print display_errors($errors);}
print "<h1>Some administration</h1>
<form action='".$_SERVER['PHP_SELF']."' method='post'>

<h4>lvl 1./Your user account:</h4>

<div class='composefield'>
  <p>Desired Username:</p>
  <input class='composefield' tabindex='1' type='text' name='username' />
</div>

<div class='composefield'>
  <p>Password:</p>
  <input class='composefield' tabindex='2' type='password' name='password1' />
</div>
<div class='composefield'>
  <p>Password confirm:</p>
  <input class='composefield' tabindex='3' type='password' name='password2' />
</div>
<h4>lvl 2./Profile information:</h4>

<div class='composefield'>
  <p>Blurb:</p>
  <textarea class='composefield' tabindex='4' type='text' name='story' rows='10' cols='50'></textarea>
</div>
  <h4>lvl 3./Profile image:</h4>
  <p>you can upload a profile picture <a href=''>here!</a></p>

<h4>lvl 4./Personal information (not public):</h4>
<div class='composefield'>
  <p>first name:</p>
  <input class='composefield' tabindex='5' type='text' name='firstname' />
</div>
<div class='composefield'>
  <p>last name:</p>
  <input class='composefield' tabindex='6' type='text' name='lastname' />
</div>
<div class='composefield'>
  <p>email:</p>
  <input class='composefield' tabindex='6' type='text' name='email' />
</div>

<div class='btn'>
<p class='grey'>You're cool, Twat!:<br />
Hate posts won't be loved.
and<br /> Love posts will never get someone's hate.. or will they.<br />Be clever and wise, make friends and/or enemies and have fun!!</p>
<input name='agreement' type='checkbox' value='1'> I swear to be nice.<br /><br />
  <input type='submit' value='Lets Go' name='submit'/>
</div>
</form>
</div>";
include("includes/footer.php");
?>
