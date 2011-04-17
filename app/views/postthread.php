<?php
if(!$authClass->hasIdentity()) {
	redirect_to('index');
}
if(isset($_POST['submit'])) {
	$fieldnames = array('title', 'status', 'comment');
	foreach($fieldnames as $postfield){
		$$postfield = mysql_prep($_POST[$postfield]);
	}
	$user_id = $authClass->getValue('id');
	$post_by = $authClass->getValue('username');
	$category = $_POST['category'];
	$current_time = date("y\\-m\\-d H\\:i\\:s");

	$errors = array();
	$required_fields = array('title', 'comment');

	$errors = array_merge($errors, check_required_fields($required_fields));
	$fields_with_lengths = array('title' => 120);

	$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

	if(empty($errors)){
	$query  = "INSERT INTO thread ( ";
	$query .= "title, user_id, post_by, category ,status, startdate, lastdate ";
	$query .= " )VALUES( ";
	$query .= "'{$title}', '{$user_id}', '{$post_by}', '{$category}', '{$status}', '{$current_time}', '{$current_time}' )";

	if(@mysql_query($query)) {
	$thread_id = mysql_insert_id();
	$comment = $_POST['comment'];


			 $sub_query  ="INSERT INTO comment ( ";
			 $sub_query .="thread_id, user_id, post_by, comment ";
			 $sub_query .=") VALUES ( ";
			 $sub_query .="'{$thread_id}', '{$user_id}', '{$post_by}', '{$comment}' )";

			 if(@mysql_query($sub_query)){
			$message = "Well done you've made it!";
		} else{
			$message = "Whoops something went wrong!<br />"; print $sub_query . mysql_error();
		}
	} else{
		$message = "Whoops something went wrong!<br />"; print $query . mysql_error();
	}
} else{
	if (count($errors) == 1) {
		$message = "You fool, you forgot a field.";
		} elseif((count($errors) > 1)){
		$message = "You fool, you forgot " . count($errors) . " fields.";
		}
	}
}

print "<div id='wrapper'>";
if(!empty($message)) {
	print display_errors($errors);
}
print "<h1>Got something to tell ?</h1>
<form method='post'>
<h4>lvl 1./Choose a catogory:</h4>
<div class='btn'>
<input tabindex='6' type='radio' name='category' value='1' checked /><span class='white'> Discussion</span>
<input tabindex='6' type='radio' name='category' value='2' /><span class='white'> Projects</span>
<input tabindex='6' type='radio' name='category' value='3' /><span class='white'> Advice</span>
<input tabindex='6' type='radio' name='category' value='4' /><span class='white'> Meaningless</span>
</div>
<h4>lvl 2./Give it a title:</h4>
<div class='composefield'>
	<input tabindex='1' type='text' id='comment-subject' name='title' />
</div>

<h4>lvl 3./Write the first post:</h4>
<div class='composefield'>
<textarea tabindex='2' type='text' id='comment-text' name='comment' rows='10' cols='50'></textarea>
</div>
<input tabindex='2' type='hidden' value='1' name='status' />
<h4>lvl 4./The hardest part:</h4>
<div class='btn'>
<p class='tenpx white'>Do you still remember what we told you ? So don't be foolish, No one wants to be hated..</p>
<br />
<input type='submit' value='i promise, post' name='submit'/>
</div>
</form></div>";
