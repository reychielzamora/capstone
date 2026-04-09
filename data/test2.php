change this syntax:

<?php

header('Content-Type: application/json');
header('Cache-Control: no-cache');

ob_start();

$response = [];

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Read JSON input
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!$data) {
        throw new Exception("Invalid JSON data received");
    }

    // Sanitize inputs
    $fname   = trim($data['fname'] ?? '');
    $mname   = trim($data['mname'] ?? '');
    $lname   = trim($data['lname'] ?? '');
    $citizen = trim($data['citizen'] ?? '');
    $sex     = trim($data['sex'] ?? '');
    $civil   = trim($data['civilstatus'] ?? '');
    $age     = trim($data['age'] ?? '');
    $contact = trim($data['contact'] ?? '');
    $email   = trim($data['email'] ?? '');
    $street  = trim($data['street'] ?? '');
    $brgy    = trim($data['Barangay'] ?? '');
    $city    = trim($data['city'] ?? '');
    $type    = trim($data['type'] ?? '');

    $photo     = $data['photo'] ?? '';
    $signature = $data['signature'] ?? '';

    if (!$fname) throw new Exception("First name is required");
    if (!$lname) throw new Exception("Last name is required");
    if (!$contact) throw new Exception("Contact is required");
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email");
    }
    if (!$brgy) throw new Exception("Barangay is required");

    // Upload directory
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Save base64 image
    function saveImage($base64, $prefix, $dir)
    {

        if (!$base64) return '';

        $data = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
        $data = str_replace(' ', '+', $data);

        $path = $dir . $prefix . '_' . time() . '_' . uniqid() . '.png';

        file_put_contents($path, base64_decode($data));

        return $path;
    }

    $photoPath = saveImage($photo, "photo", $uploadDir);
    $sigPath   = saveImage($signature, "signature", $uploadDir);

    // Database
    $mysqli = new mysqli("localhost", "root", "", "bais-system");

    if ($mysqli->connect_error) {
        throw new Exception("Database connection failed");
    }

    $stmt = $mysqli->prepare("
    INSERT INTO tbl_personal_info 
    (FNAME,MNAME,LNAME,CITIZEN,SEX,CIVIL,AGE,CONTACT,EMAIL,STREET,BRGY,CITY,TYPE,PHOTO,SIGNATURE)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param(
        "ssssssissssssss",
        $fname,
        $mname,
        $lname,
        $citizen,
        $sex,
        $civil,
        $age,
        $contact,
        $email,
        $street,
        $brgy,
        $city,
        $type,
        $photoPath,
        $sigPath
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    $response = [
        "status" => "success",
        "message" => "Form submitted successfully",
        "id" => $mysqli->insert_id
    ];

    $stmt->close();
    $mysqli->close();
} catch (Throwable $e) {

    $response = [
        "status" => "error",
        "message" => $e->getMessage()
    ];
}

ob_end_clean();

echo json_encode($response);
exit;

