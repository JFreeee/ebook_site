<?php
session_start();
header('Content-Type: application/json');
include('../inc/db.php');

// 检查是否登录
if (!isset($_SESSION['email'])) {
    echo json_encode(['code' => 0, 'msg' => '未登录']);
    exit;
}

$email = $_SESSION['email'];

// // // 查询所有用户信息
$user = $con->prepare("SELECT * FROM users");
$user->execute();  //执行 SQL 语句
$resu = $user->get_result();  //获取执行后的结果集（类似于查询到的“表”）

$users = [];
while ($row = $resu->fetch_assoc()) {
    $users[] = $row;
}

// 返回 JSON
echo json_encode([
    'code' => 1,
    'data' => [
        'email' => $email,
        'users' => $users
    ]
]);
exit;

?>