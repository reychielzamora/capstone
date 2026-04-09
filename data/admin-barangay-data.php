<?php
require_once '../model/AdminModel.php';

header('Content-Type: application/json');

$admin = new AdminModel();

try {
    $data = $admin->getPostsPerBarangay();

    $labels = [];
    $values = [];

    foreach ($data as $row) {
        $labels[] = $row['BARANGAY'] ?? 'Unknown';
        $values[] = (int)($row['total'] ?? 0);
    }

    echo json_encode([
        'labels' => $labels,
        'data' => $values
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}