<?php
session_start();

if (!isset($_POST['uid'])) {
    http_response_code(400);
    exit("UID not provided");
}

$uid = $_POST['uid'];
$usersFile = 'users.json';

if (!file_exists($usersFile)) {
    http_response_code(500);
    exit("User database not found");
}

$usersData = json_decode(file_get_contents($usersFile), true);

if (!isset($usersData[$uid])) {
    http_response_code(404);
    exit("User not found");
}

// Fake login success
$user = $usersData[$uid];

$_SESSION['user'] = [
    'id' => $uid,
    'name' => $user['name'],
    'role' => $user['role']
];

header('Location: ../dashboard.php');
exit;
