<?php
include ("connection.php");

function per_page($link, $offset) {
	global $numofpages, $page;
	$numofpages =round($numofpages);
	$pagesstart = round($page-$offset);
	$pagesend = round($page+$offset);
	$pagesendpace = round($numofpages-7);
	
	if($page >= "7" || $page <= $pagesendpace){
	$jumppage = 4;
	}
//round($numofpages) != "0"
	
	if($page >= "7"){
		print str_replace("%page", round($page-$jumppage),'<a href="?page=1">1</a> <a href="'.$link.'"><font face="Trebuchet MS">..</font></a>&nbsp; ');
	}
	
	for($i = 1; $i <= $numofpages; $i++){
		if ($pagesstart <= $i && $pagesend >= $i){
			if ($i == $page){
				print "<strong>$i</strong>&nbsp;";
			} else{
				print str_replace("%page", "$i", '<a href="'.$link.'">'.$i.'</a>&nbsp; ');
			}
		}
	}
	if(round($numofpages) == "0"){
		print "$i";
	}
	//$page != round($numofpages) && round($numofpages) != "0"
	if($page < $pagesendpace){
		print str_replace("%page", round($page+$jumppage), '<a href="'.$link.'"><font face="Trebuchet MS">..</font></a> <a href="?page='.$numofpages.'">'. $numofpages . '</a>');
	} else (){}
}


/* Set How many results to display per page */
$pp = "3";

$total = mysql_result(mysql_query("SELECT COUNT(id) FROM thread"),0);
$numofpages = $total / $pp;
if (!isset($_GET['page'])) { $page = 1; } else { $page = $_GET['page']; }
$limitvalue = $page * $pp - ($pp);

/* Display the pages down the bottom */
print "<br />";
print 'Pages: '.round($numofpages).'<br>';
per_page("?page=%page", "5");

print "<hr>";
/* Display the rows */
$query = "SELECT * FROM thread ORDER BY `id` DESC LIMIT $limitvalue, $pp";
$result = mysql_query($query);
while($r=mysql_fetch_array($result)){
	print " TITLE: ". $r['title'] ."=> by: ". $r['post_by'] ." => ID:". $r['id'] ." <br />";
}

?>