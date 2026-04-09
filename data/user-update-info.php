<?php
session_start();
header('Content-Type: application/json');

require_once '../model/Client.php';

$staff = new Client();

try {
    $id      = $_POST['user_id'];
    $fname   = trim($_POST['fname'] ?? '');
    $mname   = trim($_POST['mname'] ?? '');
    $lname   = trim($_POST['lname'] ?? '');
    $dob     = $_POST['dob'] ?? '';
    $pob     = trim($_POST['pob'] ?? '');
    $cs      = $_POST['cs'] ?? '';
    $gender  = $_POST['gender'] ?? '';
    $email   = trim($_POST['uemail'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $brgy    = $_POST['brgy'] ?? '';
    $street  = trim($_POST['street'] ?? '');
    $city    = trim($_POST['city'] ?? '');

    $old_signature = $_POST['old_signature'] ?? null;
    $old_pp        = $_POST['old_pp'] ?? null;

    if (!$id || !$fname || !$lname) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }


    $signaturePath = $old_signature;
    $avatarPath    = $old_pp;


    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {

        $avatarDir = __DIR__ . '/../profiles/';
        if (!is_dir($avatarDir)) {
            mkdir($avatarDir, 0755, true);
        }

        $avatar = $_FILES['avatar'];
        $avatarExt = pathinfo($avatar['name'], PATHINFO_EXTENSION);

        $avatarFilename = 'avatar_' . $id . '_' . time() . '.' . $avatarExt;
        $avatarFullPath = $avatarDir . $avatarFilename;

        if (move_uploaded_file($avatar['tmp_name'], $avatarFullPath)) {
            if (!empty($old_pp) && file_exists(__DIR__ . '/../profiles/' . basename($old_pp))) {
                unlink(__DIR__ . '/../profiles/' . basename($old_pp));
            }
            $avatarPath = '../profiles/' . $avatarFilename;
        } else {
            echo json_encode(['success' => false, 'error' => 'Avatar upload failed']);
            exit;
        }
    }


    if (isset($_FILES['signature']) && $_FILES['signature']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $file = $_FILES['signature'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        $newFilename = 'official_' . $id . '_' . time() . '.' . $ext;
        $uploadPath = $uploadDir . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {

            // ✅ delete old signature (optional)
            if (!empty($old_signature) && file_exists(__DIR__ . '/' . $old_signature)) {
                unlink(__DIR__ . '/' . $old_signature);
            }

            $signaturePath = '../uploads/' . $newFilename;
        } else {
            echo json_encode(['success' => false, 'error' => 'Signature upload failed']);
            exit;
        }
    }

    $saved = $staff->updateInfo($id, $fname, $mname, $lname, $dob, $pob, $cs, $gender, $email, $contact, $brgy, $street, $city, $signaturePath, $avatarPath);

    if ($saved) {
        echo json_encode([
            'success' => true,
            'message' => 'Saved successfully!',
            'signature' => $signaturePath,
            'avatar' => $avatarPath
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database insert failed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
