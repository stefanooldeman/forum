<?php
function mysql_prep ($value){
	$magic_qoutes_active = get_magic_quotes_gpc();
	$new_enough_php = function_exists("mysql_real_escape_string");

	if($new_enough_php){
		if($new_enough_php){ $value = stripslashes($value);}
		$value = mysql_real_escape_string($value);
	} else{
		if(!$magic_qoutes_active){ $value = addslashes($value);}
	}
return $value;
}

function redirect_to($location = NULL) {
	if ($location != NULL){
		header("Location: {$location}");
		exit;
	}
}
	
function confirm_query($result_set){
	if (!$result_set){
	die("Database selection failed: ". mysql_error());
	}
}
function ezDate($d) {
	$ts = time() - $d;
   
	if($ts>31536000) $val = round($ts/31536000,0).' year';
	else if($ts>2419200) $val = round($ts/2419200,0).' month';
	else if($ts>604800) $val = round($ts/604800,0).' week';
	else if($ts>86400) $val = round($ts/86400,0).' day';
	else if($ts>3600) $val = round($ts/3600,0).' hour';
	else if($ts>60) $val = round($ts/60,0).' minute';
	else $val = $ts.' second';
   
	if($val>1) $val .= 's';
	return $val;
}
function per_page($link, $offset){
global $numofpages, $page;
$numofpages = round($numofpages);

$pagesstart = round($page-$offset);
$pagesend = round($page+$offset);
if(isset($_GET['category']))

if ($page != "1" && round($numofpages) != "0") {
  if(isset($_GET['category']))
	  print str_replace("%page", round($page-1), '<a href="?category='. $_GET['category'] . $link.'"><font face="Trebuchet MS">«</font></a>&nbsp; ');
  else
	  print str_replace("%page", round($page-1), '<a href="'.$link.'"><font face="Trebuchet MS">«</font></a>&nbsp; ');
}

for($i = 1; $i <= $numofpages; $i++) {
if ($pagesstart <= $i && $pagesend >= $i) {
if ($i == $page) {
echo "<b>[$i]</b>&nbsp;";
} else {
	if(isset($_GET['category']))
     print str_replace("%page", "$i", '<a href="?category='. $_GET['category'] . $link.'">'.$i.'</a>&nbsp; ');
   else
     print str_replace("%page", "$i", '<a href="'.$link.'">'.$i.'</a>&nbsp; ');
}
}
}
if (round($numofpages) == "0") {
print "[$i]";
}

if ($page != round($numofpages) && round($numofpages) != "0") {
if(isset($_GET['category']))
	print str_replace("%page", round($page+1), '<a href="?category='. $_GET['category'] . $link.'"><font face="Trebuchet MS">»</font></a>');
else
	print str_replace("%page", round($page+1), '<a href="'.$link.'"><font face="Trebuchet MS">»</font></a>');
}
}
?>