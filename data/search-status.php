<?php
require '../model/Client.php'; // your connection


$connection = new Client();

$output = $connection->searchStatus();

return $output;