<?php
session_start();

header('Content-Type: application/json');

require_once "../model/Staff.php";

$admin = new Staff();

if (isset($_POST['addOfficial'])) {


    $fname    = trim($_POST['fname'] ?? '');
    $mname    = trim($_POST['mname'] ?? '');
    $lname    = trim($_POST['lname'] ?? '');
    $dob      = trim($_POST['dob'] ?? '');
    $pob      = trim($_POST['pob'] ?? '');
    $cs       = trim($_POST['cs'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $contact  = trim($_POST['contact'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $brgy     = trim($_POST['brgy'] ?? '');
    $title    = trim($_POST['otitle'] ?? '');
    $emp_id   = trim($_POST['emp_id'] ?? '');

    $errors = [];

    if (empty($fname))    $errors[] = "First name is required";
    if (empty($lname))    $errors[] = "Last name is required";
    if (empty($dob))      $errors[] = "Date of birth is required";
    if (empty($pob))      $errors[] = "Place of birth is required";
    if (empty($cs))       $errors[] = "Civil status is required";
    if (empty($email))    $errors[] = "Email is required";
    if (empty($contact))  $errors[] = "Contact number is required";
    if (empty($position)) $errors[] = "Position is required";
    if (empty($brgy))     $errors[] = "Barangay is required";
    if (empty($title))    $errors[] = "Official title is required";
    if (empty($emp_id))   $errors[] = "Employee ID is required";

    if (!empty($fname) && !preg_match("/^[a-zA-Z\s]+$/", $fname)) {
        $errors[] = "First name must contain only letters";
    }

    if (!empty($lname) && !preg_match("/^[a-zA-Z\s]+$/", $lname)) {
        $errors[] = "Last name must contain only letters";
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (!empty($contact) && !preg_match("/^[0-9]{10,15}$/", $contact)) {
        $errors[] = "Contact must be 10–15 digits";
    }

    if (!empty($dob)) {
        $today = date('Y-m-d');
        if ($dob >= $today) {
            $errors[] = "Date of birth must be in the past";
        }
    }

    if (!empty($emp_id) && !preg_match("/^[a-zA-Z0-9\-]+$/", $emp_id)) {
        $errors[] = "Employee ID must be alphanumeric";
    }

    if (!empty($errors)) {
        echo json_encode([
            'error' => implode(', ', $errors)
        ]);
        exit;
    }

    $uploadDir = "../profiles/";
    $photoName = '';

    if (!empty($_FILES['photo']['name'])) {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

        $fileType = $_FILES['photo']['type'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowedTypes) || !in_array($ext, $allowedExt)) {
            echo json_encode(['error' => 'Only image files are allowed']);
            exit;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if ($_FILES['photo']['error'] === 0) {
            $photoName = time() . "_" . basename($_FILES['photo']['name']);
            $filePath = $uploadDir . $photoName;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                echo json_encode(['error' => 'Upload failed']);
                exit;
            }
        } else {
            echo json_encode(['error' => 'Upload error']);
            exit;
        }
    }

    $insert = $admin->addOfficials($fname, $lname, $mname, $dob, $pob, $cs, $email, $contact, $position, $brgy, $title, $photoName, $emp_id);

    if ($insert === 1) {
        echo json_encode(['success' => "Successfully added"]);
    } else {
        echo json_encode(['error' => "Error db"]);
    }

    exit;
}
