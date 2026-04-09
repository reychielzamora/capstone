<?php
session_start();
header('Content-Type: application/json');

// Clear specific session data
unset($_SESSION['token_login']);
unset($_SESSION['u_id']);
unset($_SESSION['user_role']);
unset($_SESSION['u_email']);
unset($_SESSION['PP']);

// Optional: Destroy entire session
// session_destroy();

echo json_encode(['status' => 'logged_out']);
exit;
?>