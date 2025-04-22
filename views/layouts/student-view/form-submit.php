<?php 
	define("SERVER", 0);
	// Include libraries
	require_once(__DIR__ . "/../../../include/config.php");
	require_once(WEBSITE_ROOT . "/vendor/autoload.php");
	require_once(WEBSITE_ROOT . "/include/auth.php");
	session_start();
	redirect_on_unauthorized("student");
	
	$submitted = false;
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$fields = handle_form_submit();

	}

	if ($submitted) {
		// TODO: Add form submission response page
		// require("");
		echo "Form submitted! <br>";
		var_dump($_POST);
	} else {
		require(WEBSITE_ROOT . FORM_PAGE_URL);
	}
?>