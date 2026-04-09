<?php


require_once '../model/Connection.php';

$mysqli = Dbh::getInstance();

header('Content-Type: application/json');

if (isset($_POST['addData'])) {
    $response = [
        'status' => 'error',
        'message' => 'Something went wrong'
    ];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = '<p class="text-red-500">Invalid request method</p>';
        echo json_encode($response);
        exit;
    }

    $brgy = isset($_POST['brgy']) ? trim($_POST['brgy']) : '';

    $errors = [];

    if (empty($brgy)) {
        $errors[] = 'Barangay field is required';
    }

    if (strlen($brgy) < 2) {
        $errors[] = 'Barangay name must be at least 2 characters';
    }

    if (strlen($brgy) > 100) {
        $errors[] = 'Barangay name must be less than 100 characters';
    }

    if (!preg_match('/^[a-zA-Z\s-]+$/', $brgy)) {
        $errors[] = 'Barangay name can only contain letters, spaces, and hyphens';
    }

    if (!empty($errors)) {
        $response['message'] = '<p class="text-red-500">' . implode('<br>', $errors) . '</p>';
        echo json_encode($response);
        exit;
    }

    $check_stmt = $mysqli->prepare("SELECT COUNT(*) FROM tbl_brgy WHERE BARANGAY = ?");
    $check_stmt->bind_param("s", $brgy);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        $response['message'] = '<p class="text-red-500">Barangay already exists</p>';
        echo json_encode($response);
        exit;
    }

    $stmt = $mysqli->prepare("INSERT INTO tbl_brgy (BARANGAY) VALUES (?)");
    $stmt->bind_param("s", $brgy);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = '<p class="text-green-500">Barangay added successfully!</p>';
    } else {
        $response['message'] = '<p class="text-red-500">Failed to insert record</p>';
    }

    $stmt->close();

    echo json_encode($response);
    exit;
}

