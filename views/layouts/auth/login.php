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
		<p>Or <button id="google-login">Continue with Google</button></p>
	</body>
	<script type="module">
		import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
		import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

		const app = initializeApp({
			apiKey: "AIzaSyB1ZErQBrQ3wZrX4seBSKFhQvlAzPiHk1E",
			authDomain: "oxfordweb-local.firebaseapp.com",
			projectId: "oxfordweb-local",
			storageBucket: "oxfordweb-local.firebasestorage.app",
			messagingSenderId: "502474430288",
			appId: "1:502474430288:web:6e9dcd638677f0adb6be55",
			measurementId: "G-YV7FL9Q735"
		});
		const auth = getAuth(app);
		const provider = new GoogleAuthProvider();
		document.getElementById("google-login").onclick = async function() {
			const { user } = await signInWithPopup(auth, provider);
			const idToken = await user.getIdToken();

			fetch (<?php echo WEBSITE_ROOT . "/api/google-login.php";?>, {
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify({ id_token: idToken })
			}).then(resp => {
				// Check if the HTTP status code is OK (2xx)
				if (!resp.ok) {
					// Server returned an error status code (4xx, 5xx)
					// Try to parse JSON error body, but handle cases where it might not be JSON
					return resp.json().catch(() => {    
						// If parsing JSON fails, create a generic error object
						throw new Error(`HTTP error! Status: ${resp.status}`);
					}).then(errorData => {
						// If JSON parsing succeeded, throw an error with the server's message
						throw new Error(errorData.message || `HTTP error! Status: ${resp.status}`);
					});
				}
				return response.json();
			}).catch(err => {
				alert(err);
			});

			location.href = <?php echo WEBSITE_ROOT . "/views/layouts/dashboard.php";?>
		}
	</script>
</html>
