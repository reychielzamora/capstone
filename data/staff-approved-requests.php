<?php
session_start();
require_once __DIR__ . '/../model/Staff.php';

header('Content-Type: application/json');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['id'] ?? null;

if (!$user_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid user ID'
    ]);
    exit;
}

$staff = new Staff();
$result = $staff->approveRequest($user_id);

echo json_encode($result);
exit;