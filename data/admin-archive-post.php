<?php
require_once __DIR__ . '/../model/AdminModel.php';
$admin = new AdminModel();


$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['error' => 'Invalid.']);
    exit;
}

$res = $admin->deletePost($id);
if ($res) {
    echo json_encode(['success' => 'Deleted successfully']);
} else {
    echo json_encode(['error' => 'failed to delete']);
}
