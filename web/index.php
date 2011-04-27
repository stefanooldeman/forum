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

require '../autoload.php';
require '../app/bootstrap.php';

$_POST = (array) filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
$_GET = (array) filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
//remove empty GET values from the list
foreach($_GET as $key => $value) {
	if($value === '') {
		unset($_GET[$key]);
	}
}

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

	$className = ucfirst(strtolower(filter_input(INPUT_GET, 'c', FILTER_UNSAFE_RAW)));
	$className = '\\Audicious\\Ajax\\' . $className;
	$methodName = strtolower(filter_input(INPUT_GET, 'r', FILTER_UNSAFE_RAW)) . 'Action';

	$ajaxHelper = new Audicious\Ajax\Helper();
	$ajaxHelper->execute($className, $methodName);

	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: application/json');

	$response = array(
		'data' => $ajaxHelper->getData(),
		'error' => $ajaxHelper->getError()
	);

	exit(json_encode($response));
}

if(isset($_GET['p'])) {
	$pageName = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);
} else {
	$pageName = 'thread';
}

//these variables are often used in the scripts below
$authClass = $sc->get('auth_class');

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
