<?php
	// Include config file
	require_once $_SERVER["DOCUMENT_ROOT"] . '/backend/include/config.php';
	session_start();

	// Get the requested URI
	$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	
	// Public paths (accessible without authentication)
	$public_paths = [
		"/login" => 1,
		"/views/login" => 2,
		"/views/layouts/login" => 3,
		"/views/layouts/auth/login" => 4,
		LOGIN_PAGE_URL => 5,
		"/signup" => 6,
		"/views/signup" => 7,
		"/views/layouts/signup" => 8,	
		"/views/layouts/auth/signup" => 9,	
		SIGNUP_PAGE_URL => 10,	
	];
	
	$clean_uri = str_ends_with($request_uri, ".php") ? substr($request_uri, 0, -4) : $request_uri;
	Logger::info("Original URI: {$request_uri}, Cleaned URI: {$clean_uri}");

	// Check if URI exists in the public path array
	$is_public = array_key_exists($request_uri, $public_paths);
	
	// Check if logged in
	// TODO: check if path exists or not
	if (!isset($_SESSION['role']) && !$is_public) {
		// User is not logged in AND the requested page is not a public path
		Logger::info("User role is not set.");
		Logger::info("Rerouting to " . "/views/login");
		header("Location: " . "/views/login");
		exit();
	}

	// Route to different views
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
		case "/views/login":
		case "/views/layouts/login": {
			Logger::info("Rerouting to " . "/views/login");
			header("Location: " . "/views/login");
			exit();	
		}
		
		case "/signup":
		case "/views/signup":
		case "/views/layouts/signup": {
			Logger::info("Rerouting to " . "/views/signup");
			header("Location: " . "/views/signup");
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