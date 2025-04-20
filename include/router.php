<?php
	if (!defined("SERVER")) {
		http_response_code(403);
		die();
	}
	
	function redirect(string $url, bool $replace = true, int $error_code = 303): never {
		Logger::info("Redirecting to " . $url);
		header("Location: " . $url, $replace, $error_code);
		exit();
	}

	
?>