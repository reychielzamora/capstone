<?php
require_once __DIR__ . '/../model/AdminModel.php';
$admin = new AdminModel();

if (isset($_POST['update_off'])) {

    // 🔹 REQUIRED FIELDS
    $id       = $_POST['id'] ?? '';
    $fname    = trim($_POST['fname'] ?? '');
    $mname    = trim($_POST['mname'] ?? '');
    $lname    = trim($_POST['lname'] ?? '');
    $dob      = $_POST['dob'] ?? '';
    $pob      = trim($_POST['pob'] ?? '');
    $cs       = $_POST['cs'] ?? '';
    $email    = trim($_POST['email'] ?? '');
    $contact  = trim($_POST['contact'] ?? '');
    $position = $_POST['position'] ?? '';
    $brgy     = $_POST['brgy'] ?? '';
    $title    = trim($_POST['otitle'] ?? '');
    $emp_id   = trim($_POST['emp_id'] ?? '');
    $oldPhoto = $_POST['old_photo'] ?? '';

    if (
        empty($id) || empty($fname) || empty($lname) || empty($dob) ||
        empty($pob) || empty($cs) || empty($email) || empty($contact) ||
        empty($position) || empty($brgy) || empty($title) || empty($emp_id)
    ) {
        echo json_encode(['error' => 'All required fields must be filled.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email format.']);
        exit;
    }
    $photoToSave = $oldPhoto;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = $_FILES['photo']['type'];
        $fileSize = $_FILES['photo']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(['error' => 'Only JPG and PNG files are allowed.']);
            exit;
        }

        if ($fileSize > 2 * 1024 * 1024) { // 2MB
            echo json_encode(['error' => 'File size must be less than 2MB.']);
            exit;
        }

        $fileName = $_FILES['photo']['name'];
        $tmpName  = $_FILES['photo']['tmp_name'];

        $newFileName = time() . '_' . basename($fileName);

        if (move_uploaded_file($tmpName, "../profiles/" . $newFileName)) {

            if (!empty($oldPhoto) && file_exists("../profiles/" . $oldPhoto)) {
                unlink("../profiles/" . $oldPhoto);
            }

            $photoToSave = $newFileName;
        }
    }

    $res = $admin->updateInfoOfficial($id, $fname, $mname, $lname, $dob, $pob, $cs, $email, $contact, $position, $brgy, $title, $emp_id, $photoToSave);
    if ($res) {
        echo json_encode(['success' => 'Official updated successfully']);
    } else {
        echo json_encode(['error' => 'Update failed']);
    }
}
