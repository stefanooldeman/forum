<?php
ob_start();
if(true) {
	include '../app/config_mirror.php';
} else {
	include '../app/config.php';
}

require "../app/includes/connection.php";
include "../app/includes/functions.php";
include "../app/includes/form_functions.php";

//todo move this to some request handler
require '../autoload.php';
require '../app/bootstrap.php';

//remove empty GET values from the list
foreach($_GET as $key => $value) {
	if($value === '') {
		unset($_GET[$key]);
	}
}

//todo determine get or post
if(isset($_GET['p'])) {
	$pageName = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);
} else {
	$pageName = 'thread';
}

//todo move this to some request handler
$file = '../app/views/' . $pageName . '.php';
if(file_exists($file)) {
	$output = '';
	include_once("../app/layout/header.php");
	include("../app/layout/sidebar.php");
	//the above two go into output id will only be parsed if the main file gets fully executed ;)
	ob_start();
	include $file;
	include("../app/layout/footer.php");
	$output .= ob_get_contents();
	ob_end_clean();
	echo $output;


} else {
	header('Status: 404 Not Found');
	trigger_error('The requested page is not found: ' . $file, E_USER_WARNING);
}
ob_end_flush();
