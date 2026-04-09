<?php
session_start();
header('Content-Type: application/json');
echo json_encode([
    'valid' => isset($_SESSION['token_login']) && 
              isset($_SESSION['user_role']) && 
              isset($_SESSION['u_id']) && 
              isset($_SESSION['OFFICIALS_LOG_ID'])
]);
?>