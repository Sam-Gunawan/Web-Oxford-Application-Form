<?php
require __DIR__ . "/../vendor/autoload.php";

session_start();
$user = json_decode(file_get_contents("php://input"), true);

use Google\Rpc\BadRequest;
// use Kreait\Firebase\Factory;
// use Kreait\Firebase\Auth;

// if ($user["email"] === "hanssbtn@gmail.com") {
// 	$factory = (new Factory)->withServiceAccount("../firebase-adminsdk-credentials.json");
// 	$auth = $factory->createAuth();
// 	$auth->setCustomUserClaims($user["uid"], ["role" => "reviewer"]);
// 	$user["role"] = "reviewer";
// }

// $_SESSION["user"] = $user;

if (!isset($user["uid"]) || !isset($user["role"])) {
	http_response_code(400);
	$response = ["success" => false, "error" => "Bad Request (no uid/role object)", "type" => BadRequest::class];	
	echo json_encode($response);
	die();
}

$response = ["success" => true, "role" => $user["role"]];
header("Content-Type: application/json; charset=utf-8");
// header("Content-Type: text/plain; charset=utf-8");
echo json_encode($response);
?>
