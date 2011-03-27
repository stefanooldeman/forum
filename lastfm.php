<?php
$lastfmuser = "earorgasms";
$doc = new DOMDocument();
$doc->load("C:/wamp/www/$lastfmuser.xml");

$played = $doc->getElementsByTagName( "recenttracks" );
$i=0;
foreach( $played as $info ){
		$artists = $info->getElementsByTagName( "artist" );
		$artist = $artists->item($i)->nodeValue;
		$artist = $artists->item($i)->nodeValue;
		
		$albums = $info->getElementsByTagName( "album" );
		$album = $albums->item($i)->nodeValue;
		$album = $albums->item($i)->nodeValue;
		
		$titles = $info->getElementsByTagName( "name" );
		$title = $titles->item($i)->nodeValue;
		$title = $titles->item($i)->nodeValue;
	$i++;
}
echo "$artist - $title - $album\n";
?>
