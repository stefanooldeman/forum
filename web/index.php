<?php
if(true) {
	include '../app/config_mirror.php';
} else {
	include '../app/config.php';
}

require "../app/includes/connection.php";
include "../app/includes/functions.php";
include "../app/includes/form_functions.php";
require "../app/includes/session.php";

//todo move this to some request handler

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

$file = '../app/views/' . $pageName . '.php';
if(file_exists($file)) {

	include_once("../app/layout/header.php");
	include("../app/layout/sidebar.php");
	include $file;
	include("../app/layout/footer.php");
} else {
	header('Status: 404 Not Found');
	trigger_error('The requested page is not found: ' . $file, E_USER_WARNING);
}
