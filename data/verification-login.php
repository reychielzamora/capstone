<?php
ob_start(); 
require_once __DIR__ . "/../model/OfficialsAuth.php";
header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);
$emp_id = $input['emp_id'] ?? '';
$emp_pass = $input['emp_pass'] ?? '';

if (empty($emp_id) || empty($emp_pass)) {
    echo json_encode(['success' => false, 'message' => 'Employee ID and password are required']);
    exit;
}

$auth = new OfficialsAuth();

$verify = $auth->Login($emp_id, $emp_pass);

//  ob_clean(); 

if ($verify === 3) {
    echo json_encode(['error' => 'Account is on hold']);
    exit;
} else if ($verify === 2) {
    echo json_encode(['error' => 'Account is not found']);
    exit;
} else if ($verify === 4) {
    echo json_encode(['error' => 'User is not found']);
    exit;
}else if ($verify === 1) {
    echo json_encode(['error' => 'Password not found']);
    exit;
}else{
    echo json_encode(['redirect' => $verify]);
    exit;
}
?>