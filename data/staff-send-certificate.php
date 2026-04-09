<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../model/Mails.php';

$email = $_POST['email'] ?? '';
if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'No email provided']);
    exit;
}

try {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['file']['tmp_name']);
    finfo_close($finfo);

    $allowedTypes = ['image/png', 'application/pdf'];

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception('Only PNG or PDF allowed');
    }

    $uploadDir = __DIR__ . '/../uploads/certificates/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext = $mimeType === 'application/pdf' ? '.pdf' : '.png';
    $filename = 'certificate_' . time() . $ext;
    $uploadPath = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
        throw new Exception('File save failed');
    }

    $mails = new Mails();
    $mails->sendFile($email, $uploadPath);

    echo json_encode([
        'success' => true,
        'message' => "✅ Certificate sent to $email",
        'filename' => $filename,
        'type' => $mimeType
    ]);

} catch (Exception $e) {
    error_log('Certificate email error: ' . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'Failed: ' . $e->getMessage()
    ]);
}