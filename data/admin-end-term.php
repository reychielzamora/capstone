<?php
session_start();
require_once __DIR__ . '/../model/AdminModel.php';

header('Content-Type: application/json');

$off_id = $_POST['off_id'] ?? null;
$user   = $_POST['user'] ?? null;
$log    = $_POST['log'];

if (!$off_id || !$user) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$admin = new AdminModel();
$result = $admin->endTerm($off_id, $user, $log);

echo json_encode($result);