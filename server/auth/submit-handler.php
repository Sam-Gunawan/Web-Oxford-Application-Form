<?php 
	require_once("/server/include/config.php");
	session_start();

	if (!isset($_SESSION['role'])) {
		header("Location: login.php");
		exit(); // Make sure to call exit after header to stop further execution of the script
	}
?>