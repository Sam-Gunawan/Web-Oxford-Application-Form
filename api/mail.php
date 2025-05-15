<?php
	
require __DIR__ . "/../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

session_start();
$json = json_decode(file_get_contents("php://input"), true);

if (!isset($json["email"]) || !isset($json["msg"])) {
	http_response_code(400);
	$response = ["success" => false, "error" => "Bad Request (no email/msg)"];	
	echo json_encode($response);
	die();
}

$email = $json["email"];
$msg = $json["msg"];

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv("../credentials.env");

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Enable verbose debug output (for testing)
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $_ENV["GMAIL_USERNAME"];         // SMTP username (your full Gmail address)
    $mail->Password   = $_ENV["GMAIL_PASSWORD"];          // SMTP password (the App Password you generated)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    // TCP port to connect to; use 465 for `SMTPSecure = PHPMailer::ENCRYPTION_SMTPS`

    // Recipients
    $mail->setFrom($_ENV["GMAIL_USERNAME"]); // Sender (must be your Gmail address)
    $mail->addAddress($email);     // Add a recipient
    // $mail->addAddress('another_recipient@example.com');             // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Student Application Review';
    $mail->Body    = "<pre>You are " . $msg . ".</pre>";
    $mail->AltBody =  "You are " . $msg. ".";

    $mail->send();
	$response = ["success" => true];	
} catch (Exception $e) {
	http_response_code(400);
	$response = ["success" => false, "error" => $e->getMessage()];	
}
header("Content-Type: application/json; charset=utf-8");
echo json_encode($response);
?>