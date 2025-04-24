<?php
	session_start();
	$session_cookie = $_COOKIE["user_session"];
	$request = file_get_contents("php://input");
	$request_body = json_decode($request, true);
	$id_token = $request_body["id_token"];  

	
?>