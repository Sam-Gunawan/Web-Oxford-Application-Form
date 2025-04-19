<?php 
	require_once($_SERVER['DOCUMENT_ROOT'] . "/server/include/config.php");
	session_start();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
		$password_hash = password_hash($filtered_body["user-password"], PASSWORD_ARGON2ID);
		$email = $filtered_body["user-email"];

		if ($email && $password_hash) {
			Logger::info("User {$email} logged in");
		} else {
		}
	}
?>