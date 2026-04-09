<?php
session_start();

header('Content-Type: application/json');

require_once "../model/AdminModel.php";

$admin = new AdminModel();

if (isset($_POST['addOfficial'])) {

    function clean($data)
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    $errors = [];

    $fname   = clean($_POST['fname'] ?? '');
    $mname   = clean($_POST['mname'] ?? '');
    $lname   = clean($_POST['lname'] ?? '');
    $dob     = $_POST['dob'] ?? '';
    $pob     = clean($_POST['pob'] ?? '');
    $cs      = clean($_POST['cs'] ?? '');
    $email   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $contact = clean($_POST['contact'] ?? '');
    $position = intval($_POST['position'] ?? 0);
    $brgy    = intval($_POST['brgy'] ?? 0);
    $title   = clean($_POST['otitle'] ?? '');
    $emp_id  = clean($_POST['emp_id'] ?? '');

    $uploadDir = "../profiles/";
    $photoName = '';

    if (empty($fname)) $errors[] = "First name is required";
    if (empty($lname)) $errors[] = "Last name is required";
    if (empty($dob)) $errors[] = "Date of birth is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($contact)) $errors[] = "Contact is required";
    if ($position <= 0) $errors[] = "Invalid position";
    if ($brgy <= 0) $errors[] = "Invalid barangay";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (!preg_match('/^[0-9]{10,11}$/', $contact)) {
        $errors[] = "Invalid contact number";
    }

    if (!empty($dob) && strtotime($dob) > time()) {
        $errors[] = "Invalid birth date";
    }

    if (!preg_match('/^[a-zA-Z\s]+$/', $fname)) {
        $errors[] = "First name must contain letters only";
    }

    if (!preg_match('/^[a-zA-Z\s]*$/', $mname)) {
        $errors[] = "Middle name must contain letters only";
    }

    if (!preg_match('/^[a-zA-Z\s]+$/', $lname)) {
        $errors[] = "Last name must contain letters only";
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {

        $fileTmp  = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileSize = $_FILES['photo']['size'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($fileExt, $allowed)) {
            $errors[] = "Only JPG, JPEG, PNG allowed";
        }

        if ($fileSize > 2 * 1024 * 1024) { // 2MB
            $errors[] = "File too large (max 2MB)";
        }

        if (empty($errors)) {
            $photoName = uniqid() . '.' . $fileExt;
            move_uploaded_file($fileTmp, $uploadDir . $photoName);
        }
    }

    if (!empty($errors)) {
        echo json_encode([
            'error' => implode(", ", $errors)
        ]);
        exit;
    }

    $result = $admin->addOfficials(
        $fname,
        $lname,
        $mname,
        $dob,
        $pob,
        $cs,
        $email,
        $contact,
        $position,
        $brgy,
        $title,
        $photoName,
        $emp_id
    );

    if ($result === 1) {
        echo json_encode(['error' => "Email already exist"]);
    } else if ($result === 2) {
        echo json_encode(['error' => "Error adding officials"]);
    } else if ($result === 3) {
        echo json_encode(['error' => "Error adding to users"]);
    } else if ($result === 4) {
        echo json_encode(['error' => "Error adding to staff"]);
    } else {
        echo json_encode(['success' => "Official added successfully"]);
    }
}
