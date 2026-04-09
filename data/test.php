<?php
// data/test.php

header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'PHP is working!',
    'path' => __DIR__
]);
?>