<?php
require_once '../model/Client.php';
header('Content-Type: application/json');

$model = new Client();
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 3;

$posts = $model->getPosts($limit, $offset);

echo json_encode(['success' => true, 'posts' => $posts]);