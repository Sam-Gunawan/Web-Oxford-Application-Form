<?php 
	define("SERVER", 0);
	// Include libraries
	require_once(__DIR__ . "/../../../include/config.php");
	require_once(WEBSITE_ROOT . "/vendor/autoload.php");
	require_once(WEBSITE_ROOT . "/include/auth.php");
	use Symfony\Component\Uid\Ulid;
	session_start();
	redirect_on_unauthorized("student");
	
	/**
	 * @var \Kreait\Firebase\Contract\Factory $factory
	 * @var \Kreait\Firebase\Contract\Auth $auth
	 * @var \Kreait\Firebase\Contract\Database $realtime_database
	 * @var \Kreait\Firebase\Contract\RemoteConfig $remote_config
	 * @var \Kreait\Firebase\Contract\Storage $cloud_storage
	 * @var \Kreait\Firebase\Contract\Firestore $firestore
	 */
	$submitted = false;
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$uid = (string)(new Ulid());
		// $token = $auth->createCustomToken($uid);
		

		$fields = handle_form_submit();
		// Insert to DB

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