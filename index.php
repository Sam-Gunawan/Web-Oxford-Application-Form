<?php
	// Include config file
	require_once __DIR__ . '/server/include/config.php';
	session_start();

	// Get the requested URI
	$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

	echo "URI: " . $request_uri . "<br>";
	echo "URI: " . LOGIN_PAGE_URL . "<br>";
	
		// Public paths (accessible without authentication)
	$public_paths = [
		LOGIN_PAGE_URL
	];
	
	$is_public = false;

	foreach ($public_paths as $path) {
		// Simple check: does the request URI start with a public path?
		// More robust routing systems would do exact matches or use regex
		if (strpos($request_uri, $path) === 0) {
			$is_public = true;
			break;
		}
	}
	
	// Check if logged in or
	if (!isset($_SESSION['role']) && !$is_public) {
		// User is not logged in AND the requested page is not a public path
		header("Location: " . LOGIN_PAGE_URL);
		exit();
	}
	
	function ends_with($haystack, $needle) {
		$length = strlen($needle);
		return $length > 0 ? substr($haystack, -$length) === $needle : true;
	}

	// Route to different pages
	if ($request_uri === '/' || $request_uri === '/dashboard' || $request_uri === '/dashboard.php') {
		require __DIR__ . '/pages/dashboard.php';
	} elseif (ends_with($request_uri, "login") || ends_with($request_uri, "login.php")) {
		header("Location: " . LOGIN_PAGE_URL);
		exit();
	}
	// TODO: add other routes
	
	// Return error code 404 if no route matched
	else {
		header("HTTP/1.0 404 Not Found");
		echo "Page Not Found";
	}
	
?>