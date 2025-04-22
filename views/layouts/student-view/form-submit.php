<?php 
	define("SERVER", 0);
	require_once(__DIR__ . "/../../../include/config.php");
	require_once(WEBSITE_ROOT . "/include/auth.php");
	session_start();
	redirect_on_unauthorized("student");

	
?>