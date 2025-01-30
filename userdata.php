<?php
session_start();
if (!isset($_SESSION['name']) || !isset($_SESSION['email'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$response = [
    'id' => $_SESSION['id'],
    'name' => $_SESSION['name'],
    'email' => $_SESSION['email']
];

header('Content-Type: application/json');
echo json_encode($response);
?>