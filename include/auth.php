<?php 
	if (!defined("SERVER")) {
		http_response_code(403);
		die();
	}

	require_once(__DIR__ . "/config.php");
	// require_once(__DIR__ . "/reply.php");
	require_once(__DIR__ . "/router.php");

	function get_filtered_email_password() {
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

		return ["email" => $email, "password_hash" => $password_hash];
	}

	function handle_signup_request(): bool {
		Logger::debug("Handling signup.");

		$filter_result = get_filtered_email_password();
		$email = $filter_result["email"];	
		$password_hash = $filter_result["password_hash"];	

		if ($email === false || $email === null) {
			$_SESSION["login_error_message"] = "Invalid email";
			return false;
		} 

		if ($password_hash === false || $password_hash === null) {
			$_SESSION["login_error_message"] = "Invalid password";
			return false;
		}

		$_SESSION["role"] = "student";
		return true;
	}

	function handle_login_request(): bool {
		Logger::debug("Handling login.");	

		$filter_result = get_filtered_email_password();
		$email = $filter_result["email"];	
		$password_hash = $filter_result["password_hash"];

		// TODO: Add more checks (email not found, wrong password)
		if ($email === false || $email === null) {
			$_SESSION["login_error_message"] = "Invalid email";
			return false;
		} 

		if ($password_hash === false || $password_hash === null) {
			$_SESSION["login_error_message"] = "Invalid password";
			return false;
		}		

		// TODO: Determine role based on account
		$_SESSION["role"] = "reviewer";
		return true;
	}

	function debug_reset_session() {	
		// Unset all session variables
		$_SESSION = array(); // Clear the $_SESSION array
	
		// Destroy the session data on the server
		if (session_status() === PHP_SESSION_ACTIVE) { // Check if session is active before destroying
			session_destroy();
		}
	
		// Delete the session cookie (makes the browser forget the old session ID)
		// This is to trick the browser to thinking the cookie is expired
		$params = session_get_cookie_params();
		setcookie(
			session_name(), // The name of the session cookie (e.g., PHPSESSID)
			"", // Empty value
			time() - 42000, // A time in the past to expire the cookie
			$params["path"],
			$params["domain"],
			$params["secure"],
			$params["httponly"]
		);
	
		// Start a brand new session immediately after destroying the old one
		// This will generate a new session ID and cookie
		session_start(); // Start the new session
	
		Logger::debug("New session started.");
	}

	function handle_form_submit() {
		$filters = [
			
		];
		$fields = filter_input_array(INPUT_POST, $filters);

		return $fields;
	}

	// TODO: Add other checks
	function redirect_on_unauthorized(?string $role = null) {
		if (!CLEAN_URI && !isset($_SESSION["role"]) && ($_SESSION["role"] != $role || !$role)) {
			Logger::debug("LOGGING FROM " . __DIR__); 
			redirect(WEBSITE_ROOT . LOGIN_PAGE_URL);
		}
	}
?>