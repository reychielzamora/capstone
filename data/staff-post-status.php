<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../model/Staff.php';

$staff = new Staff();

$postId = (int)($_POST['post_id'] ?? 0);
$status = (int)($_POST['status'] ?? 0);

if ($postId <= 0 || !in_array($status, [1, 2])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

try {
    $updated = $staff->updatePostStatus($postId, $status);

    if ($updated) {
        echo json_encode([
            'success' => true,
            'message' => 'Status updated',
            'new_status' => $status
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database error'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
