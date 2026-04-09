<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['token_login'])) {
    echo json_encode(['success' => false, 'error' => 'Session expired']);
    exit;
}

try {
    if (!isset($_FILES['certificate_image'])) {
        throw new Exception('No image uploaded');
    }

    $uploadDir = __DIR__ . '/../upload/certificates/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $file = $_FILES['certificate_image'];
    $filename = $_POST['filename'] ?? 'certificate_' . time() . '.png';
    $uploadPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo json_encode([
            'success' => true,
            'image_path' => "data/certificates/$filename",
            'filename' => $filename,
            'full_path' => $uploadPath
        ]);
    } else {
        throw new Exception('File save failed');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>