<?php
/*
session varialbles:
	$_SESSION['user_id']
	$_SESSION['username']
*/
	session_start();

	function logged_in() {
		return isset($_SESSION['user_id']);
	}

	function confirm_logged_in() {
		if (!logged_in()) {

			redirect_to('threads');
		}

	}
?>
