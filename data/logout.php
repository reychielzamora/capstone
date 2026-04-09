<?php
session_start(); // 🔥 REQUIRED

$_SESSION = [];

session_unset();
session_destroy();

header('Location: ../index.php');
exit;