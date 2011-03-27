<?php
require_once("includes/session.php"); 
require_once("includes/connection.php");
include_once("includes/functions.php");
confirm_logged_in(); 
include("sidebar.php");
print "<div id='wrapper'>";
echo 'your content here';
print "</div>";

include("includes/footer.php");
?>