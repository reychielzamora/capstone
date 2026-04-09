<?php

header('Content-Type: application/json');
header("Cross-Origin-Opener-Policy: same-origin-allow-popups");

require __DIR__ . '/../model/Auth.php';
$set = new Signup();

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode([
        "status" => "error",
        "message" => "No data received"
    ]);
    exit;
}

$email = $data['email'] ?? '';
$name  = $data['name'] ?? '';
$photo = $data['photo'] ?? '';
$uid   = $data['uid'] ?? '';

if (!$email || !$uid) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid Google data"
    ]);
    exit;
}

// OPTIONAL → split name
$nameParts = explode(" ", $name);
$fname = $nameParts[0] ?? '';
$lname = $nameParts[1] ?? '';

// INSERT OR CHECK EXISTING USER
$result = $set->insertGoogleUser($uid, $email, $fname, $lname, $photo);

if ($result === 1) {
    echo json_encode([
        "status" => "error",
        "message" => "Account already exist"
    ]);
} else if ($result === 2) {
    echo json_encode([
        "status" => "error",
        "message" => "Database insert failed"
    ]);
} else if ($result === 3) {
    echo json_encode([
        "status" => "error",
        "message" => "Query failed"
    ]);
} else if ($result === 4) {
    echo json_encode([
        "status" => "error",
        "message" => "Statement failed"
    ]);
} else if ($result === 5) {
    echo json_encode([
        "status" => "error",
        "message" => "Database insert failed"
    ]);
} else if (is_string($result)) {
    echo json_encode([
        "status" => "success",
        "redirect" => $result
    ]);
} else {
    echo json_encode([
        "error" => "Invalid"
    ]);
}
