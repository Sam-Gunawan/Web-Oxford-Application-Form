<?php
	// Include config file
	require_once(__DIR__ . '/backend/include/config.php');
	session_start();

	function handle_login_request(): bool {
		$filters = array(
			"user-email" => array (
				"filter" => FILTER_VALIDATE_EMAIL,
				"flags" => FILTER_NULL_ON_FAILURE
			),
			"user-password" => array (
				"filter" => FILTER_UNSAFE_RAW,
				"flags" => FILTER_NULL_ON_FAILURE
				)
		);
		$filtered_body = filter_input_array(INPUT_POST, $filters, false);
		$email = $filtered_body["user-email"];
		$password_hash = password_hash($filtered_body["user-password"], PASSWORD_ARGON2ID);

		Logger::debug("E-mail: " . $email);
		Logger::debug("Password Hash: " . $password_hash);

		// TODO: Add more checks (email not found, wrong password)
		if ($email === false || $email === null) {
			$_SESSION["login_error_message"] = "Invalid email";
			return false;
		} 

		if ($password_hash === false || $password_hash === null) {
			$_SESSION["login_error_message"] = "Invalid password";
			return false;
		}		

		$_SESSION["role"] = "reviewer";
		return true;
	}

	// Get the requested URI
	$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

	$clean_uri = $request_uri;
	if ($clean_uri !== '/' && substr($clean_uri, -1) === '/') {
		$clean_uri = substr($clean_uri, 0, -1);
	}
	if (str_ends_with($clean_uri, '.php')) {
		$clean_uri = substr($clean_uri, 0, -4);
	}
	Logger::info("Original URI: {$request_uri}, Cleaned URI: {$clean_uri}");
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// Check the $clean_uri to determine which POST handler logic to execute
		switch ($clean_uri) {
			case '/views/login': {
				$login_result = handle_login_request(); 
				Logger::debug("Login Result: " . ($login_result ? "True" : "False"));
				if ($login_result === true) { 
					Logger::info("Login successful for user: " . ($_SESSION['user-email'] ?? 'N/A'));
					// Redirect to the dashboard after successful login
					header("Location: /dashboard", true, 303);
					// IMPORTANT: Stop execution after redirect
					exit(); 
					
				} else {
					Logger::warn("Login failed for URL: " . $request_uri . ". Error: " . ($login_result ?: 'Unknown error'));
					// Store the error message in the session to display on the login form page
					$_SESSION['login_error_message'] = ($login_result ?: "Login failed. Please try again.");
					
					// DO NOT REDIRECT ON FAILURE. Let the script fall through to
					// the authentication check and router, which will display the login form
					// and the error message from the session.
				}
			} break;
	
			// TODO: Add cases for other POST handlers (/form-submit, /api/save-data)
	
			default: {
				// Handle unexpected POST requests to URLs not designed for POST
				Logger::warn("Received unexpected POST request to URL: " . $clean_uri);
				header("HTTP/1.0 405 Method Not Allowed");
				echo "Method Not Allowed for this URL via POST";
			} exit(); // Exit from file
		}
	}
	
	// Handle failed validation after login check

	// Public paths (accessible without authentication)
	$public_paths = [
		'/views/login' => 1,       // THE login page clean URL
		'/views/signup' => 2,      // Registration page clean URL
		// TODO: Add other URLs
	];

	// Check if URI exists in the public path array
	$is_public = array_key_exists($clean_uri, $public_paths);
	
	Logger::debug("Is public: " . ($is_public ? "True" : "False"));
	Logger::debug("Role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : "None"));

	if (!isset($_SESSION['role']) && !$is_public) {
		Logger::info("Access denied to protected page: " . $clean_uri . ". Redirecting to login.");
		// Redirect to the login page clean URL
		header("Location: /views/login", true, 303); 
		exit(); // Stop further execution
	}

	$login_error_message = "";
	if (isset($_SESSION['login_error_message'])) {
		$login_error_message = $_SESSION['login_error_message'];
		unset($_SESSION['login_error_message']); // Clear it after getting it for display
	}

	switch ($clean_uri) {
		case '/': {
			require_once(__DIR__ . DASHBOARD_PAGE_URL);
		} break;
	
		case '/views/login': {
			// The user is allowed to see the login page.
			// If logged in and accessing /views/login, you might redirect to dashboard instead
			if (isset($_SESSION['role'])) {
				 Logger::info("Logged-in user tried to access login page. Redirecting to dashboard.");
				 header("Location: /dashboard", true, 303); // Adjust dashboard URL if needed
				 exit(); // Stop execution
			}
			// If not logged in (and allowed to see login), include the login form template
			// The $loginErrorMessage variable (from failed POST) is available here.
			require_once(__DIR__ . LOGIN_PAGE_URL); // Include )the login form template
		} break;
	
		case '/views/signup': {
			require_once(__DIR__ . SIGNUP_PAGE_URL); // Include )signup template	
		} break;
	
		case '/dashboard': {
			// This case is only reached if the authentication check above PASSED.
			// Check here if additional authorization is needed for dashboard (e.g., admin role)
			require_once(__DIR__ . DASHBOARD_PAGE_URL); 
		} break;
	
		// TODO: Add other routes

		default: {
			Logger::warn("Route not found for: " . $clean_uri);
			header("HTTP/1.0 404 Not Found"); // Send 404 status
			echo "Page " . htmlspecialchars($request_uri) . " Not Found"; // Sanitize original URL in 404 message
			// Return error code 404 if no route matched
			exit(); // Exit after showing 404
		} break;
	}
?>