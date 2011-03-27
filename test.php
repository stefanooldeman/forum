<?php
require_once("includes/connection.php");

function per_page($link, $offset){
global $numofpages, $page;
$numofpages = round($numofpages);

$pagesstart = round($page-$offset);
$pagesend = round($page+$offset);

if ($page != "1" && round($numofpages) != "0") {
echo str_replace("%page", round($page-1), '<a href="'.$link.'"><font face="Trebuchet MS">«</font></a>&nbsp; ');
}

for($i = 1; $i <= $numofpages; $i++) {
if ($pagesstart <= $i && $pagesend >= $i) {
if ($i == $page) {
echo "<b>[$i]</b>&nbsp;";
}
else {
echo str_replace("%page", "$i", '<a href="'.$link.'">'.$i.'</a>&nbsp; ');
}
}
}
if (round($numofpages) == "0") {
echo "[$i]";
}

if ($page != round($numofpages) && round($numofpages) != "0") {
echo str_replace("%page", round($page+1), '<a href="'.$link.'"><font face="Trebuchet MS">»</font></a>');
}
}


/* Set How many results to display per page */
$pp = "10";


$query = "SELECT * FROM thread  ORDER BY id DESC";
$pages_set = mysql_query($query);
$total = mysql_num_rows($pages_set);
$numofpages = $total / $pp;
if (!isset($_GET['page'])) {
$page = 1;
}
else {
$page = $_GET['page'];
}
$limitvalue = $page * $pp - ($pp);

/* Display the rows */
$query = "SELECT * FROM thread ORDER BY id DESC LIMIT $limitvalue, $pp";
$result = mysql_query($query);
while($r=mysql_fetch_array($result))
{
echo "<span class=box4><a href=?read&f_id=$r[id]>$r[title]</a> - Autor: $r[post_by]</span><br />";
}


/* Display the pages down the bottom */
echo 'Pages: '.round($numofpages).'<br>';
per_page("?page=%page", "7");


?>