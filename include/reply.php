<?php
	if (!defined("SERVER")) {
		http_response_code(403);
		die();
	}
	
	function reply() {
		$action = $_SERVER["QUERY_STRING"];
		Logger::info("Got action " . $action);
		
	}
?>