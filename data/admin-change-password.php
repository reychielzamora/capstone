<?php
require_once '../model/AdminModel.php';

header('Content-Type: application/json');

$admin = new AdminModel();

try {
    $u_id = $_POST['u_id'] ?? null;
    $current = $_POST['current'] ?? '';
    $newpass = $_POST['newpass'] ?? '';

    if (!$u_id || !$current || !$newpass) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required'
        ]);
        exit;
    }

    $result = $admin->changePassword($u_id, $current, $newpass);

    echo json_encode($result);

} catch (Throwable $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}