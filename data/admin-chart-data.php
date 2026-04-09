<?php
require_once '../model/AdminModel.php';

header('Content-Type: application/json'); // ✅ ensure proper JSON response

$admin = new AdminModel();

// Validate year (avoid invalid input)
$year = isset($_GET['year']) && is_numeric($_GET['year'])
    ? (int) $_GET['year']
    : date('Y');

    

$data = $admin->getUsersPerMonth($year);

// Ensure all 12 months exist
$months = array_fill(1, 12, 0);

foreach ($data as $row) {
    $monthIndex = (int)$row['month'];

    if ($monthIndex >= 1 && $monthIndex <= 12) {
        $months[$monthIndex] = (int)$row['total'];
    }
}

// Return clean indexed array (Jan–Dec)
echo json_encode(array_values($months));