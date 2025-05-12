<?php
require __DIR__ . "/../vendor/autoload.php";
use Google\Rpc\BadRequest;
$user = json_decode(file_get_contents('php://input'), true);
if (!isset($user["uid"])) {
	http_response_code(400);
	$response = ["success" => false, "error" => "Bad Request (no uid object)", "type" => BadRequest::class];	
	echo json_encode($response);
	die();
}
$_SESSION["user"] = $user;
$response = ["success" => true, "user" => $user];	
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
?>
