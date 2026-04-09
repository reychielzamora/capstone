<?php
header('Content-Type: application/json; charset=utf-8');
include("../model/Auth.php");

$sup = new Signup();
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['verify'])) {

    $otp = $data['otpCode'] ?? '';
    $email = $data['email'] ?? '';

    if (empty($otp) || empty($email)) {
        echo json_encode(['error' => 'OTP and email are required']);
        exit;
    }

    $input = $sup->verify($otp, $email);

    if ($input === 3) {
        echo json_encode(['error' => 'Error db']);
        exit;
    } else if ($input === 2) {
        echo json_encode(['error' => 'Error setting verified']);
        exit;
    } else if ($input === 1) {
        echo json_encode([

            'success' => 'Account created, please check your email for verification'
        ]);
        exit;
    } else {
        echo json_encode(['error' => 'Unknown error']);
        exit;
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}
