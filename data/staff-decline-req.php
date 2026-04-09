<?php
header('Content-Type: application/json');

require_once '../model/Staff.php';

$staff = new Staff();
$id = $_POST['id'] ?? null;

if (empty($id)) {
    echo json_encode([
        'error' => "No id has been sent"
    ]);
    exit;
}

$decline = $staff->declineRequests($id);

if ($decline) {
    echo json_encode([
        'success' => "Successfully declined"
    ]);
    exit;
} else {
    echo json_encode([
        'error' => "Error declining request"
    ]);
    exit;
}
