<?php
confirm_logged_in(); 
if(isset($GET['id'])){}
if(isset($_POST['submit'])){
	$errors = array();
	$required_fields = array('recieved', 'title', 'comment');
	
	$errors = array_merge($errors, check_required_fields($required_fields));
	$fields_with_lengths = array('title' => 40);
	
	$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));
	
	$fieldnames = array('title', 'comment');
	foreach($fieldnames as $postfield){
		$$postfield = mysql_prep($_POST[$postfield]);
	}
	if(empty($errors)){
	$query = "SELECT * FROM users WHERE username = '{$_POST['recieved']}' LIMIT 1";
	$users = mysql_query($query, $connection);
	confirm_query($query);
	$numrow = mysql_num_rows($users);
	
	while ($user = mysql_fetch_array($users)){
			$post_by = $user['id'];
	}
	$recieved = $_SESSION['user_id'];
	$query  = "INSERT INTO inbox ( ";
	$query .= "subject, user_id, post_by, message ";
	$query .= " )VALUES( ";
	$query .= "'{$title}', '{$post_by}', '{$recieved}', '{$comment}' )";
	
	if(@mysql_query($query)){ $message = "<a href='" . BASE_URL . "inbox'>Wel done you made it!</a>"; } else{$message = "wtf happend. contact some admin!"; print $sub_query . mysql_error();}
} else{
	if (count($errors) == 1) {
		$message = "You fool, you forgot a field.";
		} elseif((count($errors) > 1)){
		$message = "You fool, you forgot " . count($errors) . " fields.";
		}
	}
}

$query = "SELECT * FROM mayties WHERE user_id = '{$_SESSION['user_id']}'";
$mayties = mysql_query($query, $connection);
confirm_query($query);

?>
<script type="text/javascript">


	function addToRecipients(uname){
		var frm = document.forms.compose.recieved;
		if(frm.value == '') {frm.value = uname;}
		else{frm.value += ', '+uname;}
	}

</script>
<?php
print "<div id='Lwrapper'>";
if (!empty($message)){print display_errors($errors);}
print "<h1>Send a messages</h1>
<form method='post' name='compose'>
<h4>lvl 1./Send To:</h4>
<div class='composefield'>
	<input type='text' name='recieved' value='' />	<br />";
	while ($maytie = mysql_fetch_array($mayties)){
		$numrow = mysql_num_rows($mayties);
		if($numrow == 1){print "<a href=\"javascript:addToRecipients('". $maytie['maytiesname'] ."')\">+&nbsp;". $maytie['maytiesname'] ."</a>";}
		elseif($numrow == 0){print "";}
		else{print "<a href=\"javascript:addToRecipients('". $maytie['maytiesname'] ."')\">+&nbsp;". $maytie['maytiesname'] ."</a>, ";}
			
	}
print "</div>
<h4>lvl 2./Subject:</h4>
<div class='composefield'>
	<input tabindex='1' type='text' id='comment-subject' name='title' />
</div>
<h4>lvl 3./Message:</h4>
<div class='composefield'>
<textarea tabindex='2' type='text' id='comment-text' name='comment' rows='10' cols='50'></textarea>
</div>
<input tabindex='2' type='hidden' value='1' name='status' />
<h4>lvl 4./The hardest part:</h4>
<div class='btn'>
<br />
<input type='submit' value='Send pm' name='submit'/>
</div>
</form></div>";