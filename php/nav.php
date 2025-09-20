<?php
header('Content-Type: application/json');
session_start();

// 检查登录状态
$loggedIn = isset($_SESSION['email']);
$email = $loggedIn ? $_SESSION['email'] : '';

// 返回 JSON
echo json_encode([
    'loggedIn' => $loggedIn,
    'email' => $email
]);
exit;
?>