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

// // 查询该用户上传的图书
$stmt = $con->prepare("SELECT id, title, pic, file_path AS file FROM ebooks WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row2 = $result->fetch_assoc()) {
    $books[] = $row2;
}

// 返回 JSON
echo json_encode([
    'code' => 1,
    'data' => [
        'email' => $email,
        'books' => $books
    ]
]);
exit;
?>
