<?php
	require_once(__DIR__ . "/../../../include/auth.php");
	redirect_on_unauthorized("reviewer");
	require_once(__DIR__ . "/../../../include/reply.php");
	reply();
	
?>