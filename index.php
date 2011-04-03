<?php
if(true) {
	include 'includes/config_mirror.php';
} else {
	include 'includes/config.php';
}

require "includes/connection.php";
include "includes/functions.php";
include "includes/form_functions.php";
require "includes/session.php";

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

$file = 'pages/' . $pageName . '.php';
if(file_exists($file)) {

	include_once("includes/header.php");
	include("sidebar.php");
	include $file;
	include("includes/footer.php");
} else {
	header('Status: 404 Not Found');
	trigger_error('The requested page is not found: ' . $file, E_USER_WARNING);
}
