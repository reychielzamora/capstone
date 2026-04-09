<?php
header('Content-Type: application/json');

require __DIR__ . '/../model/Auth.php';

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$email = $data['email'] ?? '';

if (!$email) {
    echo json_encode(["error" => "Missing data"]);
    exit;
}

$auth = new Signup(); 
$result = $auth->requestPasswordReset($email);

if ($result === 1) {
    echo json_encode(["error" => "Account not found"]);
} else if($result === 0) {
    echo json_encode(["error" => "Failed to send code"]);
} else {
    echo json_encode(["success" => true]);
}