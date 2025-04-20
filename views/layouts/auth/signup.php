<?php 
	define("SERVER", true);
	session_start();
	require_once(__DIR__ . "/../../../include/config.php");
	if (!CLEAN_URI) {
		require_once(__DIR__ . "/../../../include/auth.php");
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$signup_result = handle_signup_request();
			if ($signup_result === true) { 
				Logger::info("Signup successful for user: " . ($_SESSION["user-email"] ?? "N/A"));
				header("Location: " . WEBSITE_ROOT . DASHBOARD_PAGE_URL, true, 303);
				exit(); 
				
			} else {
				Logger::warn("Signup failed for URL: " . $request_uri . ". Error: " . ($signup_result ?: "Unknown error"));
				$_SESSION["signup_error_message"] = ($signup_result ?: "Signup failed. Please try again.");
			}
		}
	}
?>

<!DOCTYPE HTML>
<html lang="en" data-bs-theme="dark">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Sign Up</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<form action="<?php echo htmlspecialchars(CLEAN_URI ? "/views/signup" : $_SERVER["PHP_SELF"]); ?>" method="POST">
			<label for="user-email">E-mail</label>
			<input id="user-email" type="email" name="user-email" placeholder="E-mail" maxlength="320" required>
			<label for="user-password">Password</label>
			<input id="user-password" type="password" name="user-password" placeholder="Password" maxlength="50" required>
			<button type="submit">Submit</button>
			<button type="reset">Reset</button>
		</form>
		<p>Already have an account? <a href="<?php echo htmlspecialchars(CLEAN_URI ? "/views/login" : "/Web-Oxford-Application-Form/views/layouts/auth/login.php"); ?>">Login</a></p>
	</body>
</html>