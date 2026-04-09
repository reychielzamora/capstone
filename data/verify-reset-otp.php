<?php
header('Content-Type: application/json; charset=utf-8');
include("../model/Auth.php");

$sup = new Signup();
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['verify_otp'])) {

    $otp = $data['otpCode'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['pass'] ?? '';

    if (empty($otp) || empty($email)) {
        echo json_encode(['error' => 'OTP and email are required']);
        exit;
    }

    $input = $sup->verifyChangePassword($otp, $email, $password);

    if ($input === 1) {
        echo json_encode(['error' => "Email not found"]);
        exit;
    } else if ($input === 2) {
        echo json_encode(['error' => "Invalid OTP"]);
        exit;
    } else if ($input === 3) {
        echo json_encode([
            'error' => "Password update failed"
        ]);
        exit;
    } else {
        echo json_encode(['success' => 'Your password has been changed you can now log in']);
        exit;
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}
