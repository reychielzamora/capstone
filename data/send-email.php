<?php
header('Content-Type: application/json');

require_once '../model/Mails.php';

$admin = new Mails();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$bodyHtml = $_POST['body'] ?? '';

if (!$email || empty($bodyHtml)) {
    exit(json_encode(['error' => 'Invalid email or empty content']));
}

$result = $admin->sendOfficialsEmail($email, $bodyHtml);

echo json_encode($result);