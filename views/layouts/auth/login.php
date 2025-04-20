<?php
	define("SERVER", true);
	session_start();
	require_once(__DIR__ . "/../../../include/config.php");
	if (!CLEAN_URI) {
		require_once(__DIR__ . "/../../../include/auth.php");
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$login_result = handle_login_request();
			if ($login_result === true) { 
				Logger::info("Login successful for user: " . ($_SESSION["user-email"] ?? "N/A"));
				header("Location: " . WEBSITE_ROOT . DASHBOARD_PAGE_URL, true, 303);
				exit(); 
			} else {
				Logger::warn("Login failed for URL: " . $request_uri . ". Error: " . ($login_result ?: "Unknown error"));
				$_SESSION["login_error_message"] = ($login_result ?: "Login failed. Please try again.");
			}
		}
	}
?>

<!DOCTYPE HTML>
<html lang="en" data-bs-theme="dark">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Login</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<form action="<?php echo htmlspecialchars(CLEAN_URI ? "/views/login" : $_SERVER["PHP_SELF"]); ?>" method="post">
			<label for="user-email">E-mail</label>
			<input id="user-email" type="email" name="user-email" placeholder="E-mail" maxlength="320" required>
			<label for="user-password">Password</label>
			<input id="user-password" type="password" name="user-password" placeholder="Password" maxlength="50" required>
			<button type="submit">Submit</button>
			<button type="reset">Reset</button>
		</form>
		<p>Don't have an account? <a href="<?php echo htmlspecialchars(CLEAN_URI ? "/views/signup" : "/Web-Oxford-Application-Form/views/layouts/auth/signup.php"); ?>">Sign Up</a></p>
	</body>
</html>
