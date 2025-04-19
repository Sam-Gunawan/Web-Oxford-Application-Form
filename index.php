<?php
	// Include config file
	require_once $_SERVER["DOCUMENT_ROOT"] . '/server/include/config.php';
	session_start();

	// Get the requested URI
	$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	
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
	
	// Check if logged in
	// TODO: check if path exists or not
	if (!isset($_SESSION['role']) && !$is_public) {
		// User is not logged in AND the requested page is not a public path
		Logger::info("Rerouting to " . LOGIN_PAGE_URL);
		header("Location: " . LOGIN_PAGE_URL);
		exit();
	}

	// Route to different pages
	switch ($request_uri) {
		case "/":
		case "/dashboard":
		case "/dashboard.php": {
			Logger::info("Rerouting to " . DASHBOARD_PAGE_URL);
			header("Location: " . DASHBOARD_PAGE_URL);
			exit();	
		}

		case "/form":
		case "/form.php": {
			Logger::info("Rerouting to " . FORM_PAGE_URL);
			header("Location: " . FORM_PAGE_URL);
			exit();
		} 

		case "/login":
		case "/login.php": {
			Logger::info("Rerouting to " . LOGIN_PAGE_URL);
			header("Location: " . LOGIN_PAGE_URL);
			exit();	
		}
		// TODO: add other routes
		
		// Return error code 404 if no route matched
		default: {
			Logger::error("Cannot find " . $request_uri);
			header("HTTP/1.0 404 Not Found");
			echo "Page Not Found";
		} break;
	}
?>