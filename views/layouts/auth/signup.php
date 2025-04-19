<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/backend/auth/signup-handler.php");
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Sign Up</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> ?>" method="POST">
			<label for="user-email">E-mail</label>
			<input id="user-email" type="email" name="user-email" placeholder="E-mail" maxlength="320" required>
			<label for="user-password">Password</label>
			<input id="user-password" type="password" name="user-password" placeholder="Password" maxlength="50" required>
			<button type="submit">Submit</button>
			<button type="reset">Reset</button>
		</form>
		<p>Already have an account? <a href="<?php echo htmlspecialchars("/views/login"); ?>">Login</a></p>
	</body>
</html>