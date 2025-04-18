<?php
	require_once("/server/include/config.php");
	session_start();
	
	if (!isset($_SESSION['role'])) {
		header("Location: " . SERVER_AUTH_URL . "/login.php");
		// Exit to stop program from running
		exit(); 
	}


?>