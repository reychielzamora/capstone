<?php
header('Content-Type: application/json; charset=utf-8');
include("../model/Auth.php");

$sup = new Signup();
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['signup'])) {
    // sanitize inputs
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $confirm = $data['confirm'] ?? '';

    // validate
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email']);
        exit;
    }

    // if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
    //     echo json_encode(['error' => 'Password must be at least 8 characters, include 1 uppercase and 1 number']);
    //     exit;
    // }

    // hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // signup
    $input = $sup->signup($email, $hashedPassword);

    if ($input === 1) {
        echo json_encode(['error' => 'Email already taken']);
        exit;
    } else if ($input === 2) {
        echo json_encode(['error' => 'Database prepare failed']);
        exit;
    } else if ($input === 3) {
        echo json_encode(['error' => 'Database execute failed']);
        exit;
    } else if ($input === 4) {
        echo json_encode([
            'email' => $_SESSION['u_email'],
            'success' => 'Account created, please check your email for verification'
        ]);
        exit;
    } else {
        echo json_encode(['error' => 'Unknown error']);
        exit;
    }
}

if (isset($_POST['login-user'])) {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['pass']);

    if ($email == '') {
        $response = array(
            'error' => "Empty email field",
        );
    }

    $result = $sup->login($password, $email);

    if ($result === 1) {
        $response = array(
            'error' => "Incorrect password",
        );
    } else if ($result === 2) {
        $response = array(
            'error' => "Account not found",
        );
    } else if ($result === 3) {
        $response = array(
            'error' => "Account is deactivated",
        );
    } else {
        $response = array(
            'redirect' => $result
        );
    }
    echo json_encode($response);
    exit;
}

if (isset($data['googleLogin'])) {
    $email = $data['email'] ?? '';
    $name = $data['name'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email']);
        exit;
    }
    $result = $sup->insertEmail($email, $name);
    echo json_encode([
        'success' => true,
        'id' => $result['id'],
        'redirect' => $result['redirect']
    ]);
    exit;
}
