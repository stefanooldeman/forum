<?php
//ob_start();
require_once("includes/session.php"); 
require_once("includes/connection.php");
include_once("includes/functions.php");
confirm_logged_in(); 

// Send Form
$comments = "SELECT * FROM comment ORDER BY id DESC";
$comment_set = mysql_query($comments, $connection);
confirm_query($comments);
$numrow = mysql_num_rows($comment_set);

if(isset($_POST['submit'])){
	include_once("includes/form_functions.php");
// validating submitted forms
	$errors = array();
	$required_fields = array('comment');
	
	$errors = array_merge($errors, check_required_fields($required_fields));
	$fields_with_lengths = array('subject' => 40);
	
	$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

	//post varialbes
	$comment = mysql_prep($_POST['comment']);
	$post_by = $_SESSION['username'];

	if (empty($errors)){
	$query  = "INSERT INTO comment ( ";
	$query .= "post_by, comment ";
	$query .= ") VALUES ( ";
	$query .= "'{$post_by}', '{$comment}')";

	if(@mysql_query($query)){
		redirect_to("comments.php");
	} else{
		print "<li class='error'>Bericht is niet opgeslagen: <b>".mysql_error()."</b></i>\n<br />\n<li>Query :".$query ."</li>";
	}
	} else{
	if (count($errors) == 1) {
		$message = "There was 1 error in the form.";
		} elseif((count($errors) > 1)){
		$message = "There were " . count($errors) . " errors in the form.";
		}
	}
}


// output the comments

print "Tottaal gevonden : {$numrow}";
print "<div id='Cleft-holder'>";

while ($comment = mysql_fetch_array($comment_set)){
print "<div id='Ccontainer'>\n";
//<a href='delete.php?id={$comment['id']}'>bericht verwijderen</a> |
// <a href='". $comment['url'] ."' target='blank' title='". $comment['url'] ."'>". $comment['name'] ."</a>
print "
<div id='Chead'>
 <div class='Csubject'>
  ". $comment['post_by']  ."
 </div>

 <div class='Cname'>comment</div>
 <hr class='clear'>\n
</div>
";
print "<hr class='clear'>\n";
	print "  <p>". $comment['comment']."</p>";
	print"</div>\n";
}
print "</div>";

//  output the submit form
print "<div id='Cform'>";
	if (!empty($message)){
		print "<p class='error'>" . $message . "</p>";
	} if (!empty($errors)){
		display_errors($errors);
	}
print "<form action='". $_SERVER['PHP_SELF']."' method='post'>";

print "   <label for='comment-text'>comment:</label><br />";
print "  <textarea tabindex='4' type='text' id='comment-text' name='comment' rows='10' cols='50'></textarea><br /><br />";

print "	<input type='submit' value='submit' name='submit'/>";
print "</form>";
print "</div>";

include("includes/footer.php");
?>